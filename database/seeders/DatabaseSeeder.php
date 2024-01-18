<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(PhoneGradeSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(ExpenseSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(VariantSeeder::class);
        $this->call(BranchSeeder::class);
    }
}
