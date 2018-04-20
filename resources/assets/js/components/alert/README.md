# Alert component

# Usage

	import AlertNotification from './../alert/Basic.vue'

	components: {
			'alert-notification': AlertNotification
		},

	<div class="row">
		<div v-if="message">
			<alert-notification :alertClass="alertClass"
								:message="message">
			</alert-notification>
		</div>
	</div>