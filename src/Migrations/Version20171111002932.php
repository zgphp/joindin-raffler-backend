<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171111002932 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is modified to preserve the 'enddate' column, which still holds orphan data
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE joindincomments DROP CONSTRAINT FK_FA3AD44E6F0601D5');
        $this->addSql('ALTER TABLE joindincomments ADD CONSTRAINT FK_FA3AD44E6F0601D5 FOREIGN KEY (talk_id) REFERENCES joindinTalks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joindintalks DROP CONSTRAINT FK_FB7FC5EA71F7E88B');
        $this->addSql('ALTER TABLE joindintalks ADD CONSTRAINT FK_FB7FC5EA71F7E88B FOREIGN KEY (event_id) REFERENCES joindinEvents (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is modified so as not to recreate the 'enddate' column, which still holds orphan data
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE joindinTalks DROP CONSTRAINT fk_fb7fc5ea71f7e88b');
        $this->addSql('ALTER TABLE joindinTalks ADD CONSTRAINT fk_fb7fc5ea71f7e88b FOREIGN KEY (event_id) REFERENCES joindinevents (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joindinComments DROP CONSTRAINT fk_fa3ad44e6f0601d5');
        $this->addSql('ALTER TABLE joindinComments ADD CONSTRAINT fk_fa3ad44e6f0601d5 FOREIGN KEY (talk_id) REFERENCES joindintalks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
