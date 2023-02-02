<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\School\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class SchoolCoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        Schema::disableForeignKeyConstraints();

        $faker = \Faker\Factory::create();

        // Add the master administrator, user id of 1df
        $schoolcores = [
            [
                'school_core_code'              => "major",
                'school_core_name'              => "Jurusan",
                'school_core_value'             => "jurusan1,jurusan2,jurusan3,--bisa ditambah",
            ],
            [
                'school_core_code'              => "recruitment_status",
                'school_core_name'              => "Status Rekrutan",
                'school_core_value'             => "status1,status2,--custom,status3",
            ],
            [
                'school_core_code'              => "skills",
                'school_core_name'              => "Status Rekrutan",
                'school_core_value'             => "skill1,skill2,skill3,--bisa ditambah",
            ],
            [
                'school_core_code'              => "certificate",
                'school_core_name'              => "Status Rekrutan",
                'school_core_value'             => "cert1,cert2,cert3,--bisa ditambah",
            ],
        ];

        foreach ($schoolcores as $schoolcore_data) {
            $schoolcore = Core::firstOrCreate($schoolcore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
