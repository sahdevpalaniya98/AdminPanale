<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsArry        = [
            'Inventory List','Inventory Add','Inventory Edit','Inventory Delete',
        ];
        if(!empty($permissionsArry)){
            foreach ($permissionsArry as $key => $pname) {
                $permission             = Permission::create([
                    'name'          => Str::slug($pname, "-"),
                    'display_name'  => $pname,
                    'created_by'    => 1,
                    'created_at'    => date("Y-m-d H:i:s"),
                    'updated_at'    => null
                ]);
            }
        }
    }
}
