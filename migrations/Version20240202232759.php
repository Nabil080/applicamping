<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202232759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9C4598A51');
        $this->addSql('DROP INDEX IDX_E7189C9C4598A51 ON tarif');
        $this->addSql('ALTER TABLE tarif CHANGE emplacement_id hebergement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C923BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('CREATE INDEX IDX_E7189C923BB0F66 ON tarif (hebergement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C923BB0F66');
        $this->addSql('DROP INDEX IDX_E7189C923BB0F66 ON tarif');
        $this->addSql('ALTER TABLE tarif CHANGE hebergement_id emplacement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E7189C9C4598A51 ON tarif (emplacement_id)');
    }
}
