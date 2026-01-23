<?php

namespace Tests\Integration;

use App\Services\AttemptService;
use App\Repositories\AttemptRepository;
use App\Repositories\ExamRepository;
use App\Support\Clock\SystemClock;
use App\Models\Exam;

class AttemptServiceIntegrationTest extends DatabaseTestCase
{
    private AttemptService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $attemptRepo = new AttemptRepository($this->em);
        $examRepo = new ExamRepository($this->em);

        $this->service = new AttemptService(
            $attemptRepo,
            $examRepo,
            new SystemClock()
        );
    }

    public function test_full_attempt_lifecycle(): void
    {
        // Create real exam
        $exam = new Exam();
        $exam->id = 'exam-1';
        $exam->title = 'Integration Test';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);
        $this->em->flush();

        // Start attempt
        $attempt = $this->service->start('exam-1', 'student-1');

        $this->assertEquals('in_progress', $attempt->status);

        // Submit
        $this->service->submit($attempt->id);

        $this->em->refresh($attempt);

        $this->assertEquals('completed', $attempt->status);
        $this->assertNotNull($attempt->completed_at);
    }
}
