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
											<th>{{ $t('words.asset-id') }}</th>
											<th>{{ $t('words.manufacturer') }}</th>
											<th>{{ $t('words.manufacturer-serial-number') }}</th>
											<th>{{ $t('words.category') }}</th>
											<th class="text-center">{{ $t('words.unit-price') }}</th>
											<th class="text-center">{{ $t('words.status') }}</th>
											<th class="text-center">{{ $t('words.condition') }}</th>
											<th class="text-center">{{ $t('words.last-known-issuance') }}</th>
										</tr>
									</thead>
									<tbody>

									<template v-if="assets.data.length > 0">
										<template v-for="(asset, index) in assets.data">
											<tr v-bind:item="asset"
													v-bind:index="index"
													v-bind:key="asset.id">
												<td>{{ asset.id }}</td>
												<td>{{ asset.manufacturer.title }}</td>
												<td>{{ asset.manufacturer_serial_number }}</td>
												<td>{{ asset.category.title }}</td>
												<td class="text-right">{{ asset.unit_price }}</td>
												<td class="text-center">{{ asset.status }}</td>
												<td class="text-center">{{ asset.condition }}</td>
												<td class="text-center">
													{{ getIssuedForValue(asset) }}
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
									<tfoot v-if="assets.data.length > 10">
										<tr>
											<th>{{ $t('words.asset-id') }}</th>
											<th>{{ $t('words.manufacturer') }}</th>
											<th>{{ $t('words.manufacturer-serial-number') }}</th>
											<th>{{ $t('words.asset-category') }}</th>
											<th>{{ $t('words.unit-price') }}</th>
											<th>{{ $t('words.status') }}</th>
										</tr>
									</tfoot>
								</table>
								<template v-if="assets.data.length > 0">
									<div class="dataTables_info">{{ assets.total }} {{ $t('words.entries') }}</div>
									<div class="dataTables_paginate paging_simple_numbers" v-if="assets.total > assets.per_page">
										<a class="paginate_button previous" 
											v-if="assets.prev_page_url"
											@click.prevent="selectPage(--assets.current_page)">
											{{ $t('words.previous') }}
										</a>
										<span>

											<a class="paginate_button" :class="n == assets.current_page ? 'current' : ''"
												v-for="n in getPages()"
												@click.prevent="selectPage(n)">
													{{ n }}
											</a>
										</span>
										<a class="paginate_button next"
											v-if="assets.next_page_url"
											@click.prevent="selectPage(++assets.current_page)">
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
				assets: null,
				reportLoading: false,
			}
		},
		methods: {
			getCommittedIssuedAssets() {
				axios.get(this.apiUrl() + '/reports/asset-stock/paginated/10').then(response => {
					this.assets = response.data
					this.addIsExpandingKeysToItems()
					this.isLoading = false
					this.isTableLoading = false
				}).catch(error => {
					alert('Error occured when getting assets')
				})
			},
			/**
			 * @param  {int} index              The index # where the object lies in the array
			 * @param  {int} asset_issuances_id   The DB ID of the issued asset
			 * @return blobl
			 */
			downloadReport() {
				this.reportLoading = true

				axios.post(this.apiUrl() + '/reports/asset-stock/print', {
					type: 'pdf'
				}, {responseType: 'arraybuffer'})
				.then(response => {

					this.reportLoading = false

					let blob = new Blob([response.data],  {type: 'application/pdf'})
					
					fileUtil.downloadBlob(blob, 'asset-stock-report.pdf')

				})
				.catch(error => {
					console.log(error)
					this.reportLoading = false
					alert('Error downloading your requested resource')
				})
			},
			addIsExpandingKeysToItems() {
				this.assets.data.map(item => item.expanded = false)
			},
			getIssuedForValue(asset) {
				if(asset.latest_log === null) {
					//
				} else {
					return asset.latest_log.issued_for
				}
			},
			selectPage(page) {
				// this.$emit('pagination-change-page', page);
				this.isTableLoading = true
				axios.get(this.apiUrl() + '/reports/asset-stock/paginated/10' + '?page=' + page).then(response => {
					this.assets = response.data
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
						return this.assets.last_page;
					}

					var start = this.assets.current_page - this.limit,
					end   = this.assets.current_page + this.limit + 1,
					pages = [],
					index;

					start = start < 1 ? 1 : start;
					end   = end >= this.assets.last_page ? this.assets.last_page + 1 : end;

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
