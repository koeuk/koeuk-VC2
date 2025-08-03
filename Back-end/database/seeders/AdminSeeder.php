<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>bcrypt('password'),
            'profile' => 'user.avif',
            'role' => 'admin',
        ]);

        $writer = User::create([
            'name'=>'User',
            'email'=>'user@gmail.com',
            'password'=>bcrypt('password'),
            'role' => 'customer',
        ]);
        $fixer = User::create([
            'name'=>'fixer',
            'email'=>'fixer@gmail.com',
            'password'=>bcrypt('password'),
            'role' => 'fixer',
        ]);
        


        $admin_role = Role::create(['name' => 'admin']);
        $writer_role = Role::create(['name' => 'customer']);
        $fixer_role  = Role::create(['name' => 'fixer']);

        $permission = Permission::create(['name' => 'Discount access']);
        $permission = Permission::create(['name' => 'Discount edit']);
        $permission = Permission::create(['name' => 'Discount create']);
        $permission = Permission::create(['name' => 'Discount delete']);

        $permission = Permission::create(['name' => 'Category access']);
        $permission = Permission::create(['name' => 'Category edit']);
        $permission = Permission::create(['name' => 'Category create']);
        $permission = Permission::create(['name' => 'Category delete']);

        $permission = Permission::create(['name' => 'Service access']);
        $permission = Permission::create(['name' => 'Service edit']);
        $permission = Permission::create(['name' => 'Service create']);
        $permission = Permission::create(['name' => 'Service delete']);
        $permission = Permission::create(['name' => 'Service getCategories']);

        $permission = Permission::create(['name' => 'Role access']);
        $permission = Permission::create(['name' => 'Role edit']);
        $permission = Permission::create(['name' => 'Role create']);
        $permission = Permission::create(['name' => 'Role delete']);

        $permission = Permission::create(['name' => 'User access']);
        $permission = Permission::create(['name' => 'User edit']);
        $permission = Permission::create(['name' => 'User create']);
        $permission = Permission::create(['name' => 'User delete']);

        $permission = Permission::create(['name' => 'Permission access']);
        $permission = Permission::create(['name' => 'Permission edit']);
        $permission = Permission::create(['name' => 'Permission create']);
        $permission = Permission::create(['name' => 'Permission delete']);

        $permission = Permission::create(['name' => 'Mail access']);
        $permission = Permission::create(['name' => 'Mail edit']);
        $permission = Permission::create(['name' => 'view_users']);

        $permission = Permission::create(['name' => 'Request access']);
        $permission = Permission::create(['name' => 'Request delete']);

        $permission = Permission::create(['name' => 'Progress access']);
        $permission = Permission::create(['name' => 'Progress delete']);

        $permission = Permission::create(['name' => 'Done access']);
        $permission = Permission::create(['name' => 'Feedback delete']);

        $permission = Permission::create(['name' => 'Payment access']);
        $permission = Permission::create(['name' => 'Payment create']);
        $permission = Permission::create(['name' => 'Payment edit']);


        $admin->assignRole($admin_role);
        $writer->assignRole($writer_role);
        $fixer->assignRole($fixer_role );

        $admin_role->givePermissionTo(Permission::all());
    }
}
