<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();
        if(!$user) $user = User::factory()->create();

        return [
            "name" => $this->faker->name() . " Team",
            "owner_id" => $user->id,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Team $team) {
            if(User::all()->count() < 10) User::factory()->count(rand(10, 20))->create();
            $users = User::all()->where("id", "!=", $team->owner_id)->random(rand(4, 8));

            $team->associates()->attach($users->pluck('id'));
            $team->save();
        });
    }
}