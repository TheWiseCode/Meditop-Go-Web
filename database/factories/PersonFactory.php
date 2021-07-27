<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'ci' => $this->faker->numerify('#######'),
            'cellphone' => $this->faker->numerify('########'),
            'birthday' => $this->faker->date,
            'sex' => $this->faker->randomElement(['M', 'F', 'O']),
            'email' => $this->faker->unique()->safeEmail()
        ];
    }
}
