<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211031155314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medic_pacient (medic_id BIGINT UNSIGNED NOT NULL, pacient_id BIGINT UNSIGNED NOT NULL, INDEX IDX_873B32B6409615FE (medic_id), INDEX IDX_873B32B61DF7AA4B (pacient_id), PRIMARY KEY(medic_id, pacient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medic_pacient ADD CONSTRAINT FK_873B32B6409615FE FOREIGN KEY (medic_id) REFERENCES medic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medic_pacient ADD CONSTRAINT FK_873B32B61DF7AA4B FOREIGN KEY (pacient_id) REFERENCES pacient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE medic_pacient');
    }
}
