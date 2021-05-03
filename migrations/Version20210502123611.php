<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502123611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partie ADD date DATE NOT NULL, ADD passe TINYINT(1) NOT NULL, ADD present TINYINT(1) NOT NULL, ADD futur TINYINT(1) NOT NULL, ADD je TINYINT(1) NOT NULL, ADD tu TINYINT(1) NOT NULL, ADD il TINYINT(1) NOT NULL, ADD nous TINYINT(1) NOT NULL, ADD vous TINYINT(1) NOT NULL, ADD ils TINYINT(1) NOT NULL, ADD affirmation TINYINT(1) NOT NULL, ADD question TINYINT(1) NOT NULL, ADD negation TINYINT(1) NOT NULL, ADD tour TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partie DROP date, DROP passe, DROP present, DROP futur, DROP je, DROP tu, DROP il, DROP nous, DROP vous, DROP ils, DROP affirmation, DROP question, DROP negation, DROP tour');
    }
}
