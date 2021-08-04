<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804141246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE joueurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, partie_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, score INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_F0FD889DE075F7A4 ON joueurs (partie_id)');
        $this->addSql('CREATE TABLE mots (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, turc VARCHAR(255) NOT NULL, francais VARCHAR(255) NOT NULL, adjectif BOOLEAN DEFAULT NULL)');
        $this->addSql('CREATE TABLE partie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nbrjoueurs INTEGER NOT NULL, date DATE NOT NULL, passe BOOLEAN NOT NULL, present BOOLEAN NOT NULL, futur BOOLEAN NOT NULL, je BOOLEAN NOT NULL, tu BOOLEAN NOT NULL, il BOOLEAN NOT NULL, nous BOOLEAN NOT NULL, vous BOOLEAN NOT NULL, ils BOOLEAN NOT NULL, affirmation BOOLEAN NOT NULL, question BOOLEAN NOT NULL, negation BOOLEAN NOT NULL, tour INTEGER NOT NULL, cycletour INTEGER NOT NULL, ordre CLOB DEFAULT NULL --(DC2Type:array)
        , lastmot INTEGER DEFAULT NULL, lasttemps INTEGER DEFAULT NULL, lasttype INTEGER DEFAULT NULL, lastpersonne INTEGER DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE joueurs');
        $this->addSql('DROP TABLE mots');
        $this->addSql('DROP TABLE partie');
    }
}
