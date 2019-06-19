<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180126123008 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration promotes four records as ZgPhp Meetup organizers
        $this->addSql('UPDATE joindinUsers SET organizer = TRUE WHERE id IN (27939, 31316, 47268, 26764)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration demotes four records representing ZgPhp Meetup organizers
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('UPDATE joindinUsers SET organizer = FALSE WHERE id IN (27939, 31316, 47268, 26764)');
    }
}
