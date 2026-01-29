<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;

use App\Services\AttemptService;
use App\Repositories\AttemptRepository;
use App\Repositories\ExamRepository;
use App\Support\Clock\ClockInterface;
use App\Models\Exam;
use App\Models\Attempt;
use Ramsey\Uuid\Uuid;

class AttemptServiceTest extends TestCase
{
    private AttemptRepository $attemptRepo;
    private ExamRepository $examRepo;
    private ClockInterface $clock;

    private AttemptService $service;

    protected function setUp(): void
    {
        $this->attemptRepo = $this->createMock(AttemptRepository::class);
        $this->examRepo = $this->createMock(ExamRepository::class);
        $this->clock = $this->createMock(ClockInterface::class);

        $this->service = new AttemptService(
            $this->attemptRepo,
            $this->examRepo,
            $this->clock
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ✅ Can start attempt when allowed
    |--------------------------------------------------------------------------
    */
    public function test_start_attempt_success(): void
    {
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->max_attempts = 3;
        $exam->cooldown_minutes = 0;

        $now = new \DateTimeImmutable();

        $this->examRepo->method('find')->willReturn($exam);
        $this->attemptRepo->method('findByStudent')->willReturn([]);
        $this->clock->method('now')->willReturn($now);

        $this->attemptRepo
            ->expects($this->once())
            ->method('create')
            ->willReturn(new Attempt());

        $attempt = $this->service->start($exam->id, 'student-1');

        $this->assertInstanceOf(Attempt::class, $attempt);
    }



    /*
    |--------------------------------------------------------------------------
    | ❌ No attempts left
    |--------------------------------------------------------------------------
    */
    public function test_cannot_start_when_no_attempts_left(): void
    {
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->max_attempts = 1;
        $exam->cooldown_minutes = 0;

        $this->examRepo->method('find')->willReturn($exam);

        $this->attemptRepo->method('findByStudent')
            ->willReturn([new Attempt()]); // already used

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No attempts left.');

        $this->service->start($exam->id, 'student-1');
    }



    /*
    |--------------------------------------------------------------------------
    | ❌ Cooldown not finished
    |--------------------------------------------------------------------------
    */
    public function test_cannot_start_during_cooldown(): void
    {
        $exam = new Exam();
        $exam->id = Uuid::uuid4()->toString();
        $exam->max_attempts = 3;
        $exam->cooldown_minutes = 10;

        $last = new Attempt();
        $last->completed_at = new \DateTimeImmutable('now');

        $this->examRepo->method('find')->willReturn($exam);
        $this->attemptRepo->method('findByStudent')->willReturn([$last]);

        $this->clock->method('now')
            ->willReturn(new \DateTimeImmutable('+5 minutes'));

        $this->expectException(\Exception::class);

        $this->service->start($exam->id, 'student-1');
    }



    /*
    |--------------------------------------------------------------------------
    | ✅ Submit attempt updates status
    |--------------------------------------------------------------------------
    */
    public function test_submit_marks_completed(): void
    {
        $attempt = new Attempt();
        $attempt->status = 'in_progress';

        $this->attemptRepo->method('find')->willReturn($attempt);

        $this->clock->method('now')
            ->willReturn(new \DateTimeImmutable());

        $this->attemptRepo
            ->expects($this->once())
            ->method('save');

        $this->service->submit('attempt-1');

        $this->assertEquals('completed', $attempt->status);
        $this->assertNotNull($attempt->completed_at);
    }
}
