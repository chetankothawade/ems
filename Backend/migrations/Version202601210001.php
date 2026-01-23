<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202601210001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema for exam management system';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
        CREATE TABLE exams (
            id CHAR(36) NOT NULL,
            title VARCHAR(255) NOT NULL,
            max_attempts INT NOT NULL,
            cooldown_minutes INT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

        $this->addSql("
        CREATE TABLE students (
            id CHAR(36) NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

        $this->addSql("
        CREATE TABLE attempts (
            id CHAR(36) NOT NULL,
            exam_id CHAR(36) NOT NULL,
            student_id CHAR(36) NOT NULL,
            attempt_number INT NOT NULL,
            status VARCHAR(20) NOT NULL,
            started_at DATETIME NOT NULL,
            completed_at DATETIME DEFAULT NULL,
            PRIMARY KEY (id),
            INDEX IDX_ATTEMPT_EXAM_STUDENT (exam_id, student_id),
            INDEX IDX_ATTEMPT_STUDENT_EXAM_NUM (student_id, exam_id, attempt_number),
            CONSTRAINT FK_ATTEMPT_EXAM FOREIGN KEY (exam_id)
                REFERENCES exams (id) ON DELETE CASCADE,
            CONSTRAINT FK_ATTEMPT_STUDENT FOREIGN KEY (student_id)
                REFERENCES students (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    }


    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE attempts");
        $this->addSql("DROP TABLE students");
        $this->addSql("DROP TABLE exams");
    }
}
