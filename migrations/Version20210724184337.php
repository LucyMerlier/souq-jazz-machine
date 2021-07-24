<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210724184337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concert DROP FOREIGN KEY FK_D57C02D27E3C61F9');
        $this->addSql('DROP INDEX IDX_D57C02D27E3C61F9 ON concert');
        $this->addSql('ALTER TABLE concert ADD owner VARCHAR(255) NOT NULL, DROP owner_id');
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA17E3C61F9');
        $this->addSql('DROP INDEX IDX_33EDEEA17E3C61F9 ON song');
        $this->addSql('ALTER TABLE song ADD owner VARCHAR(255) NOT NULL, DROP owner_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concert ADD owner_id INT NOT NULL, DROP owner');
        $this->addSql('ALTER TABLE concert ADD CONSTRAINT FK_D57C02D27E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D57C02D27E3C61F9 ON concert (owner_id)');
        $this->addSql('ALTER TABLE song ADD owner_id INT NOT NULL, DROP owner');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA17E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_33EDEEA17E3C61F9 ON song (owner_id)');
    }
}
