<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120130110639 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE club_welcome ADD location_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE club_welcome ADD CONSTRAINT FK_3A83C43164D218E FOREIGN KEY (location_id) REFERENCES club_user_location(id)");
        $this->addSql("CREATE INDEX IDX_3A83C43164D218E ON club_welcome (location_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE club_welcome DROP FOREIGN KEY FK_3A83C43164D218E");
        $this->addSql("DROP INDEX IDX_3A83C43164D218E ON club_welcome");
        $this->addSql("ALTER TABLE club_welcome DROP location_id");
    }
}
