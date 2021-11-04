<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;
use App\Laravue\Models\User;
use App\Laravue\Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $aprole = Role::findByName(Acl::ROLE_ADMIN_CENTRAL);
        $aprole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_VIEW_MENU_PROJECT_PRE_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ISSUED,
            Acl::PERMISSION_VIEW_MENU_INITIATOR,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR,
            Acl::PERMISSION_VIEW_MENU_LPJP,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR_EXPERT,
            Acl::PERMISSION_VIEW_MENU_LUK,
            Acl::PERMISSION_VIEW_MENU_EXPERT,
            Acl::PERMISSION_VIEW_MENU_CONFIGURATION,
            Acl::PERMISSION_VIEW_MENU_COMPONENT,
            Acl::PERMISSION_VIEW_MENU_PARAMS,
            Acl::PERMISSION_VIEW_MENU_SOP,
            Acl::PERMISSION_VIEW_MENU_CLUSTER,
            Acl::PERMISSION_MANAGE_INITIATOR,
            Acl::PERMISSION_MANAGE_LPJP,
            Acl::PERMISSION_MANAGE_FORMULATOR_EXPERT,
            Acl::PERMISSION_MANAGE_LUK,
            Acl::PERMISSION_MANAGE_EXPERT,
            Acl::PERMISSION_MANAGE_COMPONENT,
            Acl::PERMISSION_MANAGE_PARAMS,
            Acl::PERMISSION_MANAGE_SOP,
            Acl::PERMISSION_MANAGE_CLUSTER,
        ]);

        // admin regional
        $arRole = Role::findByName(Acl::ROLE_ADMIN_REGIONAL);
        $arRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_VIEW_MENU_PROJECT_PRE_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ISSUED,
            Acl::PERMISSION_VIEW_MENU_INITIATOR,
        ]);

        // initiator
        $inRole = Role::findByName(Acl::ROLE_INITIATOR);
        $inRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROFILE,
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_VIEW_MENU_PROJECT_PRE_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ISSUED,
        ]);

        // LPJP
        $lpRole = Role::findByName(Acl::ROLE_LPJP);
        $lpRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROFILE,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR_TEAM,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR_EXPERT,
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ISSUED, 
            Acl::PERMISSION_MANAGE_FORMULATOR_EXPERT,
            Acl::PERMISSION_DO_FORMULATOR_TEAM,
        ]);

        // Formulator
        $foRole = Role::findByName(Acl::ROLE_FORMULATOR);
        $foRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROFILE,
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ISSUED, 
            Acl::PERMISSION_DO_KA_DRAFT,
            Acl::PERMISSION_DO_ANDAL_DRAFT,
            Acl::PERMISSION_DO_RKLRPL_ACTIVITIES,
            Acl::PERMISSION_DO_RKLRPL_ENV_SET,
            Acl::PERMISSION_DO_RKLRPL_IMPACT_MATRIX,
            Acl::PERMISSION_DO_RKLRPL_POTENCIAL_IMPACT,
            Acl::PERMISSION_DO_RKLRPL_HYPOTHETICAL_SIGNIFICANCE,
            Acl::PERMISSION_DO_RKLRPL_RKL,
            Acl::PERMISSION_DO_RKLRPL_RPL,
            Acl::PERMISSION_DO_UKLUPL_ACTIVITIES,
            Acl::PERMISSION_DO_UKLUPL_UKL,
            Acl::PERMISSION_DO_UKLUPL_UPL,
        ]); 

        // pustanling
        $puRole = Role::findByName(Acl::ROLE_PUSTANLING);
        $puRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_LPJP,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR_EXPERT,
            Acl::PERMISSION_MANAGE_LPJP,
            Acl::PERMISSION_MANAGE_FORMULATOR_EXPERT,
        ]);

        // LUK
        $luRole = Role::findByName(Acl::ROLE_LUK);
        $luRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_EXAMINER_TEAM,
            Acl::PERMISSION_VIEW_MENU_EXPERT,
            Acl::PERMISSION_DO_EXAMINER_TEAM,
            Acl::PERMISSION_MANAGE_EXPERT,
        ]);

        // examiner
        $luRole = Role::findByName(Acl::ROLE_EXAMINER);
        $luRole->givePermissionTo([ 
            Acl::PERMISSION_VIEW_MENU_PROFILE,
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_VIEW_MENU_PROJECT_POST_SUBMISSION,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ON_PROCESS,
            Acl::PERMISSION_VIEW_MENU_PROJECT_ISSUED,
            Acl::PERMISSION_DO_AMDAL_REVIEW,
            Acl::PERMISSION_DO_AMDAL_APPROVE,
            Acl::PERMISSION_DO_UKLUPL_REVIEW, 
            Acl::PERMISSION_DO_UKLUPL_APPROVE, 
        ]);

        // create user by roles
        foreach (Acl::roles() as $role) {
            $user = User::where([
                'name' => ucfirst($role),
                'email' => $role.'@amdalnet.dev'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => ucfirst($role),
                    'email' => $role.'@amdalnet.dev',
                    'password' => Hash::make('amdalnet')
                ]);
            }
            // set roles
            $role = Role::findByName($role);
            $user->syncRoles($role);
        }
    }
}