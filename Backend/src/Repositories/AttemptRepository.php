<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attempt;
use Doctrine\ORM\EntityManagerInterface;

class AttemptRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Attempt $attempt): void
    {
        $this->em->persist($attempt);
        $this->em->flush();
    }

    public function find(string $id): Attempt
    {
        return $this->em->find(Attempt::class, $id);
    }

    public function findByStudent(string $examId, string $studentId): array
    {
        return $this->em->getRepository(Attempt::class)->findBy(
            ['exam_id' => $examId, 'student_id' => $studentId],
            ['attempt_number' => 'ASC']
        );
    }

    public function findByExam(string $examId): array
    {
        return $this->em->getRepository(Attempt::class)->findBy(['exam_id' => $examId]);
    }

    public function deleteByExam(string $examId): void
    {
        $this->em->createQuery(
            'DELETE FROM App\Models\Attempt a WHERE a.exam_id = :examId'
        )->setParameter('examId', $examId)->execute();
    }

    public function create(array $data): Attempt
    {
        $attempt = new Attempt();

        foreach ($data as $k => $v) {
            $attempt->$k = $v;
        }

        $this->save($attempt);

        return $attempt;
    }
}
