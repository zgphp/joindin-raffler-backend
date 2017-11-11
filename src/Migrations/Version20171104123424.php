<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171104123424 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE joindinComments (id INT NOT NULL, user_id INT DEFAULT NULL, talk_id INT DEFAULT NULL, comment TEXT NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA3AD44EA76ED395 ON joindinComments (user_id)');
        $this->addSql('CREATE INDEX IDX_FA3AD44E6F0601D5 ON joindinComments (talk_id)');
        $this->addSql('ALTER TABLE joindinComments ADD CONSTRAINT FK_FA3AD44EA76ED395 FOREIGN KEY (user_id) REFERENCES joindinUsers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joindinComments ADD CONSTRAINT FK_FA3AD44E6F0601D5 FOREIGN KEY (talk_id) REFERENCES joindinTalks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is modified as to not try to recreate an existing 'public' schema
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE joindinComments');
    }
}
