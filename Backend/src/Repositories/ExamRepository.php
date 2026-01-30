<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Exam;
use Doctrine\ORM\EntityManagerInterface;

class ExamRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Exam $exam): void
    {
        $this->em->persist($exam);
        $this->em->flush();
    }

    public function find(string $id): Exam
    {
        return $this->em->find(Exam::class, $id);
    }

    public function all(): array
    {
        return $this->em->getRepository(Exam::class)->findAll();
    }
}
