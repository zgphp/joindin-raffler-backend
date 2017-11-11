<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171108224445 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is modified to preserve the already present data in table, rename the column, and set
        // it to accept null values
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE joindinevents RENAME COLUMN startdate TO date');
        $this->addSql('ALTER TABLE joindinevents ALTER COLUMN enddate DROP NOT NULL;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is modified to preserve the already present data in table, revert the the column
        // name and null value acceptance, and to not try to recreate an existing 'public' schema

        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE joindinEvents RENAME COLUMN date TO startdate');
        $this->addSql('ALTER TABLE joindinevents ALTER COLUMN enddate SET NOT NULL;');
    }
}
