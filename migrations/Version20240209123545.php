<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209123545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement ADD user_id INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B1DC7A1EA76ED395 ON paiement (user_id)');
        $this->addSql('ALTER TABLE reservation CHANGE debut debut DATE DEFAULT NULL, CHANGE fin fin DATE DEFAULT NULL, CHANGE adultes adultes INT DEFAULT NULL, CHANGE enfants enfants INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EA76ED395');
        $this->addSql('DROP INDEX IDX_B1DC7A1EA76ED395 ON paiement');
        $this->addSql('ALTER TABLE paiement DROP user_id, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE debut debut DATE NOT NULL, CHANGE fin fin DATE NOT NULL, CHANGE adultes adultes INT NOT NULL, CHANGE enfants enfants INT NOT NULL');
    }
}
