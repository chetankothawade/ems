<?php

declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'students')]
class Student
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    public string $id;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->created_at = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }
}
