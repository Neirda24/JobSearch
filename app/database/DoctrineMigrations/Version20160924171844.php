<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160924171844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE search_details ADD search_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE search_details ADD CONSTRAINT FK_FFF7E410650760A9 FOREIGN KEY (search_id) REFERENCES search (id)');
        $this->addSql('CREATE INDEX IDX_FFF7E410650760A9 ON search_details (search_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE search_details DROP FOREIGN KEY FK_FFF7E410650760A9');
        $this->addSql('DROP INDEX IDX_FFF7E410650760A9 ON search_details');
        $this->addSql('ALTER TABLE search_details DROP search_id');
    }
}
