<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use App\Repositories\AttemptRepository;
use App\Models\Exam;

class ExamService
{
    public function __construct(
        private ExamRepository $examRepo,
        private AttemptRepository $attemptRepo
    ) {}

    public function create(array $data): Exam
    {
        $exam = new Exam();

        foreach ($data as $k => $v) {
            $exam->$k = $v;
        }

        $this->examRepo->save($exam);

        return $exam;
    }

    //reset attempts on update
    public function update(string $id, array $data): void
    {
        $exam = $this->examRepo->find($id);

        foreach ($data as $k => $v) {
            $exam->$k = $v;
        }

        $this->examRepo->save($exam);

        $this->attemptRepo->deleteByExam($id);
    }
}
