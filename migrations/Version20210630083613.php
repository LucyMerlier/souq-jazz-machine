<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630083613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE concert (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, time TIME NOT NULL, description LONGTEXT NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concert_rate (id INT AUTO_INCREMENT NOT NULL, concert_id INT NOT NULL, category VARCHAR(40) NOT NULL, price INT NOT NULL, INDEX IDX_7A510BFE83C97B2E (concert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE concert_rate ADD CONSTRAINT FK_7A510BFE83C97B2E FOREIGN KEY (concert_id) REFERENCES concert (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE concert_rate DROP FOREIGN KEY FK_7A510BFE83C97B2E');
        $this->addSql('DROP TABLE concert');
        $this->addSql('DROP TABLE concert_rate');
    }
}
