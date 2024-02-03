<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202132750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE option_maximum_hebergement (option_maximum_id INT NOT NULL, hebergement_id INT NOT NULL, INDEX IDX_292AADA5D56DA9B5 (option_maximum_id), INDEX IDX_292AADA523BB0F66 (hebergement_id), PRIMARY KEY(option_maximum_id, hebergement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE option_maximum_hebergement ADD CONSTRAINT FK_292AADA5D56DA9B5 FOREIGN KEY (option_maximum_id) REFERENCES option_maximum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_hebergement ADD CONSTRAINT FK_292AADA523BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_emplacement DROP FOREIGN KEY FK_A1B715CFC4598A51');
        $this->addSql('ALTER TABLE option_maximum_emplacement DROP FOREIGN KEY FK_A1B715CFD56DA9B5');
        $this->addSql('DROP TABLE option_maximum_emplacement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE option_maximum_emplacement (option_maximum_id INT NOT NULL, emplacement_id INT NOT NULL, INDEX IDX_A1B715CFC4598A51 (emplacement_id), INDEX IDX_A1B715CFD56DA9B5 (option_maximum_id), PRIMARY KEY(option_maximum_id, emplacement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE option_maximum_emplacement ADD CONSTRAINT FK_A1B715CFC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_emplacement ADD CONSTRAINT FK_A1B715CFD56DA9B5 FOREIGN KEY (option_maximum_id) REFERENCES option_maximum (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_hebergement DROP FOREIGN KEY FK_292AADA5D56DA9B5');
        $this->addSql('ALTER TABLE option_maximum_hebergement DROP FOREIGN KEY FK_292AADA523BB0F66');
        $this->addSql('DROP TABLE option_maximum_hebergement');
    }
}
