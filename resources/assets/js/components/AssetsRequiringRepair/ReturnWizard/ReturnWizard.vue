<template> 
	<div v-if="completed">
		{{ $t('statements.asset-registered') }}. <br/><br/>

		<a :href="this.baseUrl() + '/procedures/asset-repair'">{{ $t('words.assets-repair-management') }}</a>
	</div v-end>
	<div v-else>
		
		<div class="row" v-if="message">
			<div>
				<alert-notification :alertClass="alertClass"
									:message="message">
				</alert-notification>
			</div>
		</div>

		<div class="row" v-if="errors">
			<div class="col-md-10 col-md-offset-1">
				<div class="alert alert-danger">
					<ul v-for="(message, key) in errors">
						<li>{{ message[0] }}</li>
					</ul>
				</div>
			</div>
		</div>

		<form class="form-horizontal form-groups-bordered">
			<div class="form-group">
				<label class="col-md-5 control-label">{{ $t('words.return-status') }} *</label>
			
				<div class="col-md-5">

						<select
						class="form-control"
						v-model="return_status"
						@change="clearOldValueIfNecessary(return_status)"
						required>
							<option disabled value="">{{ $t('statements.please-select-one') }}</option>
							
							<option value="fixed">{{ $t('words.fixed') }}</option>
							<option value="replaced">{{ $t('words.replaced') }}</option>

						</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-5 control-label">{{ $t('words.return-date') }} *</label>
			
				<div class="col-md-5">
					<masked-input v-model="return_date" mask="11/11/1111" class="form-control" required />
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-5 control-label">{{ $t('words.vendor-delivery-number') }}</label>
			
				<div class="col-md-5">
					<input class="form-control" v-model="vendor_delivery_number">
				</div>
			</div>

			<!-- When the asset has been replaced, new asset information must be inserted -->
			<template v-if="return_status == 'replaced'">
				<div class="form-group">
					<label class="col-md-5 control-label">{{ $t('words.manufacturer-serial-number') }} *</label>

					<div class="col-md-5">
						<input v-model="manufacturer_serial_number" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-5 control-label">{{ $t('words.system-tag-number') }}</label>

					<div class="col-md-5">
						<input v-model="system_tag_number" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-5 control-label">{{ $t('words.finance-tag-number') }}</label>

					<div class="col-md-5">
						<input v-model="finance_tag_number" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-5 control-label">{{ $t('words.rfid-technology') }}</label>

					<div class="col-md-5">
						<input v-model="rfid" class="form-control">
					</div>
				</div>
			</template>

			<div class="form-group">
				<div class="col-sm-offset-5 col-sm-5">
					<button
					@click="validateAndSendForm()"
					class="btn btn-success btn-lg"
					:disabled="loading">
						<i v-if="loading" class="fa fa-circle-o-notch fa-spin fa-fw"></i>
						{{ $t('words.send') }}
					</button>
				</div>
			</div>
		</form>
	</div>
</template>

<script>
	import MaskedInput from 'vue-masked-input'
	import AlertNotification from './../../alert/Basic.vue'

	export default {
		components: {
			MaskedInput,
			AlertNotification
		},
		props: {
			asset: {
						type: Object,
						required: true
					}
		},
		mounted() {
			console.log(this.asset)
			if(this.asset.status == 'in-repair') {
				this.completed = false
			}
		},
		data() {
			return {
				loading: false,
				message: null,
				errors: null,
				alertClass: 'alert-warning',
				completed: false,
				return_status: null,
				return_date: null,
				vendor_delivery_number: null,
				manufacturer_serial_number: null,
				system_tag_number: null,
				finance_tag_number: null,
				rfid: null,
			}
		},
		methods: {
			clearOldValueIfNecessary(return_status) {
				if(return_status == 'fixed') {
					this.manufacturer_serial_number = null
					this.system_tag_number = null
					this.finance_tag_number = null
				}
			},
			validateAndSendForm() {
				this.erorrs = null
				this.validateForm()
			},
			validateForm() {
				if( (this.return_status == null || this.return_date == null) ) {
					this.message = this.getTrans('words.fill-the-fields')
					return false;
				} else {
					this.sendForm()
				}

			},
			sendForm() {
				this.loading = true
				axios.post(this.apiUrl() + '/asset-repair/' + this.asset.id + '/receive', {
					return_status: this.return_status,
					return_date: this.return_date,
					vendor_delivery_number: this.vendor_delivery_number,
					manufacturer_serial_number: this.manufacturer_serial_number,
					system_tag_number: this.system_tag_number,
					finance_tag_number: this.finance_tag_number,
					rfid: this.rfid
				})
				.then(response => {
					console.log(response.data)
					this.loading = false
					this.completed = true
				}).catch(error => {
					this.loading = false
					this.errors = error.response.data.errors
					console.log(error.response.data)
				})
			}
		}
	}
</script>
