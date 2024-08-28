<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828143153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD driver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_773DE69DC3423909 ON car (driver_id)');
        $this->addSql('DROP INDEX uniq_11667cd9c3c6f69f');
        $this->addSql('CREATE INDEX IDX_11667CD9C3C6F69F ON driver (car_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DC3423909');
        $this->addSql('DROP INDEX IDX_773DE69DC3423909');
        $this->addSql('ALTER TABLE car DROP driver_id');
        $this->addSql('DROP INDEX IDX_11667CD9C3C6F69F');
        $this->addSql('CREATE UNIQUE INDEX uniq_11667cd9c3c6f69f ON driver (car_id)');
    }
}
