<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708133645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE music_sheet (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, song_id INT NOT NULL, url VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E0B5EA2ACF11D9C (instrument_id), INDEX IDX_E0B5EA2AA0BDB2F3 (song_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE song (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, title VARCHAR(50) NOT NULL, composer VARCHAR(50) DEFAULT NULL, arranger VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_33EDEEA17E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE music_sheet ADD CONSTRAINT FK_E0B5EA2ACF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE music_sheet ADD CONSTRAINT FK_E0B5EA2AA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA17E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE music_sheet DROP FOREIGN KEY FK_E0B5EA2AA0BDB2F3');
        $this->addSql('DROP TABLE music_sheet');
        $this->addSql('DROP TABLE song');
    }
}
