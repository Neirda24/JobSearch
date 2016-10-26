<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161023142014 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborator ADD added_by_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE collaborator ADD CONSTRAINT FK_606D487C55B127A4 FOREIGN KEY (added_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_606D487C55B127A4 ON collaborator (added_by_id)');
        $this->addSql('ALTER TABLE users CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborator DROP FOREIGN KEY FK_606D487C55B127A4');
        $this->addSql('DROP INDEX IDX_606D487C55B127A4 ON collaborator');
        $this->addSql('ALTER TABLE collaborator DROP added_by_id');
        $this->addSql('ALTER TABLE users CHANGE confirmation_token confirmation_token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
