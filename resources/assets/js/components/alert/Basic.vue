<template>
	<div>
		<div class="col-md-6 col-md-offset-3">
			<div class="alert" :class="alertClass">
				<p>{{ message }}</p>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			alertClass: {
				type: String,
				default: 'alert-default'
			},
			message: {
				type: String,
				default: 'Error occured'
			}
		},
		mounted() {
			//
		}
	}
</script>
