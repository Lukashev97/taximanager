<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240828133659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE logger_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE logger (id INT NOT NULL, driver_id INT NOT NULL, car_id INT NOT NULL, event_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, text TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_987E13F3C3423909 ON logger (driver_id)');
        $this->addSql('CREATE INDEX IDX_987E13F3C3C6F69F ON logger (car_id)');
        $this->addSql('ALTER TABLE logger ADD CONSTRAINT FK_987E13F3C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE logger ADD CONSTRAINT FK_987E13F3C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE logger_id_seq CASCADE');
        $this->addSql('ALTER TABLE logger DROP CONSTRAINT FK_987E13F3C3423909');
        $this->addSql('ALTER TABLE logger DROP CONSTRAINT FK_987E13F3C3C6F69F');
        $this->addSql('DROP TABLE logger');
    }
}
