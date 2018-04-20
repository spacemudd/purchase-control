# Simple search Show

A  searchbar that has a 'go' button next to it that redirects the user to the 'show' page if clicked on.

# Props

| name | type | description |
|---|---|---|
|searchEndpointUrl | string | URL fed by the backend that returns results|
|targetUrl | string | URL fed by the backend to redirect the user to  |

# Usage

	<simple-search-show
		target-url="settings/asset-templates"
		search-endpoint-url="{{ route('api.search.asset-templates') }}"
	>
	</simple-search-show>
