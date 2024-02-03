<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201171254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE option_maximum DROP FOREIGN KEY FK_299E273EA58BC72');
        $this->addSql('DROP INDEX IDX_299E273EA58BC72 ON option_maximum');
        $this->addSql('ALTER TABLE option_maximum CHANGE _option_id option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE option_maximum ADD CONSTRAINT FK_299E273A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
        $this->addSql('CREATE INDEX IDX_299E273A7C41D6F ON option_maximum (option_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE option_maximum DROP FOREIGN KEY FK_299E273A7C41D6F');
        $this->addSql('DROP INDEX IDX_299E273A7C41D6F ON option_maximum');
        $this->addSql('ALTER TABLE option_maximum CHANGE option_id _option_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE option_maximum ADD CONSTRAINT FK_299E273EA58BC72 FOREIGN KEY (_option_id) REFERENCES `option` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_299E273EA58BC72 ON option_maximum (_option_id)');
    }
}
