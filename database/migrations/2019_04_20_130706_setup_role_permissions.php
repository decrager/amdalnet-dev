<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;
use App\Laravue\Acl;

class SetupRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create roles if not exists
        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role);
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(Acl::ROLE_EDITOR);
        $userRole = Role::findByName(Acl::ROLE_USER);
        $visitorRole = Role::findByName(Acl::ROLE_VISITOR);

        // create permission if not exists
        foreach (Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }


        // Setup basic permission
        $adminRole->givePermissionTo(Acl::permissions());
        $managerRole->givePermissionTo(Acl::permissions([Acl::PERMISSION_MANAGE_PERMISSION]));
        $editorRole->givePermissionTo(Acl::menuPermissions());
        $editorRole->givePermissionTo(Acl::PERMISSION_ARTICLE_MANAGE);
        $userRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
        ]);
        $visitorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
        ]);

        //// setup apps permissions
        // initiator
        $initiatorRole = Role::findByName(Acl::ROLE_INITIATOR);
        $initiatorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_MANAGE_PROJECT,
            Acl::PERMISSION_DO_ASSEMBLE_FORMULATOR,
            Acl::PERMISSION_DO_ANNOUNCEMENT,
        ]);

        $formulatorRole = Role::findByName(Acl::ROLE_FORMULATOR);
        $formulatorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_WORKSPACE,
            Acl::PERMISSION_DO_QUALIFICATION,
            Acl::PERMISSION_DO_RKLRPL,
            Acl::PERMISSION_DO_ANDAL,
        ]);

        // lpjp
        $institutionRole = Role::findByName(Acl::ROLE_LPJP); 
        $institutionRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_FORMULATOR,
            Acl::PERMISSION_VIEW_MENU_PROJECT,
            Acl::PERMISSION_MANAGE_FORMULATOR,
            Acl::PERMISSION_DO_ASSEMBLE_FORMULATOR,
        ]);

        // pustanling
        $adminStandardRole = Role::findByName(Acl::ROLE_PUSTANLING); 
        $adminStandardRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_LPJP,
            Acl::PERMISSION_VIEW_MENU_FORMULATOR,
            Acl::PERMISSION_VIEW_MENU_PARAMS,
            Acl::PERMISSION_VIEW_MENU_SOP,
            // Acl::PERMISSION_VIEW_MENU_TECHNICAL_REGULATIONS,
            Acl::PERMISSION_VIEW_MENU_CLUSTER,
            Acl::PERMISSION_MANAGE_LPJP,
            Acl::PERMISSION_MANAGE_FORMULATOR,
            Acl::PERMISSION_MANAGE_PARAMS,
            Acl::PERMISSION_MANAGE_SOP,
            Acl::PERMISSION_MANAGE_TECHNICAL_REGULATIONS,
            Acl::PERMISSION_MANAGE_CLUSTER,
        ]);

        $examinerRole = Role::findByName(Acl::ROLE_EXAMINER); 
        $examinerRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_WORKSPACE,
            Acl::PERMISSION_DO_UKLUPL,
            Acl::PERMISSION_DO_AMDAL,

            Acl::PERMISSION_DO_SK_PKPLH,
            Acl::PERMISSION_DO_SK_SKKL,
            Acl::PERMISSION_DO_PUSH,
        ]);

        $examinerInstitutionRole = Role::findByName(Acl::ROLE_LUK); 
        $examinerInstitutionRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_EXAMINER,
            Acl::PERMISSION_VIEW_MENU_EXPERT,
            Acl::PERMISSION_MANAGE_EXAMINER,
            Acl::PERMISSION_MANAGE_EXPERT,
        ]);

        $adminSystemRole = Role::findByName(Acl::ROLE_ADMIN_SYSTEM); 
        $adminSystemRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_USER,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
            Acl::PERMISSION_VIEW_MENU_ROLE,
            Acl::PERMISSION_MANAGE_USER,
            Acl::PERMISSION_MANAGE_ROLE,
            Acl::PERMISSION_MANAGE_PERMISSION,
        ]);

        $adminCentralRole = Role::findByName(Acl::ROLE_ADMIN_CENTRAL); 
        $adminCentralRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_EXAMINER,
            Acl::PERMISSION_VIEW_MENU_LUK,
            Acl::PERMISSION_VIEW_MENU_EXPERT,
            Acl::PERMISSION_MANAGE_EXAMINER,
            Acl::PERMISSION_MANAGE_LUK,
            Acl::PERMISSION_MANAGE_EXPERT,
        ]);

        $adminRegionalRole = Role::findByName(Acl::ROLE_ADMIN_REGIONAL); 
        $adminRegionalRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_EXAMINER,
            Acl::PERMISSION_VIEW_MENU_LUK,
            Acl::PERMISSION_VIEW_MENU_EXPERT,
            Acl::PERMISSION_MANAGE_EXAMINER,
            Acl::PERMISSION_MANAGE_LUK,
            Acl::PERMISSION_MANAGE_EXPERT,
        ]);

        foreach (Acl::roles() as $role) {
            /** @var \App\User[] $users */
            $users = \App\Laravue\Models\User::where('role', $role)->get();
            $role = Role::findByName($role);
            foreach ($users as $user) {
                $user->syncRoles($role);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('editor');
            });
        }

        /** @var \App\User[] $users */
        $users = \App\Laravue\Models\User::all();
        foreach ($users as $user) {
            $roles = array_reverse(Acl::roles());
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $user->role = $role;
                    $user->save();
                }
            }
        }
    }
}
