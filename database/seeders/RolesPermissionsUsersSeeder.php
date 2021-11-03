<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;
use App\Laravue\Models\User;
use App\Laravue\Faker;
use Illuminate\Support\Facades\DB;

class RolesPermissionsUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // clean up
        // $tables = ['model_has_permissions',
        //     'model_has_roles',
        //     'role_has_permissions',
        //     'personal_access_tokens',
        //     'permissions',
        //     'roles',
        //     'users',
        // ];
        // foreach ($tables as $tbl) {
        //     DB::table($tbl)->truncate();
        // }
        
        // create roles if not exists
        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role);
        }

        // create permission if not exists
        foreach (Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        // setup permissions roles
        $initiatorRole = Role::findByName(Acl::ROLE_INITIATOR);
        $initiatorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_MANAGE_PROJECT,
            Acl::PERMISSION_DO_ASSEMBLE_FORMULATOR,
            Acl::PERMISSION_DO_ANNOUNCEMENT,
        ]);
    }
}