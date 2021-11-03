<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103190927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11C1DF7AA4B');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11C409615FE');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11CAB0D61F7');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11C1DF7AA4B FOREIGN KEY (pacient_id) REFERENCES pacient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11C409615FE FOREIGN KEY (medic_id) REFERENCES medic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11CAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11C409615FE');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11C1DF7AA4B');
        $this->addSql('ALTER TABLE consultatie DROP FOREIGN KEY FK_A092B11CAB0D61F7');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11C409615FE FOREIGN KEY (medic_id) REFERENCES medic (id)');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11C1DF7AA4B FOREIGN KEY (pacient_id) REFERENCES pacient (id)');
        $this->addSql('ALTER TABLE consultatie ADD CONSTRAINT FK_A092B11CAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
    }
}
