<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103182305 extends AbstractMigration
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
        $this->addSql('ALTER TABLE consultatie ADD medic_id BIGINT UNSIGNED DEFAULT NULL, ADD pacient_id BIGINT UNSIGNED DEFAULT NULL, ADD medicament_id BIGINT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11C409615FE FOREIGN KEY (medic_id) REFERENCES medic (id)');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11C1DF7AA4B FOREIGN KEY (pacient_id) REFERENCES pacient (id)');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11CAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
        $this->addSql('CREATE INDEX IDX_A092B11C409615FE ON consultatie (medic_id)');
        $this->addSql('CREATE INDEX IDX_A092B11C1DF7AA4B ON consultatie (pacient_id)');
        $this->addSql('CREATE INDEX IDX_A092B11CAB0D61F7 ON consultatie (medicament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE medic_pacient');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11C409615FE');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11C1DF7AA4B');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11CAB0D61F7');
        $this->addSql('DROP INDEX IDX_A092B11C409615FE ON consultatie');
        $this->addSql('DROP INDEX IDX_A092B11C1DF7AA4B ON consultatie');
        $this->addSql('DROP INDEX IDX_A092B11CAB0D61F7 ON consultatie');
        $this->addSql('ALTER TABLE consultatie DROP medic_id, DROP pacient_id, DROP medicament_id');
    }
}
