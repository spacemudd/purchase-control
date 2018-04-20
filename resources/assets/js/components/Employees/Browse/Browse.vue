<template>
	<div class="columns">
		<div class="column is-6">
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find employees"
					size="is-small"
					search-endpoint="employees">
			</simple-search>
		</div>
		<div class="column is-6 has-text-right">
			<create-employee></create-employee>
			<slot name="buttons"></slot>
		</div>
	</div>
</template>
