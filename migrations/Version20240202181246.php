<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202181246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE periode (id INT AUTO_INCREMENT NOT NULL, saison_id INT NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, jours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_93C32DF3F965414C (saison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE periode ADD CONSTRAINT FK_93C32DF3F965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('ALTER TABLE saison_date DROP FOREIGN KEY FK_9EE4A986F965414C');
        $this->addSql('DROP TABLE saison_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saison_date (id INT AUTO_INCREMENT NOT NULL, saison_id INT NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, jours LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', INDEX IDX_9EE4A986F965414C (saison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE saison_date ADD CONSTRAINT FK_9EE4A986F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE periode DROP FOREIGN KEY FK_93C32DF3F965414C');
        $this->addSql('DROP TABLE periode');
    }
}
