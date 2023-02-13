<?php

namespace Modules\Data\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Data\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class DataCoreDatabaseSeeder extends Seeder
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
        $datacores = [
            [
                'data_core_code'              => "major",
                'data_core_name'              => "Jurusan",
                'data_core_value'             => "jurusan1,jurusan2,jurusan3,--bisa ditambah",
            ],
            [
                'data_core_code'              => "recruitment_status",
                'data_core_name'              => "Status Rekrutan",
                'data_core_value'             => "status1,status2,--custom,status3",
            ],
            [
                'data_core_code'              => "skills",
                'data_core_name'              => "Status Rekrutan",
                'data_core_value'             => "skill1,skill2,skill3,--bisa ditambah",
            ],
            [
                'data_core_code'              => "certificate",
                'data_core_name'              => "Status Rekrutan",
                'data_core_value'             => "cert1,cert2,cert3,--bisa ditambah",
            ],
        ];

        foreach ($datacores as $datacore_data) {
            $datacore = Core::firstOrCreate($datacore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
