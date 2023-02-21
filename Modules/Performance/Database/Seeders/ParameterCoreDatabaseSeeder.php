<?php

namespace Modules\Performance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Performance\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class PerformanceCoreDatabaseSeeder extends Seeder
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
        $performancecores = [
            [
                'performance_core_code'              => "major",
                'performance_core_name'              => "Jurusan",
                'performance_core_value'             => "jurusan1,jurusan2,jurusan3,--bisa ditambah",
            ],
            [
                'performance_core_code'              => "recruitment_status",
                'performance_core_name'              => "Status Rekrutan",
                'performance_core_value'             => "status1,status2,--custom,status3",
            ],
            [
                'performance_core_code'              => "skills",
                'performance_core_name'              => "Status Rekrutan",
                'performance_core_value'             => "skill1,skill2,skill3,--bisa ditambah",
            ],
            [
                'performance_core_code'              => "certificate",
                'performance_core_name'              => "Status Rekrutan",
                'performance_core_value'             => "cert1,cert2,cert3,--bisa ditambah",
            ],
        ];

        foreach ($performancecores as $performancecore_data) {
            $performancecore = Core::firstOrCreate($performancecore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
