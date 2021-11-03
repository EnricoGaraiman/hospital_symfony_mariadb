<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211031155220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consultatie (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, data DATETIME NOT NULL, diagnostic VARCHAR(255) NOT NULL, doza_medicament DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medic (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, nume_medic VARCHAR(255) NOT NULL, prenume_medic VARCHAR(255) NOT NULL, specializare VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, denumire VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pacient (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, cnp VARCHAR(255) NOT NULL, nume_pacient VARCHAR(255) NOT NULL, prenume_pacient VARCHAR(255) NOT NULL, adresa VARCHAR(255) DEFAULT NULL, asigurare VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE consultatie');
        $this->addSql('DROP TABLE medic');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE pacient');
    }
}
