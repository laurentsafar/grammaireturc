<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502070440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE joueurs (id INT AUTO_INCREMENT NOT NULL, partie_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, score INT DEFAULT NULL, INDEX IDX_F0FD889DE075F7A4 (partie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE joueurs ADD CONSTRAINT FK_F0FD889DE075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE joueurs');
    }
}
