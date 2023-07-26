<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230721034748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catestaff (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE staffs ADD staffcate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE staffs ADD CONSTRAINT FK_54D5390817E5DDD FOREIGN KEY (staffcate_id) REFERENCES catestaff (id)');
        $this->addSql('CREATE INDEX IDX_54D5390817E5DDD ON staffs (staffcate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE staffs DROP FOREIGN KEY FK_54D5390817E5DDD');
        $this->addSql('DROP TABLE catestaff');
        $this->addSql('DROP INDEX IDX_54D5390817E5DDD ON staffs');
        $this->addSql('ALTER TABLE staffs DROP staffcate_id');
    }
}
