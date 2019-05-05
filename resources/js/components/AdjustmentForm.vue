<template>
	<div>
		<multiselect class="mb-3" v-model="invoice" :options="invoices" label="reference" track-by="id">
		    <template slot="singleLabel" slot-scope="props">
		    	<span class="option__desc">
		    		<span class="option__title">{{ props.option.reference }}</span>
		    	</span>
		    </template>
			<template slot="option" slot-scope="props">
				<div class="option__desc">
					<span class="option__title">{{ props.option.reference + ' (' + props.option.customer_name + ')'}}</span> <span class="option__small" v-if="props.option.rectifieds.length">- Rectificada ({{ props.option.rectifieds.map(rectified => rectified.reference).toString() }})</span>
				</div>
			</template>
			<span slot="noResult">{{ messages['recipients-no-result'] }}</span>
			<span slot="noOptions">{{ messages['recipients-no-options'] }}</span>
		</multiselect>
		<div v-if="invoice" class="mb-3">
			<div v-for="item in invoice.tickets" @click="toggleDeleteItem(item)">
				<adjustment-item :item="item" :deleted="deletedItems.includes(item)"></adjustment-item>
			</div>
		</div>
		<button :disabled="loading || !deletedItems.length" class="btn btn-default btn-primary" @click="applyAdjustment()">Apply</button>
		<pre><code>{{ invoice }}</code></pre>
	</div>
</template>
<script>
import Multiselect from 'vue-multiselect'
import AdjustmentItem from './AdjustmentItem'

export default {
	name: 'adjustment-form',
	components: {
		Multiselect,
		AdjustmentItem
	},
	props: ['messages', 'invoices'],
	data: () => ({
		loading: false,
		invoice: null,
		deletedItems: []
	}),
	methods: {
		toggleDeleteItem(item) {
			if (this.deletedItems.includes(item)) {
				this.deletedItems.splice(this.deletedItems.indexOf(item.id), 1)
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
            }).catch(error => {
                this.$toasted.show(error.response.data.message, { type: 'error' })
            }).finally(() => {
                vm.loading = false
            })
		}
	}
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
	
</style>