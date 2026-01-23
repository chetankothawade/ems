<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122092343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attempts DROP FOREIGN KEY `FK_ATTEMPT_EXAM`');
        $this->addSql('ALTER TABLE attempts DROP FOREIGN KEY `FK_ATTEMPT_STUDENT`');
        $this->addSql('DROP INDEX IDX_ATTEMPT_EXAM_STUDENT ON attempts');
        $this->addSql('DROP INDEX IDX_ATTEMPT_STUDENT_EXAM_NUM ON attempts');
        $this->addSql('DROP INDEX IDX_BFC7E764578D5E91 ON attempts');
        $this->addSql('DROP INDEX IDX_BFC7E764CB944F1A ON attempts');
        $this->addSql('ALTER TABLE attempts CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE students CHANGE id id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attempts CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE attempts ADD CONSTRAINT `FK_ATTEMPT_EXAM` FOREIGN KEY (exam_id) REFERENCES exams (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attempts ADD CONSTRAINT `FK_ATTEMPT_STUDENT` FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_ATTEMPT_EXAM_STUDENT ON attempts (exam_id, student_id)');
        $this->addSql('CREATE INDEX IDX_ATTEMPT_STUDENT_EXAM_NUM ON attempts (student_id, exam_id, attempt_number)');
        $this->addSql('CREATE INDEX IDX_BFC7E764578D5E91 ON attempts (exam_id)');
        $this->addSql('CREATE INDEX IDX_BFC7E764CB944F1A ON attempts (student_id)');
        $this->addSql('ALTER TABLE students CHANGE id id CHAR(36) NOT NULL');
    }
}
