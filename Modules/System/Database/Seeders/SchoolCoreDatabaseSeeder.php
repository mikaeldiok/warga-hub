<?php

namespace Modules\System\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\System\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class SystemCoreDatabaseSeeder extends Seeder
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
        $systemcores = [
            [
                'system_core_code'              => "major",
                'system_core_name'              => "Jurusan",
                'system_core_value'             => "jurusan1,jurusan2,jurusan3,--bisa ditambah",
            ],
            [
                'system_core_code'              => "recruitment_status",
                'system_core_name'              => "Status Rekrutan",
                'system_core_value'             => "status1,status2,--custom,status3",
            ],
            [
                'system_core_code'              => "skills",
                'system_core_name'              => "Status Rekrutan",
                'system_core_value'             => "skill1,skill2,skill3,--bisa ditambah",
            ],
            [
                'system_core_code'              => "certificate",
                'system_core_name'              => "Status Rekrutan",
                'system_core_value'             => "cert1,cert2,cert3,--bisa ditambah",
            ],
        ];

        foreach ($systemcores as $systemcore_data) {
            $systemcore = Core::firstOrCreate($systemcore_data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
