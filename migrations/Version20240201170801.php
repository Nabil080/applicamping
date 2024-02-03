<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201170801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE option_maximum (id INT AUTO_INCREMENT NOT NULL, _option_id INT DEFAULT NULL, nombre INT NOT NULL, many_to_one VARCHAR(255) NOT NULL, INDEX IDX_299E273EA58BC72 (_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_maximum_emplacement (option_maximum_id INT NOT NULL, emplacement_id INT NOT NULL, INDEX IDX_A1B715CFD56DA9B5 (option_maximum_id), INDEX IDX_A1B715CFC4598A51 (emplacement_id), PRIMARY KEY(option_maximum_id, emplacement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_maximum_saison (option_maximum_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_C9F0CFD0D56DA9B5 (option_maximum_id), INDEX IDX_C9F0CFD0F965414C (saison_id), PRIMARY KEY(option_maximum_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE option_maximum ADD CONSTRAINT FK_299E273EA58BC72 FOREIGN KEY (_option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE option_maximum_emplacement ADD CONSTRAINT FK_A1B715CFD56DA9B5 FOREIGN KEY (option_maximum_id) REFERENCES option_maximum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_emplacement ADD CONSTRAINT FK_A1B715CFC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_saison ADD CONSTRAINT FK_C9F0CFD0D56DA9B5 FOREIGN KEY (option_maximum_id) REFERENCES option_maximum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum_saison ADD CONSTRAINT FK_C9F0CFD0F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_hebergement DROP FOREIGN KEY FK_A2932FFD23BB0F66');
        $this->addSql('ALTER TABLE regle_options_hebergement DROP FOREIGN KEY FK_A2932FFD3A7B3F76');
        $this->addSql('ALTER TABLE regle_options_saison DROP FOREIGN KEY FK_78BB00443A7B3F76');
        $this->addSql('ALTER TABLE regle_options_saison DROP FOREIGN KEY FK_78BB0044F965414C');
        $this->addSql('DROP TABLE regle_options');
        $this->addSql('DROP TABLE regle_options_hebergement');
        $this->addSql('DROP TABLE regle_options_saison');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE regle_options (id INT AUTO_INCREMENT NOT NULL, mmaximum INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE regle_options_hebergement (regle_options_id INT NOT NULL, hebergement_id INT NOT NULL, INDEX IDX_A2932FFD23BB0F66 (hebergement_id), INDEX IDX_A2932FFD3A7B3F76 (regle_options_id), PRIMARY KEY(regle_options_id, hebergement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE regle_options_saison (regle_options_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_78BB00443A7B3F76 (regle_options_id), INDEX IDX_78BB0044F965414C (saison_id), PRIMARY KEY(regle_options_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE regle_options_hebergement ADD CONSTRAINT FK_A2932FFD23BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_hebergement ADD CONSTRAINT FK_A2932FFD3A7B3F76 FOREIGN KEY (regle_options_id) REFERENCES regle_options (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_saison ADD CONSTRAINT FK_78BB00443A7B3F76 FOREIGN KEY (regle_options_id) REFERENCES regle_options (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_saison ADD CONSTRAINT FK_78BB0044F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_maximum DROP FOREIGN KEY FK_299E273EA58BC72');
        $this->addSql('ALTER TABLE option_maximum_emplacement DROP FOREIGN KEY FK_A1B715CFD56DA9B5');
        $this->addSql('ALTER TABLE option_maximum_emplacement DROP FOREIGN KEY FK_A1B715CFC4598A51');
        $this->addSql('ALTER TABLE option_maximum_saison DROP FOREIGN KEY FK_C9F0CFD0D56DA9B5');
        $this->addSql('ALTER TABLE option_maximum_saison DROP FOREIGN KEY FK_C9F0CFD0F965414C');
        $this->addSql('DROP TABLE option_maximum');
        $this->addSql('DROP TABLE option_maximum_emplacement');
        $this->addSql('DROP TABLE option_maximum_saison');
    }
}
