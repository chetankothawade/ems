<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ExamService;
use App\Repositories\AttemptRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminExamController extends Controller
{
    public function __construct(
        private ExamService $service,
        private AttemptRepository $attemptRepo
    ) {}

    public function create(ServerRequestInterface $req, ResponseInterface $res): ResponseInterface
    {
        $exam = $this->service->create($req->getParsedBody());

        return $this->json($res, $exam);
    }

    public function update(ServerRequestInterface $req, ResponseInterface $res, array $args): ResponseInterface
    {
        $this->service->update($args['id'], $req->getParsedBody());

        return $this->json($res, ['message' => 'updated']);
    }

   
    public function attemptHistory(ServerRequestInterface $req, ResponseInterface $res, array $args): ResponseInterface
    {
        $attempts = $this->attemptRepo->findByExam($args['id']);

        return $this->json($res, $attempts);
    }
}
