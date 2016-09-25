<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160924174943 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collaborator_details (collaborator_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', details_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_A72D3EFF30098C8C (collaborator_id), INDEX IDX_A72D3EFFBB1A0722 (details_id), PRIMARY KEY(collaborator_id, details_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaborator_details ADD CONSTRAINT FK_A72D3EFF30098C8C FOREIGN KEY (collaborator_id) REFERENCES collaborator (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborator_details ADD CONSTRAINT FK_A72D3EFFBB1A0722 FOREIGN KEY (details_id) REFERENCES search_details (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE details_collaborator');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE details_collaborator (details_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', collaborator_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', INDEX IDX_4D04DDDBBB1A0722 (details_id), INDEX IDX_4D04DDDB30098C8C (collaborator_id), PRIMARY KEY(details_id, collaborator_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE details_collaborator ADD CONSTRAINT FK_4D04DDDB30098C8C FOREIGN KEY (collaborator_id) REFERENCES collaborator (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_collaborator ADD CONSTRAINT FK_4D04DDDBBB1A0722 FOREIGN KEY (details_id) REFERENCES search_details (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE collaborator_details');
    }
}
