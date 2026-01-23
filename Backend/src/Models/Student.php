<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

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
}
