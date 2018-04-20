# New resource simple

Dynamically create a form and send a request to create a new resource.

# Props

|	Name	|	Type	|	Required	|	Description |
|---|---|---|---|
| buttonName	| String	|	Yes		| The text that will appear in the button |
| fieldNames	| Array	|	No	|	Creates a form with fields specificed. **Each element has a label and name**.
| storeEndpoint | String | Yes | URL of where to POST the data |

# Usage

	<new-resource-simple
		buttonName="{{ trans('words.new-asset') }}"
		fieldNames="[{
			label: 'Name',
			name: 'name'
		}]"
		storeEndpoint="{{ route('new-assets.store') }}">
	
	</new-resource-simple>
