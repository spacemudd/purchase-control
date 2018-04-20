<template> 
	<section class="section">
		
		<div class="row">

			<div class="col-md-12">
				
				<section class="section">

						<div class="dataTables_wrapper" v-if="!isLoading">
								<table class="table table-bordered datatable dataTable" :class="isTableLoading ? 'table-loading' : ''">
									<span v-if="isTableLoading" class="table-loading-cube">
											<div class="sk-folding-cube">
												<div class="sk-cube1 sk-cube"></div>
												<div class="sk-cube2 sk-cube"></div>
												<div class="sk-cube4 sk-cube"></div>
												<div class="sk-cube3 sk-cube"></div>
											</div>
									</span>
									<thead>
										<tr>
											<th>{{ $t('words.issuance-id') }}</th>
											<th>{{ $t('words.employee') }}</th>
											<th>{{ $t('words.department') }}</th>
											<th>{{ $t('words.issuance-date') }}</th>
											<th>{{ $t('words.total-assets-issued') }}</th>
											<th class="text-center">{{ $t('words.actions') }}</th>
										</tr>
									</thead>
									<tbody>

									<template v-if="asset_issuances.data.length > 0">
										<template v-for="(asset_issuance, index) in asset_issuances.data">
											<tr v-bind:item="asset_issuance"
													v-bind:index="index"
													v-bind:key="asset_issuance.id">
												<td>{{ asset_issuance.prefix + asset_issuance.id }}</td>
												<td>{{ asset_issuance.employee.id  + ' - ' + asset_issuance.employee.name }}</td>
												<td>{{ asset_issuance.department.id + ' - ' + asset_issuance.department.description }}</td>
												<td class="text-center">{{ asset_issuance.issuance_date_human }}</td>
												<td class="text-center">{{ asset_issuance.items.length }}</td>
												<td class="text-center">
													<button @click="displayAssetsAttached(index)" 
														class="btn btn-primary btn-sm">
															<i class="fa fa-eye" ></i>
													</button>
												</td>
											</tr>

											<tr v-if="asset_issuance.expanded">
												<td	colspan="6">
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>{{ $t('words.asset-id') }}</th>
																<th>{{ $t('words.manufacturer') }}</th>
																<th>{{ $t('words.manufacturer-serial-number') }}</th>
																<th>{{ $t('words.issued-for') }}</th>
															</tr>
														</thead>
															<tbody>
																<tr v-for="item in asset_issuance.items">
																	<td>{{ item.asset.id }}</td>
																	<td>{{ item.asset.manufacturer.title }}</td>
																	<td>{{ item.asset.manufacturer_serial_number }}</td>
																	<td>{{ item.issued_for }}</td>
																</tr>
															</tbody>
													</table>
												</td>
											</tr>
										</template>
									</template>
									<template v-else>
									<tr>
										<td colspan="7" class="text-center">
											<strong class="lineheight-spaced">{{ $t('words.no-asset-issuances') }}</strong>
										</td>
									</tr>
									</template>

									</tbody>
									<tfoot v-if="asset_issuances.data.length > 10">
										<tr>
											<th>{{ $t('words.issuance-id') }}</th>
											<th>{{ $t('words.employee') }}</th>
											<th>{{ $t('words.department') }}</th>
											<th>{{ $t('words.issuance-date') }}</th>
											<th>{{ $t('words.total-assets-issued') }}</th>
											<th class="text-center">{{ $t('words.actions') }}</th>
										</tr>
									</tfoot>
								</table>
								<template v-if="asset_issuances.data.length > 0">
									<div class="dataTables_info">{{ asset_issuances.total }} {{ $t('words.entries') }}</div>
									<div class="dataTables_paginate paging_simple_numbers" v-if="asset_issuances.total > asset_issuances.per_page">
										<a class="paginate_button previous" 
											v-if="asset_issuances.prev_page_url"
											@click.prevent="selectPage(--asset_issuances.current_page)">
											{{ $t('words.previous') }}
										</a>
										<span>

											<a class="paginate_button" :class="n == asset_issuances.current_page ? 'current' : ''"
												v-for="n in getPages()"
												@click.prevent="selectPage(n)">
													{{ n }}
											</a>
										</span>
										<a class="paginate_button next"
											v-if="asset_issuances.next_page_url"
											@click.prevent="selectPage(++asset_issuances.current_page)">
											{{ $t('words.next') }}
										</a>
									</div>
								</template>

						</div>

				</section>

			</div>

		</div>

	</section>
</template>

<script>
	import * as fileUtil from '../../../utils/file.js'

	export default {
		mounted() {
			this.getCommittedIssuedAssets()
		},
		data() {
			return {
				limit: 0,
				isLoading: true,
				isTableLoading: true,
				asset_issuances: null,
				reportLoading: false,
			}
		},
		methods: {
			getCommittedIssuedAssets() {
				axios.get(this.apiUrl() + '/reports/asset-issuances/paginated/10').then(response => {
					this.asset_issuances = response.data
					this.addIsExpandingKeysToItems()
					this.isLoading = false
					this.isTableLoading = false
				}).catch(error => {
					alert('Error occured when getting committed issued assets')
				})
			},
			/**
			 * @param  {int} index              The index # where the object lies in the array
			 * @param  {int} asset_issuances_id   The DB ID of the issued asset
			 * @return blobl
			 */
			downloadReport() {
				this.reportLoading = true

				axios.post(this.apiUrl() + '/reports/asset-issuances/print', {
					type: 'pdf'
				}, {responseType: 'arraybuffer'})
				.then(response => {

					this.reportLoading = false

					let blob = new Blob([response.data],  {type: 'application/pdf'})
					
					fileUtil.downloadBlob(blob, 'asset-issuance-report.pdf')

				})
				.catch(error => {
					console.log(error)
					this.reportLoading = false
					alert('Error downloading your requested resource')
				})
			},
			displayAssetsAttached(index) {
				
				var newObject = this.asset_issuances.data[index]

					if(newObject.expanded) {
						newObject.expanded = false
						newObject.isLoading = false
					} else {
						newObject.isLoading = true
						newObject.expanded = true
					}

				this.$set(this.asset_issuances.data, index, newObject)

			},
			addIsExpandingKeysToItems() {
				this.asset_issuances.data.map(item => item.expanded = false)
			},
			selectPage(page) {
				// this.$emit('pagination-change-page', page);
				this.isTableLoading = true
				axios.get(this.apiUrl() + '/asset-issuances/paginated/10' + '?page=' + page).then(response => {
					this.asset_issuances = response.data
					this.isTableLoading = false
				}).catch(error => {
					console.log('Error occured while selecting page number: ' + page)
				})
			},
			getPages() {
				if (this.limit === -1) {
						return 0;
					}

					if (this.limit === 0) {
						return this.asset_issuances.last_page;
					}

					var start = this.asset_issuances.current_page - this.limit,
					end   = this.asset_issuances.current_page + this.limit + 1,
					pages = [],
					index;

					start = start < 1 ? 1 : start;
					end   = end >= this.asset_issuances.last_page ? this.asset_issuances.last_page + 1 : end;

					for (index = start; index < end; index++) {
						pages.push(index);
					}n

					console.log(pages)

					return pages;
			}
		}
	}
</script>

<style lang="sass">

</style>
