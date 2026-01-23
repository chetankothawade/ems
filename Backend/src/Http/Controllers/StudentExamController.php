<?php

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
        $studentId = 'student-1'; // later from auth middleware

        $exams = $this->examRepo->all();

        $result = [];

        foreach ($exams as $exam) {
            $attempts = $this->attemptRepo->findByStudent($exam->id, $studentId);

            $result[] = [
                'id' => $exam->id,
                'title' => $exam->title,
                'max_attempts' => $exam->max_attempts,
                'cooldown_minutes' => $exam->cooldown_minutes,
                'attempts_used' => count($attempts),
                'attempts_remaining' => $exam->max_attempts - count($attempts)
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
        $attempt = $this->service->start($args['id'], 'student-1');

        return $this->json($res, $attempt);
    }

    public function submit($req, $res, $args)
    {
        $this->service->submit($args['id']);

        return $this->json($res, ['message' => 'submitted']);
    }
}
