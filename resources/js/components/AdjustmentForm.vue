<template>
	<div class="card px-6 py-4" v-if="!adjusted">
		<h3 class="text-base text-80 font-bold mb-3">{{ messages['invoice-title'] }}</h3>
		<div class="mb-6">
			<p class="mb-2 italic">{{ messages['invoice-subtitle'] }}</p>
			<multiselect class="mb-6 invoice-select" v-model="invoice" :options="invoices" label="reference" track-by="id" :placeholder="messages['multiselect-placeholder']" optionsLimit="10" :selectLabel="messages['multiselect-select-text']" :deselectLabel="messages['multiselect-remove-text']" :selectedLabel="messages['multiselect-selected-text']">
				<template slot="singleLabel" slot-scope="props">
					<span class="option__desc">
						<span class="option__title">{{ props.option.reference }}</span> <span class="option__small" v-if="props.option.rectifieds.length">- {{ messages['adjusted'] }} ({{ props.option.rectifieds.map(rectified => rectified.reference).toString() }})</span>
					</span>
				</template>
				<template slot="option" slot-scope="props">
					<div class="option__desc">
						<span class="option__title">{{ props.option.reference + ' (' + props.option.customer_name + ')'}}</span> <span class="option__small" v-if="props.option.rectifieds.length">- {{ messages['adjusted'] }} ({{ props.option.rectifieds.map(rectified => rectified.reference).toString() }})</span>
					</div>
				</template>
				<span slot="noResult">{{ messages['multiselect-no-result'] }}</span>
				<span slot="noOptions">{{ messages['multiselect-no-options'] }}</span>
			</multiselect>
		</div>
		<div v-if="invoice" class="invoice-details">
			<h3 class="text-base text-80 font-bold mb-3">{{ messages['tickets-title'] }}</h3>
			<div class="mb-8">
				<p class="mb-2 italic">{{ messages['tickets-subtitle'] }}</p>
				<div class="flex flex-wrap -mx-3 mb-3">
					<div class="px-3 mb-6 w-1/3" v-for="item in invoice.tickets" v-key="item.id" @click="toggleDeleteItem(item)">
						<adjustment-item :item="item" :deleted="deletedItems.includes(item)"></adjustment-item>
					</div>
				</div>
			</div>
		</div>
		<button :disabled="loading || !deletedItems.length" class="btn btn-default btn-primary" @click="applyAdjustment()">{{ messages['apply'] }}</button>
	</div>
	<success-panel v-else @reset="reset" :messages="messages" :adjusted="adjusted"></success-panel>
</template>
<script>
import Multiselect from 'vue-multiselect'
import AdjustmentItem from './AdjustmentItem'
import SuccessPanel from './SuccessPanel'

export default {
	name: 'adjustment-form',
	components: {
		Multiselect,
		AdjustmentItem,
		SuccessPanel
	},
	props: ['messages', 'invoices'],
	data: () => ({
		loading: false,
		invoice: null,
		deletedItems: [],
		adjusted: null
	}),
	methods: {
		isDeleted(item) {
			return this.deletedItems.map(deletedItem => deletedItem.id).includes(item.id)
		},
		toggleDeleteItem(item) {
			if (this.isDeleted(item)) {
				this.deletedItems.splice(this.deletedItems.indexOf(item), 1)
			} else {
				this.deletedItems.push(item)
			}

			if (this.deletedItems.length > 0) {
				this.invoice.changes = {
					amount: -Math.abs(this.deletedItems.reduce((price, item) => price + item.price, 0)),
					tickets: this.invoice.tickets.filter(filteredItem => this.deletedItems.includes(filteredItem))
				}
			} else {
				this.invoice.changes = null
			}
		},
		applyAdjustment() {
			this.loading = true
			let vm = this
            Nova.request().post('/nova-vendor/invoice-adjustment/apply', vm.invoice).then(response => {
                console.log(response)
                this.adjusted = response.data.adjusted
            }).catch(error => {
                this.$toasted.show(error.response.data.message, { type: 'error' })
            }).finally(() => {
                vm.loading = false
            })
		},
		reset() {
			location.reload();
		}
	}
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
	.invoice-select {
		max-width: 50%;
	}
</style>