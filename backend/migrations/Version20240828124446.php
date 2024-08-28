<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240828124446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE SEQUENCE driver_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE driver (id INT NOT NULL, car_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD9C3C6F69F ON driver (car_id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE driver_id_seq CASCADE');
        $this->addSql('ALTER TABLE driver DROP CONSTRAINT FK_11667CD9C3C6F69F');
        $this->addSql('DROP TABLE driver');
    }
}
