<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Database\Factories\TaskFactory;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
        User::factory()
        ->count(10)
        ->sequence(fn($sequence)=> [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
                'password' => Hash::make('password')
        ])
        ->has(
             factory: Task::factory()
            ->count(5)
            ->sequence(fn($sequence)=> [
                'name'=> 'Task '.$sequence->index + 1
            ])
            ->state(
                function (array $attributes, User $user){
                    return ['assignee_id'=> $user->id];
                }
            )
        )
        ->create();
    }
}
