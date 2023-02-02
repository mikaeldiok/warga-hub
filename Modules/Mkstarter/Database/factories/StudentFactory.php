<?php
namespace Modules\Mkstarter\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminte\Support\Str;
use Illuminate\Support\Arr; 
use Carbon\Carbon;

use Modules\Mkstarter\Entities\Core;

class MkdumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Mkstarter\Entities\Mkdum::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $raw_majors = Core::getRawData('major');
        $majors = [];
        foreach($raw_majors as $key => $value){
            $majors += [$value => $value];
        }

        $skills_raw = Core::getRawData('skills');
        $skills = [];
        foreach($skills_raw as $value){
            $skills += [$value => $value];
        }

        $certificate_raw= Core::getRawData('certificate');
        $certificate = [];
        foreach($certificate_raw as $value){
            $certificate += [$value => $value];
        }

        return [
            'name'          => $this->faker->name,
            'mkdum_id'    => mt_rand(1111111,9999999),
            'gender'        => Arr::random(["Laki-laki","Perempuan"]),
            'birth_place'   => $this->faker->country,
            'birth_date'    => $this->faker->dateTimeBetween(),
            'year_class'    => mt_rand( (Carbon::now()->year - 10), Carbon::now()->year ),
            'major'         => Arr::random($majors),
            'height'        => rand(150,190),
            'weight'        => rand(50,98),
            'religion'      => Arr::random([
                                'Islam'     => 'Islam',
                                'Kristen'   => 'Kristen',
                                'Katolik'   => 'Katolik',
                                'Hindu'     => 'Hindu',
                                'Budha'     => 'Budha',
                                'Konghucu'  => 'Konghucu',
                                ]),
            'skills'        => implode("," ,array_rand($skills, rand(2,count($skills)))),
            'certificate'   => implode("," ,array_rand($certificate, rand(2,count($certificate))) ),
            'available'     => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ];
    }
}

