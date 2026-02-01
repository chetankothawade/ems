<?php

declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'exams')]
#[ORM\HasLifecycleCallbacks]
class Exam
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    public string $id;

    #[ORM\Column]
    public string $title;

    #[ORM\Column(type: 'integer')]
    public int $max_attempts;

    #[ORM\Column(type: 'integer')]
    public int $cooldown_minutes;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $updated_at;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /*
    |--------------------------------------------------------------------------
    | Auto timestamps (Doctrine magic)
    |--------------------------------------------------------------------------
    */

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $this->created_at = $now;
        $this->updated_at = $now;
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }
}
