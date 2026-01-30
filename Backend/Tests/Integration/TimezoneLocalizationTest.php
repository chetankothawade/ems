<?php

namespace Tests\Integration;

use App\Models\Exam;
use App\Models\Attempt;
use Ramsey\Uuid\Uuid;

class TimezoneLocalizationTest extends ApiTestCase
{
    /**
     * Test that API returns times in ISO 8601 UTC format (RFC 3339)
     * allowing clients to convert to local timezone.
     */
    public function test_attempt_times_returned_in_iso_8601_utc_format(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Test Exam';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);

        // Create attempt with specific UTC time
        $attempt = new Attempt();
        $attempt->id = Uuid::uuid4()->toString();
        $attempt->exam_id = $exam->id;
        $attempt->student_id = 'student-1';
        $attempt->attempt_number = 1;
        $attempt->status = 'completed';
        // Using explicit UTC timezone
        $attempt->started_at = new \DateTimeImmutable('2024-01-01T10:30:00', new \DateTimeZone('UTC'));
        $attempt->completed_at = new \DateTimeImmutable('2024-01-01T11:00:00', new \DateTimeZone('UTC'));

        $this->em->persist($attempt);
        $this->em->flush();

        $response = $this->makeRequest('GET', '/student/exams/' . $exam->id . '/attempts');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertCount(1, $data);
        
        // Verify ISO 8601 format with UTC timezone indicator
        $startedAt = $data[0]['started_at'];
        $completedAt = $data[0]['completed_at'];

        // ISO 8601 UTC format: YYYY-MM-DDTHH:MM:SS+00:00 or YYYY-MM-DDTHH:MM:SSZ
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\+00:00|Z)$/',
            $startedAt,
            'started_at should be in ISO 8601 UTC format'
        );

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\+00:00|Z)$/',
            $completedAt,
            'completed_at should be in ISO 8601 UTC format'
        );

        // Verify the actual values are correct
        $this->assertStringContainsString('2024-01-01', $startedAt);
        $this->assertStringContainsString('10:30:00', $startedAt);
        $this->assertStringContainsString('2024-01-01', $completedAt);
        $this->assertStringContainsString('11:00:00', $completedAt);
    }

    /**
     * Test that null completed_at (in-progress attempts) are handled correctly.
     */
    public function test_in_progress_attempt_completed_at_is_null(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Test Exam';
        $exam->max_attempts = 2;
        $exam->cooldown_minutes = 0;

        $this->em->persist($exam);

        // Create in-progress attempt
        $attempt = new Attempt();
        $attempt->id = Uuid::uuid4()->toString();
        $attempt->exam_id = $exam->id;
        $attempt->student_id = 'student-1';
        $attempt->attempt_number = 1;
        $attempt->status = 'in_progress';
        $attempt->started_at = new \DateTimeImmutable('2024-01-01T10:30:00', new \DateTimeZone('UTC'));
        $attempt->completed_at = null; // Not yet completed

        $this->em->persist($attempt);
        $this->em->flush();

        $response = $this->makeRequest('GET', '/student/exams/' . $exam->id . '/attempts');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertNull($data[0]['completed_at'], 'In-progress attempt should have null completed_at');
        $this->assertNotNull($data[0]['started_at'], 'In-progress attempt should have started_at');
    }

    /**
     * Test that dashboard includes properly formatted attempt times.
     */
    public function test_dashboard_returns_attempts_with_utc_times(): void
    {
        // Create exam
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->title = 'Test Exam';
        $exam->max_attempts = 3;
        $exam->cooldown_minutes = 60;

        $this->em->persist($exam);

        // Create multiple attempts with different times
        $attempt1 = new Attempt();
        $attempt1->id = Uuid::uuid4()->toString();
        $attempt1->exam_id = $exam->id;
        $attempt1->student_id = 'student-1';
        $attempt1->attempt_number = 1;
        $attempt1->status = 'completed';
        $attempt1->started_at = new \DateTimeImmutable('2024-01-01T08:00:00', new \DateTimeZone('UTC'));
        $attempt1->completed_at = new \DateTimeImmutable('2024-01-01T08:45:00', new \DateTimeZone('UTC'));

        $attempt2 = new Attempt();
        $attempt2->id = Uuid::uuid4()->toString();
        $attempt2->exam_id = $exam->id;
        $attempt2->student_id = 'student-1';
        $attempt2->attempt_number = 2;
        $attempt2->status = 'in_progress';
        $attempt2->started_at = new \DateTimeImmutable('2024-01-01T09:50:00', new \DateTimeZone('UTC'));
        $attempt2->completed_at = null;

        $this->em->persist($attempt1);
        $this->em->persist($attempt2);
        $this->em->flush();

        $response = $this->makeRequest('GET', '/student/exams');

        $this->assertJsonResponse($response, 200);

        $data = $this->getJsonResponse($response);
        $this->assertCount(1, $data);

        $examData = $data[0];
        $this->assertCount(2, $examData['attempts']);

        // Verify both attempts have properly formatted times
        foreach ($examData['attempts'] as $attempt) {
            $this->assertIsString($attempt['started_at']);
            $this->assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\+00:00|Z)$/',
                $attempt['started_at']
            );

            // completed_at can be null or ISO 8601 format
            if ($attempt['completed_at'] !== null) {
                $this->assertIsString($attempt['completed_at']);
                $this->assertMatchesRegularExpression(
                    '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\+00:00|Z)$/',
                    $attempt['completed_at']
                );
            }
        }
    }

    /**
     * Test that started_at time during start() is in ISO 8601 UTC format.
     */
    public function test_start_attempt_returns_utc_started_time(): void
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
        
        // Verify started_at is in ISO 8601 UTC format
        $this->assertNotNull($data['started_at']);
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\+00:00|Z)$/',
            $data['started_at'],
            'started_at should be in ISO 8601 UTC format'
        );

        // Verify completed_at is not set
        $this->assertNull($data['completed_at']);
    }
}
