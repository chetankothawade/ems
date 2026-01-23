<?php

namespace App\Database\Seeder;

use Doctrine\ORM\EntityManagerInterface;
use App\Models\Student;
use App\Models\Exam;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder
{
    public function __construct(private EntityManagerInterface $em) {}

    public function run(): void
    {
        $this->seedStudents();
        $this->seedExams();

        $this->em->flush();
    }

    private function seedStudents(): void
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $students = [
            ['student-1', 'Anna Parker'],
            ['student-2', 'John Doe'],
            ['student-3', 'Adam Smith'],
        ];

        foreach ($students as [$id, $name]) {
            $s = new Student();

            $s->id = $id;
            $s->name = $name;
            $s->created_at = $now;

            $this->em->persist($s);
        }
    }

    private function seedExams(): void
    {
        $exam = new Exam();

        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Sample Math Exam';
        $exam->max_attempts = 3;
        $exam->cooldown_minutes = 5;

        $this->em->persist($exam);
    }
}
