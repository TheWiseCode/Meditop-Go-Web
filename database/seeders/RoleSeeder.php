<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'Administrador']);
        $doc = Role::create(['name' => 'Doctor']);
        Permission::create(['name' => 'verificar doctores'])->syncRoles($admin);
        Permission::create(['name' => 'ver usuarios'])->syncRoles($admin);
        Permission::create(['name' => 'administrar horarios'])->syncRoles($doc);
        Permission::create(['name' => 'administrar reservaciones'])->syncRoles($doc);
        Permission::create(['name' => 'enviar verificacion']);
    }
}
