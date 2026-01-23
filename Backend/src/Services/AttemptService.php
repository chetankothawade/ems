<?php

namespace App\Services;

use App\Repositories\AttemptRepository;
use App\Repositories\ExamRepository;
use App\Support\Clock\ClockInterface;
use App\Models\Attempt;
use Ramsey\Uuid\Uuid;
class AttemptService
{
    public function __construct(
        private AttemptRepository $attemptRepo,
        private ExamRepository $examRepo,
        private ClockInterface $clock
    ) {}

    public function start(string $examId, string $studentId): Attempt
    {
        $exam = $this->examRepo->find($examId);

        $attempts = $this->attemptRepo->findByStudent($examId, $studentId);

        if (count($attempts) >= $exam->max_attempts) {
            throw new \Exception('No attempts left.');
        }

        $last = end($attempts);

        if ($last && $last->completed_at) {
            $next = $last->completed_at->modify("+{$exam->cooldown_minutes} minutes");

            if ($this->clock->now() < $next) {
                throw new \Exception(
                    "Your next attempt will be available at {$next->format(DATE_ATOM)}"
                );
            }
        }

        return $this->attemptRepo->create([
            'id' => Uuid::uuid4()->toString(),
            'exam_id' => $examId,
            'student_id' => $studentId,
            'attempt_number' => count($attempts) + 1,
            'status' => 'in_progress',
            'started_at' => $this->clock->now()
        ]);
    }

    public function submit(string $attemptId): void
    {
        $attempt = $this->attemptRepo->find($attemptId);

        $attempt->status = 'completed';
        $attempt->completed_at = $this->clock->now();

        $this->attemptRepo->save($attempt);
    }
}
