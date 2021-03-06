<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
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
            'new-role',
            'edit-roles',
            'show-users',
            'edit-user-roles',

            'invite-users',

            'view-vendor',
            'create-vendor',
            'update-vendor',
            'delete-vendor',

            'create-vendor-representative',

            'create-vendor-bank',
            'update-vendor-bank',
            'delete-vendor-bank',

            'view-manufacturers',
            'create-manufacturers',
            'update-manufacturers',
            'delete-manufacturers',

            'view-categories',
            'create-categories',
            'update-categories',
            'delete-categories',

            'view-items',
            'create-items',
            'update-items',
            'delete-items',

            'view-item-templates',
            'create-item-templates',
            'update-item-templates',
            'delete-item-templates',

            'view-departments',
            'create-departments',
            'update-departments',
            'delete-departments',

            'view-employees',
            'create-employees',
            'update-employees',
            'delete-employees',

            'view-purchase-orders',
            'create-purchase-orders',
            'update-purchase-orders',
            'delete-purchase-orders',
            'approve-purchase-orders',
            'reject-purchase-orders',
            'upload-purchase-orders',
            'void-purchase-orders',

            'view-purchase-requisitions',
            'create-purchase-requisitions',
            'update-purchase-requisitions',
            'delete-purchase-requisitions',
            'send-purchase-requisitions-to-purchasing',
            'approve-purchase-requisitions',
            'upload-purchase-requisitions',
            'void-purchase-requisitions',
            'receive-purchase-requisitions-notifications',
            'decline-purchase-requisitions',

            'create-purchase-requisition-item',

            'create-approvers',
            'view-approvers',
            'edit-approvers',
            'delete-approvers',

            'create-po-terms',
            'view-po-terms',
            'edit-po-terms',
            'delete-po-terms',
        ];

        // Creating a super admin role.
        $superAdmin = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
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

        \App\Models\User::find(1)->assignRole($superAdmin);
    }
}
