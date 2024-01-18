<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhoneGrade;

class PhoneGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gradeArry = [
            'Grade A','Grade B','Grade C','Grade D', 'Open box', 'Sealed Box',
        ];
        if(!empty($gradeArry)){
            foreach ($gradeArry as $key => $pname) {
                PhoneGrade::updateOrCreate([
                    'grade_name'    => $pname,
                    'grade_value'   => 0,
                    'created_at'    => date("Y-m-d H:i:s"),
                    'updated_at'    => null
                ]);
            }
        }
    }
}
