# Toggle Permission

This component allows the user to toggle the permission for each role found in the system.

# Usage

	<toggle-permission :role-id.number="{{ $role->id }}"
					   :enabled-prop.number="{{ $role->hasPermissionTo($permission->name) ? 'true' : 'false' }}"
					   permission-name="{{ $permission->name }}">
		{{ $permission->name }}
	</toggle-permission>
