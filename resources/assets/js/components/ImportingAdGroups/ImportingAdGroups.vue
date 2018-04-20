<template>

	<div class="columns is-multiline">

		<div class="column is-12">
			<div v-if="message">
				<alert-notification :alertClass="alertClass"
									:message="message">
				</alert-notification>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
		
			<div class="panel-heading">
					{{ $t('words.import-ad-groups') }}
			</div>
			
			<div class="panel-block">
				<transition>
					<div v-if="isLoading"
						 style="margin:0 auto;"
						 class="block has-text-centered">
						<loading-screen></loading-screen>
					</div>

					<template v-if="selectingView">
						<template v-if="groups.length > 0 ">
							<table class="table is-fullwidth">
								<thead>
									<tr>
										<th>{{ $t('words.common-name') }}</th>
										<th class="text-center">{{ $t('words.selected') }}</th>
									</tr>
									</thead>
								<tbody>
									<tr v-for="group in groups"
										@click="handleGroup(group)"
										:class="group.selected ? 'selected-row' : 'unselected'">

											<td>{{ group.common_name }}</td>
											<td class="text-center">
												<button class="button"
														:class="group.selected ? '' : 'is-primary'">
													{{ group.selected ? $t('words.selected') : $t('words.unselected') }}
												</button>
											</td>

									</tr>
								</tbody>
							</table>
						</template>
						<div v-else>
							<div class="section text-center">
								<h3><strong>{{ $t('words.no-groups-found') }}</strong></h3>
								<p>{{ $t('statements.no-ad-groups-found-help') }}</p>
							</div>
						</div>
					</template>

					<template v-if="importingView">
						<template v-if="selectedGroups.length > 0">
							<table class="table is-fullwidth">
								<thead>
									<tr>
										<th>{{ $t('words.role-name-to-be-saved') }}</th>
										<th>{{ $t('words.common-name') }}</th>
									</tr>
									</thead>
								<tbody>
									<tr v-for="group in selectedGroups">
										
										<td @click.prevent="editGroup(group)">
											<div v-if="group.is_editing">
												<input class="form-control"
														v-model="group.role_name"
														@blur="doneEditGroup(group)"
														@keyup.enter="doneEditGroup(group)"
														@keyup.esc="cancelEditGroup(group)" v-focus>
											</div>
											<div v-else>
												{{ group.role_name }}
												<i class="fa fa-pencil"></i>
											</div>
										</td>

										<td>{{ group.common_name }}</td>
									</tr>
								</tbody>
							</table>
						</template>
					</template>


					
					<div v-if="finalView">
							<div class="section text-center">
								<h3><strong>{{ $t('words.done') }}</strong></h3>
								<p>{{ $t('statements.roles-to-modify-permissions') }}</p>
							</div>
					</div>
				</transition>
			</div>
		
		</div>
		</div>

		<div class="column is-12" v-if="!finalView">
			<div class="level">
				<div class="level-left"></div>
				<div class="level-right"><button class="button is-success" @click="continueTask()">{{ $t('words.continue') }}</button></div>
			</div>
		</div>

	</div>
</template>

<script>
	import AlertNotification from './../alert/Basic.vue'

	const focus = {
		inserted(el) {
				el.focus()
			},
	}

	export default {
		props: {
			adGroupId: {
				type: Number,
				required: true
			}
		},
		components: {
			'alert-notification': AlertNotification
		},
		directives: { focus },
		mounted() {
			this.loadAdGroups()
		},
		data() {
			return {
				isLoading: true,
				alertClass: '',
				message: '',
				groups: [],
				selectedGroups: [],
				selectingView: false,
				importingView: false,
				finalView: false,
			}
		},
		methods: {
			loadAdGroups() {
				axios.get(this.apiUrl() + "/active-directory/" + this.adGroupId + "/get-ad-groups").then(response => {
					response.data.forEach((group) => {
						group.selected = false
						group.role_name = group.common_name
						group.is_editing = false
					})
					this.groups = response.data
					this.isLoading = false
					this.selectingView = true
				}).catch(error => {
					this.isLoading = false
					this.selectingView = true
					this.alertClass = 'alert-danger'
					this.message = this.getTrans('words.error-occurred')
				})
			},
			handleGroup(group) {

				if(group.selected) {
					
					let groupToDelete = this.selectedGroups.indexOf(group)
					this.selectedGroups.splice(groupToDelete, 1);

					group.selected = false

				} else {
					this.selectedGroups.push(group)

					group.selected = true
				}
			},
			editGroup(group) {
				group.old_role_name = group.role_name
				group.role_name = null
				group.is_editing = true
			},
			doneEditGroup(group) {
				if(group.is_editing) {

					if(group.role_name == null || '') {
						group.role_name = group.old_role_name
					}

					group.is_editing = false
				}
			},
			continueTask() {
				

				if(this.importingView == true) {
					this.importingView = false
					this.isLoading = true
					axios.post(this.apiUrl() + '/active-directory/' 
											 + this.adGroupId 
											 + '/store-ad-groups', 
											{
												groups: this.selectedGroups
											}
							).then(response => {
								this.alertClass = 'alert-success'
								this.message = response.data.message
								this.importingView = false
								this.finalView = true
								this.isLoading = false
							}).catch(error => {
								this.alertClass = 'alert-danger'
								this.message = error.response.data.groups[0]

								this.isLoading = false
								this.importingView = true
							})
				}

				if(this.selectingView == true) {
					this.selectingView = false
					this.importingView = true

					this.isLoading = false
				}

			},

		}
	}
</script>

<style lang="sass" scoped>
	.unselected
		transition: all 0.3s ease-in-out
	.selected-row
		background-color: #ff0001
		color: white
		transition: all 0.3s ease-in-out
		button
</style>
