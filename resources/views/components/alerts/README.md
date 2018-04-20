# Alert - Basic Component

Usage:

	`@if(session()->has('message'))
		@component('components.alerts.basic', ['status' => (session()->has('status') == null) ? 'default' : session()->get('status')])
			{{ session()->get('message') }}
		@endcomponent
	@endif`

Parameters:

1. `status`: 'is-danger', 'is-success', 'is-warning'
2. `message`: Preferably the message is fed from the localization files.
