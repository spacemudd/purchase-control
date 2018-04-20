<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find manufacturers"
					search-endpoint="manufacturers">
			</simple-search>
		</div>
		<div class="level-right">

			<a class="button is-primary pull-right" @click="$store.commit('Manufacturer/setNewModal', true)">
				{{ $t('words.new-manufacturer') }}
			</a>

		</div>
	</div>
</template>
