# Simple search module

It returns the ID of the model as a 'value' field.

# Props

|	Name	| Type | required | Description |
|---|---|---|--- |
| searchEndpoint | string | yes |API endpoint (**URL**) that returns search results |
| fieldName | string | yes |HTML 
| placeholder | string | no |Text that appears for the use prior to writing |

# Usage

	<simple-search-return-id
		search-endpoint="{{ route('api.search.employee') }}"
		field-name="employee_id"
	>
	</simple-search-return-id>

# Rendering

It injects this code `<input type='hidden' name='...' value='..'>` in the page.
