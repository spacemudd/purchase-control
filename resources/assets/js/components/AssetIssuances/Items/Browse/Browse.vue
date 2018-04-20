<template>
	<div>
			<div class="panel panel-primary">
			
				<div class="panel-heading">
					<div class="panel-title">
						{{ $t('words.issued-items') }}
					</div>
					<div class="panel-options">
						<a  v-if="isDraft()" :href="baseUrl() + '/procedures/asset-issuances/' + assetIssuanceId + '/items/create'"><i class="fa fa-plus-square-o fa-lg"></i></a>
					</div>
				</div>
				
				<div class="panel-body">
					<div v-if="isLoading">
						<div class="sk-folding-cube">
							<div class="sk-cube1 sk-cube"></div>
							<div class="sk-cube2 sk-cube"></div>
							<div class="sk-cube4 sk-cube"></div>
							<div class="sk-cube3 sk-cube"></div>
						</div>
					</div>
					<div v-else>
						<table class="table" v-if="issuance.items.length > 0">
							<thead>
							<tr>
								<th>{{ $t('words.asset-code') }}</th>
								<th>{{ $t('words.asset') }}</th>
								<th>{{ $t('words.manufacturer') }}</th>
								<th>{{ $t('words.manufacturer-serial-number') }}</th>
								<th>{{ $t('words.system-tag-number') }}</th>
								<th>{{ $t('words.issued-for') }}</th>
								<th class="text-center">{{ $t('words.actions') }}</th>
							</tr>
							</thead>
							<tbody>
									<tr v-for="(item, index) in issuance.items">
										<td>{{ item.asset.asset_template.code }}</td>
										<td>{{ item.asset.asset_template.description }}</td>
										<td>{{ item.asset.asset_template.manufacturer.description }}</td>
										<td>{{ item.asset.manufacturer_serial_number }}</td>
										<td>{{ item.asset.system_tag_number }}</td>
										<td>{{ item.issued_for }}</td>
										<td class="text-center">
											<a 
											v-if="isDraft()"
											@click="deleteItem(index, item)"
											class="btn btn-danger btn-sm btn-icon icon-left">
												<i class="fa fa-ban"></i>
												{{ $t('words.delete') }}
											</a>
										</td>
									</tr>
							</tbody>
						</table>
						<div v-else>
							<div class="text-center">
								<img :src="this.baseUrl() + '/img/assets/asset-issuance-items.svg'" class="new-slate">
								<h3>{{ $t('slates.new-asset-issuance-item') }}</h3>
								<p>
									<a class="btn btn-default btn-primary btn-lg"
										:href="baseUrl() + '/procedures/asset-issuances/' + assetIssuanceId + '/items/create'">
										{{ $t('words.find-asset') }}
									</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			
			</div>

	</div>
</template>

<script>
	export default {
		props: {
			assetIssuanceId: {
				type: Number,
				required: true
			},
			assetIssuanceStatus: {
				type: String,
				required: true
			}
		},
		data() {
			return {
				issuance: [],
				isLoading: true
			}
		},
		mounted() {
			this.getAssetIssuanceItems(this.assetIssuanceId)
		},
		methods: {
			getAssetIssuanceItems(asset_issuance_id) {
				axios.get(this.apiUrl() + '/procedures/asset-issuances/' + asset_issuance_id + '/items').then(response => {
					this.issuance = response.data
					this.isLoading = false
				})
			},
		   	deleteItem(index, item) {
		   		this.isLoading = true

		   		axios.delete(this.apiUrl() + '/procedures/asset-issuances/' + this.assetIssuanceId + '/items/' + item.id + '/delete')
		   		.then(response => {
		   			this.isLoading = false
		   			this.issuance.items.splice(index, 1)
		   		}).catch(error => {
		   			this.isLoading = false
		   			alert('Error occured while deleting an item')
		   		})
		   	},
		   	isDraft() {
		   		return this.assetIssuanceStatus == 'draft'
		   	}
		}
	}
</script>
