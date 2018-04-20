<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:placeholder-text="$t('words.find-purchase-orders')"
					:hyper-linked-results="true"
					search-endpoint="purchase-orders">
			</simple-search>
		</div>
		<div class="level-right">
			<button class="button is-primary pull-right" @click="showNewPurchaseOrderModal()">
				{{ $t('words.new-purchase-order') }}
			</button>
		</div>
	</div>
</template>

<script>
	export default {
		methods: {
			editLink(purchase_order_id) {
				return this.baseUrl() + '/procedures/purchase-orders/' + purchase_order_id + '/edit'
			},
			showLink(purchase_order_id) {
				return this.baseUrl() + '/procedures/purchase-orders/' + purchase_order_id
			},
            showNewPurchaseOrderModal() {
			    this.$store.commit('PurchaseOrder/setModalActive', true);
			},
		}
	}
</script>
