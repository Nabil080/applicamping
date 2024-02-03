<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201135943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emplacement_tag (emplacement_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_74F5E7F8C4598A51 (emplacement_id), INDEX IDX_74F5E7F8BAD26311 (tag_id), PRIMARY KEY(emplacement_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emplacement_tag ADD CONSTRAINT FK_74F5E7F8C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emplacement_tag ADD CONSTRAINT FK_74F5E7F8BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9EA58BC72');
        $this->addSql('DROP INDEX IDX_E7189C9EA58BC72 ON tarif');
        $this->addSql('ALTER TABLE tarif CHANGE _option_id option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('CREATE INDEX IDX_E7189C9A7C41D6F ON tarif (option_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplacement_tag DROP FOREIGN KEY FK_74F5E7F8C4598A51');
        $this->addSql('ALTER TABLE emplacement_tag DROP FOREIGN KEY FK_74F5E7F8BAD26311');
        $this->addSql('DROP TABLE emplacement_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9A7C41D6F');
        $this->addSql('DROP INDEX IDX_E7189C9A7C41D6F ON tarif');
        $this->addSql('ALTER TABLE tarif CHANGE option_id _option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9EA58BC72 FOREIGN KEY (_option_id) REFERENCES `option` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E7189C9EA58BC72 ON tarif (_option_id)');
    }
}
