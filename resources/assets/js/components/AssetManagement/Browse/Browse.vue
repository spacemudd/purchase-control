<template> 
	<section class="section">

		<div class="row">
			<div class="col-md-12">
				<div class="level">
					<div class="level-left">
						<simple-search search-endpoint="asset-management"
									   target-url="/procedures/asset-management">
						</simple-search>
					</div>
					<div class="level-right"></div>
				</div>
			</div>
		</div>
		
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
											<th>{{ $t('words.description') }}</th>
											<th>{{ $t('words.manufacturer-serial-number') }}</th>
											<th>{{ $t('words.system-tag-number') }}</th>
											<th>{{ $t('words.finance-tag-number') }}</th>
											<th class="text-center">{{ $t('words.unit-price') }} ({{ $t('words.saudi-riyals') }})</th>
											<th>{{ $t('words.status') }}</th>
											<th class="text-center">{{ $t('words.actions') }}</th>
										</tr>
									</thead>
									<tbody>

									<template v-if="assets.data.length > 0">
										<template v-for="asset in assets.data">
											<tr>
												<td>{{ asset.id }}</td>
												<td>{{ asset.description }}</td>
												<td>{{ asset.manufacturer_serial_number }}</td>
												<td>{{ asset.system_tag_number }}</td>
												<td>{{ asset.finance_tag_number }}</td>
												<td class="text-right">{{ asset.unit_price_human }}</td>
												<td class="text-center">{{ asset.status }}</td>
												<td class="text-center">
													<a :href="baseUrl() + '/procedures/asset-management/' + asset.id"
														class="btn btn-info btn-sm">
														<i class="fa fa-eye"></i>
													</a>
												</td>
											</tr>
										</template>
									</template>
									<template v-else>
									<tr>
										<td colspan="7" class="text-center">
											<strong class="lineheight-spaced">{{ $t('statements.no-registered-assets') }}</strong>
										</td>
									</tr>
									</template>

									</tbody>
									<tfoot v-if="assets.data.length > 10">
										<tr>
											<th>{{ $t('words.asset') }}</th>
											<th>{{ $t('words.manufacturer-serial-number') }}</th>
											<th>{{ $t('words.system-tag-number') }}</th>
											<th>{{ $t('words.finance-tag-number') }}</th>
											<th>{{ $t('words.unit-price') }}</th>
											<th class="text-center">{{ $t('words.actions') }}</th>
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
						<div v-else>
							<div class="sk-folding-cube">
								<div class="sk-cube1 sk-cube"></div>
								<div class="sk-cube2 sk-cube"></div>
								<div class="sk-cube4 sk-cube"></div>
								<div class="sk-cube3 sk-cube"></div>
							</div>
						</div>

				</section>

			</div>

		</div>

	</section>
</template>

<script>
	export default {
		mounted() {
			this.getAssets()
		},
		data() {
			return {
				isLoading: true,
				assets: null,
				limit: 0,
				isTableLoading: true
			}
		},
		methods: {
			getAssets() {
				axios.get(this.apiUrl() + '/asset-management/paginated/10').then(response => {
					this.assets = response.data
					this.isLoading = false
					this.isTableLoading = false
				}).catch(error => {
					alert('Error occured when getting issued assets')
				})
			},
			selectPage(page) {
				// this.$emit('pagination-change-page', page);
				this.isTableLoading = true
				axios.get(this.apiUrl() + '/asset-management/paginated/10' + '?page=' + page).then(response => {
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
