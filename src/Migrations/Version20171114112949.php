<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * This migration will insert two records into joindinUsers, representing (up until the time of this migration) the two
 * organizers of ZgPhp Meetup.
 */
class Version20171114112949 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration promotes two records as ZgPhp Meetup organizers
        $this->addSql('UPDATE joindinUsers SET organizer = TRUE WHERE id IN (18486, 31686)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration demotes two records representing ZgPhp Meetup organizers
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('UPDATE joindinUsers SET organizer = FALSE WHERE id IN (18486, 31686)');
    }
}
