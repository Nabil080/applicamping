<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203002739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offre_hebergement (offre_id INT NOT NULL, hebergement_id INT NOT NULL, INDEX IDX_37B57BC4CC8505A (offre_id), INDEX IDX_37B57BC23BB0F66 (hebergement_id), PRIMARY KEY(offre_id, hebergement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offre_hebergement ADD CONSTRAINT FK_37B57BC4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_hebergement ADD CONSTRAINT FK_37B57BC23BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F4383C247');
        $this->addSql('DROP INDEX IDX_AF86866F4383C247 ON offre');
        $this->addSql('ALTER TABLE offre DROP hebergements_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre_hebergement DROP FOREIGN KEY FK_37B57BC4CC8505A');
        $this->addSql('ALTER TABLE offre_hebergement DROP FOREIGN KEY FK_37B57BC23BB0F66');
        $this->addSql('DROP TABLE offre_hebergement');
        $this->addSql('ALTER TABLE offre ADD hebergements_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F4383C247 FOREIGN KEY (hebergements_id) REFERENCES hebergement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AF86866F4383C247 ON offre (hebergements_id)');
    }
}
