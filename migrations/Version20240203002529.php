<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203002529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD hebergements_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F4383C247 FOREIGN KEY (hebergements_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F4383C247 ON offre (hebergements_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F4383C247');
        $this->addSql('DROP INDEX IDX_AF86866F4383C247 ON offre');
        $this->addSql('ALTER TABLE offre DROP hebergements_id');
    }
}
