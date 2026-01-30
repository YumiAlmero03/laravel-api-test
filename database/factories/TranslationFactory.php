<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Translation;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'locale_id' => Locale::factory(),
            'key' => 'app.' . $this->faker->unique()->word(),
            'value' => $this->faker->sentence(),
        ];
    }

}
