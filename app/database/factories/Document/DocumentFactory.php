<?php

namespace Database\Factories\Document;


use App\Enums\Document\Priority;
use App\Enums\Document\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            "title" => $this->faker->text(50),
            "priority" => Arr::random(array_column(Priority::cases(), 'value')),
            "status" => Status::OPEN,
            'created_at' => Carbon::now()->subMinutes(rand(1, 60))
        ];
    }
}
