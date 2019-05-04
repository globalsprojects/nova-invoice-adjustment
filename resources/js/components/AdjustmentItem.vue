<template>
	<div class="adjustment-item" :class="{'is-delete': !!(deleted)}">
		<p>Name: {{ item.streaming.name }}</p>
		<p>Discount: {{ item.discount ? 'yes' : 'no' }}</p>
		<p>Price: {{ item.price }}</p>
	</div>
</template>
<script>
	export default {
		name: 'adjustment-item',
		props: ['item', 'deleted'],
	}
</script>
<style lang="scss">
	.adjustment-item {
		background: green;
		margin-bottom: 20px;
		cursor: pointer;

		&.is-delete {
			background: red;
		}
	}
</style>