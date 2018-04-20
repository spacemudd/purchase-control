<template> 
	<div v-if="completed">
		{{ $t('statements.asset-sent-to-repair') }}.
	</div v-end>
	<div v-else>
		<form class="form-horizontal form-groups-bordered">
			<div class="form-group">
				<label class="col-md-3 control-label">{{ $t('words.vendor') }} *</label>
			
				<div class="col-md-5">

						<select
						class="form-control" name="vendor_id" 
						v-model="vendor_id"
						required>
							<option disabled value="">{{ $t('statements.please-select-one') }}</option>
							<option v-for="vendor in vendors" :value="vendor.id">{{ vendor.id + ' - ' + vendor.description }}</option>
						</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">{{ $t('words.submission-date') }} *</label>
			
				<div class="col-md-5">
					<masked-input v-model="submission_date" mask="11/11/1111" class="form-control" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">{{ $t('words.tracking-id') }}</label>
			
				<div class="col-md-5">
					<input v-model="tracking_id" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-5">
					<button
					@click="sendTovendor"
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

	export default {
		components: {
			MaskedInput
		},
		props: {
			asset: {
						type: Object,
						required: true,
					},
			vendors: {
						type: Array,
						required: true,
			}
		},
		mounted() {
			console.log(this.asset)
			console.log(this.vendors)
			if(this.asset.status == 'in-repair') {
				this.completed = true
			}
		},
		data() {
			return {
				loading: false,
				vendor_id: null,
				submission_date: null,
				tracking_id: null,
				completed: false
			}
		},
		methods: {
			sendTovendor() {
				this.loading = true
				axios.post(this.apiUrl() + '/asset-repair/' + this.asset.id + '/send', {
					vendor_id: this.vendor_id,
					submission_date: this.submission_date,
					tracking_id: this.tracking_id
				})
				.then(response => {
					console.log(response.data)
					this.loading = false
					this.completed = true
				}).catch(error => {
					this.loading = false
					alert('Error occured when sending the asset for repair. Asset ID: ' + this.asset.id)
				})
			},
		}
	}
</script>
