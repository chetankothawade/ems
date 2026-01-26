<?php

namespace Tests\Integration;

use App\Models\Exam;
use App\Models\Attempt;

class AdminExamApiTest extends ApiTestCase
{
    public function test_create_exam(): void
    {
        $examData = [
            'id' => 'exam-1',
            'title' => 'Test Exam',
            'max_attempts' => 3,
            'cooldown_minutes' => 60
        ];

        $response = $this->makeRequest(
            'POST',
            '/admin/exams',
            ['Content-Type' => 'application/json'],
            json_encode($examData)
        );

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertEquals('exam-1', $data['id']);
        $this->assertEquals('Test Exam', $data['title']);
        $this->assertEquals(3, $data['max_attempts']);
        $this->assertEquals(60, $data['cooldown_minutes']);
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }

    public function test_update_exam(): void
    {
        // Create exam first
        $exam = new Exam();
        $exam->id = 'exam-1';
        $exam->title = 'Original Title';
        $exam->max_attempts = 1;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);
        $this->em->flush();

        // Update exam
        $updateData = [
            'title' => 'Updated Title',
            'max_attempts' => 5,
            'cooldown_minutes' => 120
        ];

        $response = $this->makeRequest(
            'PUT',
            '/admin/exams/exam-1',
            ['Content-Type' => 'application/json'],
            json_encode($updateData)
        );

        $this->assertJsonResponse($response, 200, ['message' => 'updated']);

        // Verify exam was updated
        $this->em->refresh($exam);
        $this->assertEquals('Updated Title', $exam->title);
        $this->assertEquals(5, $exam->max_attempts);
        $this->assertEquals(120, $exam->cooldown_minutes);
    }

    public function test_get_attempt_history(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = 'exam-1';
        $exam->title = 'Test Exam';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);

        // Create attempts
        $attempt1 = new Attempt();
        $attempt1->id = 'attempt-1';
        $attempt1->exam_id = $exam->id;
        $attempt1->student_id = 'student-1';
        $attempt1->attempt_number = 1;
        $attempt1->status = 'completed';
        $attempt1->started_at = new \DateTimeImmutable('2024-01-01 10:00:00');
        $attempt1->completed_at = new \DateTimeImmutable('2024-01-01 10:30:00');

        $attempt2 = new Attempt();
        $attempt2->id = 'attempt-2';
        $attempt2->exam_id = $exam->id;
        $attempt2->student_id = 'student-2';
        $attempt2->attempt_number = 1;
        $attempt2->status = 'in_progress';
        $attempt2->started_at = new \DateTimeImmutable('2024-01-01 11:00:00');
        $attempt2->completed_at = null;

        $this->em->persist($attempt1);
        $this->em->persist($attempt2);
        $this->em->flush();

        $response = $this->makeRequest('GET', '/admin/exams/exam-1/attempts');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertCount(2, $data);

        // Check that attempts are returned with correct data
        $attemptIds = array_column($data, 'id');
        $this->assertContains('attempt-1', $attemptIds);
        $this->assertContains('attempt-2', $attemptIds);
    }

    public function test_get_attempt_history_for_nonexistent_exam(): void
    {
        $response = $this->makeRequest('GET', '/admin/exams/nonexistent/attempts');

        $this->assertJsonResponse($response, 200, []);
    }
}