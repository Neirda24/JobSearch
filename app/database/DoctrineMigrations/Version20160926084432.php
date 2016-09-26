<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160926084432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborator DROP FOREIGN KEY FK_606D487C979B1AD6');
        $this->addSql('DROP INDEX IDX_606D487C979B1AD6 ON collaborator');
        $this->addSql('ALTER TABLE collaborator DROP company_id');
        $this->addSql('ALTER TABLE search_details ADD company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE search_details ADD CONSTRAINT FK_FFF7E410979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_FFF7E410979B1AD6 ON search_details (company_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collaborator ADD company_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE collaborator ADD CONSTRAINT FK_606D487C979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_606D487C979B1AD6 ON collaborator (company_id)');
        $this->addSql('ALTER TABLE search_details DROP FOREIGN KEY FK_FFF7E410979B1AD6');
        $this->addSql('DROP INDEX IDX_FFF7E410979B1AD6 ON search_details');
        $this->addSql('ALTER TABLE search_details DROP company_id');
    }
}
