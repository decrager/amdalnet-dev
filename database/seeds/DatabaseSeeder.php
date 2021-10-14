<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $admin = User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@amdalnet.dev',
        //     'password' => Hash::make('amdalnet'),
        // ]);
        // $manager = User::create([
        //     'name' => '',
        //     'email' => 'manager@amdalnet.dev',
        //     'password' => Hash::make('amdalnet'),
        // ]);
        // $editor = User::create([
        //     'name' => 'Editor',
        //     'email' => 'editor@amdalnet.dev',
        //     'password' => Hash::make('amdalnet'),
        // ]);
        // $user = User::create([
        //     'name' => 'User',
        //     'email' => 'user@amdalnet.dev',
        //     'password' => Hash::make('amdalnet'),
        // ]);
        // $visitor = User::create([
        //     'name' => 'Visitor',
        //     'email' => 'visitor@amdalnet.dev',
        //     'password' => Hash::make('amdalnet'),
        // ]);

        // $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        // $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        // $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        // $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        // $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);
        // $admin->syncRoles($adminRole);
        // $manager->syncRoles($managerRole);
        // $editor->syncRoles($editorRole);
        // $user->syncRoles($userRole);
        // $visitor->syncRoles($visitorRole);

        foreach (Acl::roles() as $role) {
            $user = User::create([
                'name' => ucfirst($role),
                'email' => $role.'@amdalnet.dev',
                'password' => Hash::make('amdalnet'),
            ]);

            $role = Role::findByName($role);
            $user->syncRoles($role);
        }

        $this->call([
            UsersTableSeeder::class,
            BlogPostTableSeeder::class]);
    }
}
