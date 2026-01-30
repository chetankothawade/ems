<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AttemptService;
use App\Repositories\ExamRepository;
use App\Repositories\AttemptRepository;

class StudentExamController extends Controller
{
    public function __construct(
        private AttemptService $service,
        private ExamRepository $examRepo,
        private AttemptRepository $attemptRepo
    ) {}


    public function dashboard($req, $res)
    {
        $studentId = 'student-1'; // later from auth

        $exams = $this->examRepo->all();

        $result = [];

        foreach ($exams as $exam) {

            $attempts = $this->attemptRepo->findByStudent($exam->id, $studentId);

            $used = count($attempts);
            $remaining = $exam->max_attempts - $used;

            $inProgress = null;

            foreach ($attempts as $a) {
                if ($a->status === 'in_progress') {
                    $inProgress = $a;
                    break;
                }
            }

            $canStart = $remaining > 0 && !$inProgress;

            $result[] = [
                'id' => $exam->id,
                'title' => $exam->title,
                'max_attempts' => $exam->max_attempts,
                'cooldown_minutes' => $exam->cooldown_minutes,

                // counts
                'attempts_used' => $used,
                'attempts_remaining' => $remaining,

                // UI helpers
                'can_start' => $canStart,
                'in_progress_attempt_id' => $inProgress?->id,
                'message' => $remaining <= 0 ? 'No attempts remaining' : null,

                // attempts list (VERY IMPORTANT for AttemptTable)
                'attempts' => array_map(function ($a) {
                    return [
                        'id' => $a->id,
                        'attempt_number' => $a->attempt_number,
                        'status' => $a->status,
                        'started_at' => $a->started_at?->format(DATE_ATOM),
                        'completed_at' => $a->completed_at?->format(DATE_ATOM),
                    ];
                }, $attempts)
            ];
        }

        return $this->json($res, $result);
    }

    public function myAttempts($req, $res, $args)
    {
        $studentId = 'student-1';

        $attempts = $this->attemptRepo->findByStudent($args['id'], $studentId);

        return $this->json($res, $attempts);
    }

    public function start($req, $res, $args)
    {
        try {
            $attempt = $this->service->start($args['id'], 'student-1');

            return $this->json($res, $attempt);
        } catch (\Exception $e) {
            // Determine status code based on message
            $status = 400;
            if (str_contains($e->getMessage(), 'next attempt will be available')) {
                $status = 429; // Too Many Requests
            } elseif (str_contains($e->getMessage(), 'No attempts left')) {
                $status = 403; // Forbidden
            }

            return $this->json($res, ['message' => $e->getMessage()], $status);
        }
    }

    public function submit($req, $res, $args)
    {
        $this->service->submit($args['id']);

        return $this->json($res, ['message' => 'submitted']);
    }
}
