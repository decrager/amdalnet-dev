<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // clean up first

        $userList = [
            "Adriana C Ocampo Uria",
            "Albert Einstein",
            "Anna K Behrensmeyer",
            "Blaise Pascal",
            "Caroline Herschel",
            "Cecilia Payne-Gaposchkin",
            "Chien-Shiung Wu",
            "Dorothy Hodgkin",
            "Edmond Halley",
            "Edwin Powell Hubble",
            "Elizabeth Blackburn",
            "Enrico Fermi",
            "Erwin Schroedinger",
            "Flossie Wong-Staal",
            "Frieda Robscheit-Robbins",
            "Geraldine Seydoux",
            "Gertrude B Elion",
            "Ingrid Daubechies",
            "Jacqueline K Barton",
            "Jane Goodall",
            "Jocelyn Bell Burnell",
            "Johannes Kepler",
            "Lene Vestergaard Hau",
            "Lise Meitner",
            "Lord Kelvin",
            "Maria Mitchell",
            "Marie Curie",
            "Max Born",
            "Max Planck",
            "Melissa Franklin",
            "Michael Faraday",
            "Mildred S Dresselhaus",
            "Nicolaus Copernicus",
            "Niels Bohr",
            "Patricia S Goldman-Rakic",
            "Patty Jo Watson",
            "Polly Matzinger",
            "Richard Phillips Feynman",
            "Rita Levi-Montalcini",
            "Rosalind Franklin",
            "Ruzena Bajcsy",
            "Sarah Boysen",
            "Shannon W Lucid",
            "Shirley Ann Jackson",
            "Sir Ernest Rutherford",
            "Sir Isaac Newton",
            "Stephen Hawking",
            "Werner Karl Heisenberg",
            "Wilhelm Conrad Roentgen",
            "Wolfgang Ernst Pauli",
        ];

        foreach ($userList as $fullName) {
            $name = str_replace(' ', '.', $fullName);
            $roleName = \App\Laravue\Faker::randomInArray([
                Acl::ROLE_INITIATOR,
                Acl::ROLE_FORMULATOR,
                Acl::ROLE_LPJP,
                Acl::ROLE_PUSTANLING,
                Acl::ROLE_EXAMINER,
                Acl::ROLE_LUK,
                Acl::ROLE_ADMIN_SYSTEM,
                Acl::ROLE_ADMIN_CENTRAL,
                Acl::ROLE_ADMIN_REGIONAL,
            ]);
            $user = User::where([
                'name' => $fullName,
                'email' => strtolower($name) . '@amdalnet.dev'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $fullName,
                    'email' => strtolower($name) . '@amdalnet.dev',
                    'password' => \Illuminate\Support\Facades\Hash::make('amdalnet'),
                    // 'activate' => '1',
                ]);

                $role = Role::findByName($roleName);
                $user->syncRoles($role);
            }
        }
    }
}
