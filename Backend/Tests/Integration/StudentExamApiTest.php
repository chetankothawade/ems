<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\Exam;
use App\Models\Attempt;
use Ramsey\Uuid\Uuid;

class StudentExamApiTest extends ApiTestCase
{
    public function test_dashboard_returns_exam_list_with_attempt_counts(): void
    {
        // Create exams
        $exam1 = new Exam();
        $exam1->id = Uuid::uuid4()->toString();
        $exam1->title = 'Math Exam';
        $exam1->max_attempts = 3;
        $exam1->cooldown_minutes = 60;

        $exam2 = new Exam();
        $exam2->id = Uuid::uuid4()->toString();
        $exam2->title = 'Science Exam';
        $exam2->max_attempts = 2;
        $exam2->cooldown_minutes = 30;

        $this->em->persist($exam1);
        $this->em->persist($exam2);

        // Create attempts for student-1 on exam-1
        $attempt1 = new Attempt();
        $attempt1->id = Uuid::uuid4()->toString();
        $attempt1->exam_id = $exam1->id;
        $attempt1->student_id = 'student-1';
        $attempt1->attempt_number = 1;
        $attempt1->status = 'completed';
        $attempt1->started_at = new \DateTimeImmutable('2024-01-01 10:00:00');
        $attempt1->completed_at = new \DateTimeImmutable('2024-01-01 10:30:00');

        $this->em->persist($attempt1);
        $this->em->flush();

        $response = $this->makeRequest('GET', '/student/exams');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertCount(2, $data);

        // Find exam-1 in response
        $exam1Data = null;
        $exam2Data = null;
        foreach ($data as $exam) {
            if ($exam['id'] === $exam1->id) {
                $exam1Data = $exam;
            } elseif ($exam['id'] === $exam2->id) {
                $exam2Data = $exam;
            }
        }

        $this->assertNotNull($exam1Data);
        $this->assertEquals('Math Exam', $exam1Data['title']);
        $this->assertEquals(3, $exam1Data['max_attempts']);
        $this->assertEquals(60, $exam1Data['cooldown_minutes']);
        $this->assertEquals(1, $exam1Data['attempts_used']);
        $this->assertEquals(2, $exam1Data['attempts_remaining']);

        $this->assertNotNull($exam2Data);
        $this->assertEquals('Science Exam', $exam2Data['title']);
        $this->assertEquals(2, $exam2Data['max_attempts']);
        $this->assertEquals(30, $exam2Data['cooldown_minutes']);
        $this->assertEquals(0, $exam2Data['attempts_used']);
        $this->assertEquals(2, $exam2Data['attempts_remaining']);
    }

    public function test_my_attempts_returns_student_attempts_for_exam(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Test Exam';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);

        // Create attempts for student-1
        $attempt1 = new Attempt();
        $attempt1->id = Uuid::uuid4()->toString();
        $attempt1->exam_id = $exam->id;
        $attempt1->student_id = 'student-1';
        $attempt1->attempt_number = 1;
        $attempt1->status = 'completed';
        $attempt1->started_at = new \DateTimeImmutable('2024-01-01 10:00:00');
        $attempt1->completed_at = new \DateTimeImmutable('2024-01-01 10:30:00');

        // Create attempt for different student (should not be returned)
        $attempt2 = new Attempt();
        $attempt2->id = Uuid::uuid4()->toString();
        $attempt2->exam_id = $exam->id;
        $attempt2->student_id = 'student-2';
        $attempt2->attempt_number = 1;
        $attempt2->status = 'in_progress';
        $attempt2->started_at = new \DateTimeImmutable('2024-01-01 11:00:00');
        $attempt2->completed_at = null;

        $this->em->persist($attempt1);
        $this->em->persist($attempt2);
        $this->em->flush();

        $response = $this->makeRequest('GET', '/student/exams/' . $exam->id . '/attempts');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertCount(1, $data);
        $this->assertEquals($attempt1->id, $data[0]['id']);
        $this->assertEquals('student-1', $data[0]['student_id']);
        $this->assertEquals('completed', $data[0]['status']);
    }

    public function test_start_exam_creates_new_attempt(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Test Exam';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);
        $this->em->flush();

        $response = $this->makeRequest('POST', '/student/exams/' . $exam->id . '/start');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertEquals($exam->id, $data['exam_id']);
        $this->assertEquals('student-1', $data['student_id']);
        $this->assertEquals(1, $data['attempt_number']);
        $this->assertEquals('in_progress', $data['status']);
        $this->assertArrayHasKey('started_at', $data);

        // Verify attempt was created in database
        $attempt = $this->em->find(Attempt::class, $data['id']);
        $this->assertNotNull($attempt);
        $this->assertEquals('in_progress', $attempt->status);
    }

    public function test_submit_attempt_updates_status(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Test Exam';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);

        // Create attempt
        $attempt = new Attempt();
        $attempt->id = Uuid::uuid4()->toString();
        $attempt->exam_id = $exam->id;
        $attempt->student_id = 'student-1';
        $attempt->attempt_number = 1;
        $attempt->status = 'in_progress';
        $attempt->started_at = new \DateTimeImmutable('2024-01-01 10:00:00');
        $attempt->completed_at = null;

        $this->em->persist($attempt);
        $this->em->flush();

        $response = $this->makeRequest('POST', '/student/attempts/' . $attempt->id . '/submit');

        $this->assertJsonResponse($response, 200, ['message' => 'submitted']);

        // Verify attempt was updated
        $this->em->refresh($attempt);
        $this->assertEquals('completed', $attempt->status);
        $this->assertNotNull($attempt->completed_at);
    }
}