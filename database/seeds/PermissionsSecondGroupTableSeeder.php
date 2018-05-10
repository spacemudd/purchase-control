<?php

use Illuminate\Database\Seeder;

class PermissionsSecondGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a list of permissions.
        $permissionsList['system'] = [
            'create-addresses',
        ];
        // Creating a super admin role.
        $superAdmin = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ], [
            'name' => 'Super Admin',
        ]);

        // Inserting the permissions to the database.
        $permissionsToMake = [];
        foreach($permissionsList as $type => $permissions) {
            foreach($permissions as $permission) {
                $permissionsToMake[] = [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'type' => $type,
                    'created_at' => new \Carbon\Carbon(),
                    'updated_at' => new \Carbon\Carbon(),
                ];
            }
        }
        \Spatie\Permission\Models\Permission::insert($permissionsToMake);

        // Assigning the permissions to the role.
        $superAdmin->givePermissionTo($permissionsList['system']);
    }
}
