<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dueDate = fake()->dateTimeBetween('-10 days', '+10 days');
        $status = $this->setstatus($dueDate);

        return [
            'description' => fake()->paragraph(2),
            'dueDate' => $dueDate,
            'status' => $status,
            'workflow' => 'New',
        ];
    }

    private function setstatus($dueDate)
    {
        $today = now()->settime(0, 0);
        $diff = date_diff($today, $dueDate, false);
        $isNegative = $diff->invert;

        $diffDay = $diff->d;

        if ($isNegative == 1) {
            $status = "Overdue";
        } else if ($diffDay >= 7) {
            $status = "Not Urgent";
        } else {
            $status = "Due soon";
        }
        return $status;
    }
}
