<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $baseStudentId = 2201100000;

        for ($i = 1; $i <= 50; $i++) {
            $studentId = $baseStudentId + $i;
            
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'course' => $this->getRandomCourse(),
                'year' => $this->getRandomYear(),
                'section' => $this->getRandomSection(),
            ]);
        }
    }

    private function getRandomCourse(): string
    {
        $courses = [
            'BSIT', 'BSCS', 'BSIS', 'BSCpE', 'BSCE',
            'BSEE', 'BSME', 'BSA', 'BSBA', 'BSN'
        ];
        return $courses[array_rand($courses)];
    }

    private function getRandomYear(): string
    {
        return (string) rand(1, 4);
    }

    private function getRandomSection(): string
    {
        return chr(rand(65, 70)); // Returns A to F
    }
}