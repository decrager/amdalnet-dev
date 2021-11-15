<?php

namespace Database\Seeders;

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;
use App\Laravue\Acl;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (Acl::roles() as $role) {
            $user = User::where([
                'name' => ucfirst($role),
                'email' => $role.'@amdalnet.dev'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => ucfirst($role),
                    'email' => $role.'@amdalnet.dev',
                    'password' => Hash::make('amdalnet'),
                ]);
            

                $role = Role::findByName($role);
                $user->syncRoles($role);
            }
        }

        $this->call([
            UsersTableSeeder::class,
            BlogPostTableSeeder::class]);
    }
}
