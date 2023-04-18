<?php

namespace Database\Factories;

use App\Models\Commentaire;
use App\Models\Forms;
use App\Models\Jeu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentaireFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Commentaire::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $user_ids = User::all()->pluck('id');
        $survey_ids = Forms::all()->pluck('id');
        return [
            'commentaire' => $this->faker->text(200),
            'date_com' => $this->faker->dateTimeInInterval(
                $startDate = '-6 months',
                $interval = '+ 180 days',
                $timezone = date_default_timezone_get()
            ),
            'user_id' => $this->faker->randomElement($user_ids),
            'survey_id' => $this->faker->randomElement($survey_ids),
            'note' => $this->faker->numberBetween(1, 5)
        ];
    }
}
