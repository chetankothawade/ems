<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Repositories\AttemptRepository;
use App\Models\Exam;
use App\Validation\Validator;

class ExamService
{
    public function __construct(
        private ExamRepository $examRepo,
        private AttemptRepository $attemptRepo
    ) {}

    public function create(array $data): Exam
    {
        $this->validateExamData($data);

        $exam = new Exam();
        $exam->title = $data['title'];
        $exam->max_attempts = (int)$data['max_attempts'];
        $exam->cooldown_minutes = (int)$data['cooldown_minutes'];

        $this->examRepo->save($exam);

        return $exam;
    }

    public function update(string $id, array $data): void
    {
        $this->validateExamData($data);

        $exam = $this->examRepo->find($id);

        if (isset($data['title'])) {
            $exam->title = $data['title'];
        }
        if (isset($data['max_attempts'])) {
            $exam->max_attempts = (int)$data['max_attempts'];
        }
        if (isset($data['cooldown_minutes'])) {
            $exam->cooldown_minutes = (int)$data['cooldown_minutes'];
        }

        $this->examRepo->save($exam);
        $this->attemptRepo->deleteByExam($id);
    }

    private function validateExamData(array $data): void
    {
        $validator = new Validator($data);

        $validator
            ->required('title', 'Exam title is required')
            ->string('title', 'Title must be a string')
            ->minLength('title', 3, 'Title must be at least 3 characters')
            ->maxLength('title', 255, 'Title cannot exceed 255 characters')
            ->required('max_attempts', 'Maximum attempts is required')
            ->integer('max_attempts', 'Maximum attempts must be a number')
            ->min('max_attempts', 1, 'Maximum attempts must be at least 1')
            ->required('cooldown_minutes', 'Cooldown minutes is required')
            ->integer('cooldown_minutes', 'Cooldown minutes must be a number')
            ->min('cooldown_minutes', 0, 'Cooldown minutes must be 0 or greater')
            ->validate();
    }
}
