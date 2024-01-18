<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Branch List',
            'Branch Add',
            'Branch Edit',
            'Branch Delete'
        ];
        if (!empty($permissions)) {
            foreach ($permissions as $key => $pname) {
                $permission = Permission::create([
                    'name' => Str::slug($pname, "-"),
                    'display_name' => $pname,
                    'created_by' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => null
                ]);
            }
        }
    }
}
