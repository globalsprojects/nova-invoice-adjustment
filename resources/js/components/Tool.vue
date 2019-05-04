<template>
    <div>
        <loading-card v-if="loading" class="flex flex-col px-6 py-4" style="min-height: 400px;"></loading-card>
        <div class="invoice-adjustment" v-else>
            <heading class="mb-6">{{ messages['tool-name'] }}</heading>

            <adjustment-form :messages="messages" :invoices="invoices"></adjustment-form>
        </div>
    </div>
</template>

<script>
import AdjustmentForm from './AdjustmentForm'

export default {
    name: 'InvoiceAdjustment',
    components: {
        AdjustmentForm
    },
    data: () => ({
        loading: true,
        invoices: {},
        messages: {}
    }),
    mounted() {
        this.getConfig();
    },
    methods: {
        getConfig() {
            let vm = this;
            Nova.request().get('/nova-vendor/invoice-adjustment/config').then(response => {
                vm.invoices = response.data.invoices;
                vm.messages = response.data.messages;
            }).catch(error => {
                this.$toasted.show(error.response.data, { type: 'error' })
            }).finally(() => {
                vm.loading = false;
            });
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
