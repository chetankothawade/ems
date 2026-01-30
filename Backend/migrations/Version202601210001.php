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
        /*
         * EXAMS
         */
        $exams = $schema->createTable('exams');
        $exams->addColumn('id', 'string', ['length' => 36]);
        $exams->addColumn('title', 'string', ['length' => 255]);
        $exams->addColumn('max_attempts', 'integer');
        $exams->addColumn('cooldown_minutes', 'integer');
        $exams->addColumn('created_at', 'datetime');
        $exams->addColumn('updated_at', 'datetime');
        $exams->setPrimaryKey(['id']);


        /*
         * STUDENTS
         */
        $students = $schema->createTable('students');
        $students->addColumn('id', 'string', ['length' => 36]);
        $students->addColumn('name', 'string', ['length' => 255]);
        $students->addColumn('created_at', 'datetime');
        $students->setPrimaryKey(['id']);


        /*
         * ATTEMPTS
         */
        $attempts = $schema->createTable('attempts');

        $attempts->addColumn('id', 'string', ['length' => 36]);
        $attempts->addColumn('exam_id', 'string', ['length' => 36]);
        $attempts->addColumn('student_id', 'string', ['length' => 36]);
        $attempts->addColumn('attempt_number', 'integer');
        $attempts->addColumn('status', 'string', ['length' => 255]);
        $attempts->addColumn('started_at', 'datetime');
        $attempts->addColumn('completed_at', 'datetime', ['notnull' => false]);

        $attempts->setPrimaryKey(['id']);

        // indexes (stable names!)
        $attempts->addIndex(['exam_id', 'student_id'], 'IDX_ATTEMPT_EXAM_STUDENT');
        $attempts->addIndex(['student_id', 'exam_id', 'attempt_number'], 'IDX_ATTEMPT_STUDENT_EXAM_NUM');

        // foreign keys
        $attempts->addForeignKeyConstraint(
            'exams',
            ['exam_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_ATTEMPT_EXAM'
        );

        $attempts->addForeignKeyConstraint(
            'students',
            ['student_id'],
            ['id'],
            ['onDelete' => 'CASCADE'],
            'FK_ATTEMPT_STUDENT'
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('attempts');
        $schema->dropTable('students');
        $schema->dropTable('exams');
    }
}
