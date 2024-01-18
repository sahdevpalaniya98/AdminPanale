<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Expense::create([
            'expense_name'    => 'Shipping',
            'note'   => 'Shipping',
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => null
        ]);
    }
}
