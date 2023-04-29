<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416162330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE candidat ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE consultant ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE recruteur ADD role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP role');
        $this->addSql('ALTER TABLE candidat DROP role');
        $this->addSql('ALTER TABLE consultant DROP role');
        $this->addSql('ALTER TABLE recruteur DROP role');
    }
}
