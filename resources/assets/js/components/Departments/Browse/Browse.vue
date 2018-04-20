<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find departments"
					search-endpoint="departments">
			</simple-search>
		</div>
		<div class="level-right">
			<slot name="options"></slot>
		</div>
	</div>
</template>
