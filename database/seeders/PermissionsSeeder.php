<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Customer List',
            'Customer Add',
            'Customer Edit',
            'Customer Delete',
            'Buyer List',
            'Buyer Add',
            'Buyer Edit',
            'Buyer Delete',
            'Expense List',
            'Expense Add',
            'Expense Edit',
            'Expense Delete',
            'Brand List',
            'Brand Add',
            'Brand Edit',
            'Brand Delete',
            'Phone Model List',
            'Phone Model Add',
            'Phone Model Edit',
            'Phone Model Delete',
            'Phone Series List',
            'Phone Series Add',
            'Phone Series Edit',
            'Phone Series Delete',
            'Phone Damage List',
            'Phone Damage Add',
            'Phone Damage Edit',
            'Phone Damage Delete',
            'Pay Worker List',
            'Pay Worker Add',
            'Pay Worker Edit',
            'Pay Worker Delete',
            'Order List',
            'Order Add',
            'Order Edit',
            'Order Delete',
            'Order View',
            'User History',
            'Buyer History'
        ];
        if(!empty($permissions)){
            foreach ($permissions as $key => $pname) {
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
