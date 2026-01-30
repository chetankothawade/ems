<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\Services\ExamService;

use App\Repositories\AttemptRepository;

class AdminExamController extends Controller
{
    public function __construct(
        private ExamService $service,
        private AttemptRepository $attemptRepo
    ) {}

    public function create($req, $res)
    {
        $exam = $this->service->create($req->getParsedBody());

        return $this->json($res, $exam);
    }

    public function update($req, $res, $args)
    {
        $this->service->update($args['id'], $req->getParsedBody());

        return $this->json($res, ['message' => 'updated']);
    }

   
    public function attemptHistory($req, $res, $args)
    {
        $attempts = $this->attemptRepo->findByExam($args['id']);

        return $this->json($res, $attempts);
    }
}
