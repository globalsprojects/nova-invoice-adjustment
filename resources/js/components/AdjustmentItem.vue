<template>
	<div class="card px-6 py-4 adjustment-item flex flex-wrap mb-3" :class="{'is-delete': !!(deleted)}">
		<div class="w-1/3 pr-3">
			<img :src="'/storage/' + item.streaming.image" alt="Streaming img">
		</div>
		<div class="card-info w-2/3">
			<h3 class="text-2xl text-80 font-bold mb-2">{{ item.streaming.name }}</h3>
			<p class="mb-1">{{ item.discount ? 'Con descuento del ' + item.discount.percentage + '%' : 'Sin descuento' }}</p>
			<p class="text-base text-80 font-bold mb-1">{{ formattedPrice(item.price) }}</p>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'adjustment-item',
		props: ['item', 'deleted'],
		methods: {
			formattedPrice(price) {
				return (price / 100).toFixed(2) + ' €'
			}
		}
	}
</script>
<style lang="scss">
	.adjustment-item {
		border: 2px solid transparent;
		cursor: pointer;

		&:hover {
			border-color: var(--primary);
		}

		&.is-delete {
			background: rgba(231, 68, 68, 0.25);

			&:hover {
				border-color: var(--danger);
			}
		}

		.card {
			&-img img {
				max-width: 100px;
			}
		}
	}
</style>