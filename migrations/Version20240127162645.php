<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240127162645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, hebergement_id INT NOT NULL, numero INT NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_C0CF65F623BB0F66 (hebergement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebergement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, minimum INT DEFAULT NULL, maximum INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, devise VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, debut DATE DEFAULT NULL, fin DATE DEFAULT NULL, utilisations INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, montant INT NOT NULL, date DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, methode VARCHAR(255) NOT NULL, INDEX IDX_B1DC7A1EB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_duree (id INT AUTO_INCREMENT NOT NULL, maximum INT DEFAULT NULL, minimum INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_duree_saison (regle_duree_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_B5F66ADD3D76B9A0 (regle_duree_id), INDEX IDX_B5F66ADDF965414C (saison_id), PRIMARY KEY(regle_duree_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_duree_hebergement (regle_duree_id INT NOT NULL, hebergement_id INT NOT NULL, INDEX IDX_2C08CA6B3D76B9A0 (regle_duree_id), INDEX IDX_2C08CA6B23BB0F66 (hebergement_id), PRIMARY KEY(regle_duree_id, hebergement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_options (id INT AUTO_INCREMENT NOT NULL, mmaximum INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_options_saison (regle_options_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_78BB00443A7B3F76 (regle_options_id), INDEX IDX_78BB0044F965414C (saison_id), PRIMARY KEY(regle_options_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_options_hebergement (regle_options_id INT NOT NULL, hebergement_id INT NOT NULL, INDEX IDX_A2932FFD3A7B3F76 (regle_options_id), INDEX IDX_A2932FFD23BB0F66 (hebergement_id), PRIMARY KEY(regle_options_id, hebergement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_reservation (id INT AUTO_INCREMENT NOT NULL, client_nom TINYINT(1) NOT NULL, client_prenom TINYINT(1) NOT NULL, client_email TINYINT(1) NOT NULL, client_telephone TINYINT(1) NOT NULL, client_adresse TINYINT(1) NOT NULL, emplacement_libre TINYINT(1) NOT NULL, emplacement_aleatoire TINYINT(1) NOT NULL, emplacement_defini TINYINT(1) NOT NULL, paiement_carte_bancaire TINYINT(1) NOT NULL, paiement_virement_bancaire TINYINT(1) NOT NULL, paiement_cheque_vacance TINYINT(1) NOT NULL, paiement_cheque TINYINT(1) NOT NULL, paiement_espece TINYINT(1) NOT NULL, acompte TINYINT(1) NOT NULL, acompte_seul TINYINT(1) NOT NULL, acompte_montant INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_sejour (id INT AUTO_INCREMENT NOT NULL, check_in LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', check_out LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_sejour_hebergement (regle_sejour_id INT NOT NULL, hebergement_id INT NOT NULL, INDEX IDX_8D5556EBA87677CF (regle_sejour_id), INDEX IDX_8D5556EB23BB0F66 (hebergement_id), PRIMARY KEY(regle_sejour_id, hebergement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regle_sejour_saison (regle_sejour_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_CDE75109A87677CF (regle_sejour_id), INDEX IDX_CDE75109F965414C (saison_id), PRIMARY KEY(regle_sejour_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, emplacement_id INT NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, adultes INT NOT NULL, enfants INT NOT NULL, commentaire VARCHAR(510) DEFAULT NULL, montant INT NOT NULL, date DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955C4598A51 (emplacement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison_date (id INT AUTO_INCREMENT NOT NULL, saison_id INT NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, jours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_9EE4A986F965414C (saison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tarif (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT DEFAULT NULL, _option_id INT DEFAULT NULL, montant INT NOT NULL, par_nuit TINYINT(1) NOT NULL, par_personne TINYINT(1) NOT NULL, adulte TINYINT(1) NOT NULL, enfant TINYINT(1) NOT NULL, INDEX IDX_E7189C9C4598A51 (emplacement_id), INDEX IDX_E7189C9EA58BC72 (_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tarif_saison (tarif_id INT NOT NULL, saison_id INT NOT NULL, INDEX IDX_7DB41301357C0A59 (tarif_id), INDEX IDX_7DB41301F965414C (saison_id), PRIMARY KEY(tarif_id, saison_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F623BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE regle_duree_saison ADD CONSTRAINT FK_B5F66ADD3D76B9A0 FOREIGN KEY (regle_duree_id) REFERENCES regle_duree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_duree_saison ADD CONSTRAINT FK_B5F66ADDF965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_duree_hebergement ADD CONSTRAINT FK_2C08CA6B3D76B9A0 FOREIGN KEY (regle_duree_id) REFERENCES regle_duree (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_duree_hebergement ADD CONSTRAINT FK_2C08CA6B23BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_saison ADD CONSTRAINT FK_78BB00443A7B3F76 FOREIGN KEY (regle_options_id) REFERENCES regle_options (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_saison ADD CONSTRAINT FK_78BB0044F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_hebergement ADD CONSTRAINT FK_A2932FFD3A7B3F76 FOREIGN KEY (regle_options_id) REFERENCES regle_options (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_options_hebergement ADD CONSTRAINT FK_A2932FFD23BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_sejour_hebergement ADD CONSTRAINT FK_8D5556EBA87677CF FOREIGN KEY (regle_sejour_id) REFERENCES regle_sejour (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_sejour_hebergement ADD CONSTRAINT FK_8D5556EB23BB0F66 FOREIGN KEY (hebergement_id) REFERENCES hebergement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_sejour_saison ADD CONSTRAINT FK_CDE75109A87677CF FOREIGN KEY (regle_sejour_id) REFERENCES regle_sejour (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regle_sejour_saison ADD CONSTRAINT FK_CDE75109F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE saison_date ADD CONSTRAINT FK_9EE4A986F965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9EA58BC72 FOREIGN KEY (_option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE tarif_saison ADD CONSTRAINT FK_7DB41301357C0A59 FOREIGN KEY (tarif_id) REFERENCES tarif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tarif_saison ADD CONSTRAINT FK_7DB41301F965414C FOREIGN KEY (saison_id) REFERENCES saison (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F623BB0F66');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EB83297E7');
        $this->addSql('ALTER TABLE regle_duree_saison DROP FOREIGN KEY FK_B5F66ADD3D76B9A0');
        $this->addSql('ALTER TABLE regle_duree_saison DROP FOREIGN KEY FK_B5F66ADDF965414C');
        $this->addSql('ALTER TABLE regle_duree_hebergement DROP FOREIGN KEY FK_2C08CA6B3D76B9A0');
        $this->addSql('ALTER TABLE regle_duree_hebergement DROP FOREIGN KEY FK_2C08CA6B23BB0F66');
        $this->addSql('ALTER TABLE regle_options_saison DROP FOREIGN KEY FK_78BB00443A7B3F76');
        $this->addSql('ALTER TABLE regle_options_saison DROP FOREIGN KEY FK_78BB0044F965414C');
        $this->addSql('ALTER TABLE regle_options_hebergement DROP FOREIGN KEY FK_A2932FFD3A7B3F76');
        $this->addSql('ALTER TABLE regle_options_hebergement DROP FOREIGN KEY FK_A2932FFD23BB0F66');
        $this->addSql('ALTER TABLE regle_sejour_hebergement DROP FOREIGN KEY FK_8D5556EBA87677CF');
        $this->addSql('ALTER TABLE regle_sejour_hebergement DROP FOREIGN KEY FK_8D5556EB23BB0F66');
        $this->addSql('ALTER TABLE regle_sejour_saison DROP FOREIGN KEY FK_CDE75109A87677CF');
        $this->addSql('ALTER TABLE regle_sejour_saison DROP FOREIGN KEY FK_CDE75109F965414C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C4598A51');
        $this->addSql('ALTER TABLE saison_date DROP FOREIGN KEY FK_9EE4A986F965414C');
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9C4598A51');
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9EA58BC72');
        $this->addSql('ALTER TABLE tarif_saison DROP FOREIGN KEY FK_7DB41301357C0A59');
        $this->addSql('ALTER TABLE tarif_saison DROP FOREIGN KEY FK_7DB41301F965414C');
        $this->addSql('DROP TABLE emplacement');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE regle_duree');
        $this->addSql('DROP TABLE regle_duree_saison');
        $this->addSql('DROP TABLE regle_duree_hebergement');
        $this->addSql('DROP TABLE regle_options');
        $this->addSql('DROP TABLE regle_options_saison');
        $this->addSql('DROP TABLE regle_options_hebergement');
        $this->addSql('DROP TABLE regle_reservation');
        $this->addSql('DROP TABLE regle_sejour');
        $this->addSql('DROP TABLE regle_sejour_hebergement');
        $this->addSql('DROP TABLE regle_sejour_saison');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE saison_date');
        $this->addSql('DROP TABLE tarif');
        $this->addSql('DROP TABLE tarif_saison');
    }
}
