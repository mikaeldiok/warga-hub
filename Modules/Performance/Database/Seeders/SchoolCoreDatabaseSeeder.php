<?php

namespace Modules\Mkstarter\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Mkstarter\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class MkstarterCoreDatabaseSeeder extends Seeder
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
        $mkstartercores = [
            [
                'mkstarter_core_code'              => "major",
                'mkstarter_core_name'              => "Jurusan",
                'mkstarter_core_value'             => "jurusan1,jurusan2,jurusan3,--bisa ditambah",
            ],
            [
                'mkstarter_core_code'              => "recruitment_status",
                'mkstarter_core_name'              => "Status Rekrutan",
                'mkstarter_core_value'             => "status1,status2,--custom,status3",
            ],
            [
                'mkstarter_core_code'              => "skills",
                'mkstarter_core_name'              => "Status Rekrutan",
                'mkstarter_core_value'             => "skill1,skill2,skill3,--bisa ditambah",
            ],
            [
                'mkstarter_core_code'              => "certificate",
                'mkstarter_core_name'              => "Status Rekrutan",
                'mkstarter_core_value'             => "cert1,cert2,cert3,--bisa ditambah",
            ],
        ];

        foreach ($mkstartercores as $mkstartercore_data) {
            $mkstartercore = Core::firstOrCreate($mkstartercore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
