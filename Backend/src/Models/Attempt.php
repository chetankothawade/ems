<?php

declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'attempts')]
class Attempt
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    public string $id;

    #[ORM\Column(type: 'guid')]
    public string $exam_id;

    #[ORM\Column(type: 'guid')]
    public string $student_id;

    #[ORM\Column(type: 'integer')]
    public int $attempt_number;

    #[ORM\Column]
    public string $status;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $started_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    public ?\DateTimeImmutable $completed_at = null;
}


