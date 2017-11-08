<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171104183806 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE raffles (id UUID NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN raffles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE raffleEvents (raffle_id UUID NOT NULL, joindinevent_id INT NOT NULL, PRIMARY KEY(raffle_id, joindinevent_id))');
        $this->addSql('CREATE INDEX IDX_6A5D398CDBEC4B1F ON raffleEvents (raffle_id)');
        $this->addSql('CREATE INDEX IDX_6A5D398C2D25C8B9 ON raffleEvents (joindinevent_id)');
        $this->addSql('COMMENT ON COLUMN raffleEvents.raffle_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE raffleWinners (raffle_id UUID NOT NULL, joindinuser_id INT NOT NULL, PRIMARY KEY(raffle_id, joindinuser_id))');
        $this->addSql('CREATE INDEX IDX_8E84A987DBEC4B1F ON raffleWinners (raffle_id)');
        $this->addSql('CREATE INDEX IDX_8E84A987C624E52E ON raffleWinners (joindinuser_id)');
        $this->addSql('COMMENT ON COLUMN raffleWinners.raffle_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE raffleNoShows (raffle_id UUID NOT NULL, joindinuser_id INT NOT NULL, PRIMARY KEY(raffle_id, joindinuser_id))');
        $this->addSql('CREATE INDEX IDX_A6A1C1FFDBEC4B1F ON raffleNoShows (raffle_id)');
        $this->addSql('CREATE INDEX IDX_A6A1C1FFC624E52E ON raffleNoShows (joindinuser_id)');
        $this->addSql('COMMENT ON COLUMN raffleNoShows.raffle_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE raffleEvents ADD CONSTRAINT FK_6A5D398CDBEC4B1F FOREIGN KEY (raffle_id) REFERENCES raffles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE raffleEvents ADD CONSTRAINT FK_6A5D398C2D25C8B9 FOREIGN KEY (joindinevent_id) REFERENCES joindinEvents (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE raffleWinners ADD CONSTRAINT FK_8E84A987DBEC4B1F FOREIGN KEY (raffle_id) REFERENCES raffles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE raffleWinners ADD CONSTRAINT FK_8E84A987C624E52E FOREIGN KEY (joindinuser_id) REFERENCES joindinUsers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE raffleNoShows ADD CONSTRAINT FK_A6A1C1FFDBEC4B1F FOREIGN KEY (raffle_id) REFERENCES raffles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE raffleNoShows ADD CONSTRAINT FK_A6A1C1FFC624E52E FOREIGN KEY (joindinuser_id) REFERENCES joindinUsers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE raffleEvents DROP CONSTRAINT FK_6A5D398CDBEC4B1F');
        $this->addSql('ALTER TABLE raffleWinners DROP CONSTRAINT FK_8E84A987DBEC4B1F');
        $this->addSql('ALTER TABLE raffleNoShows DROP CONSTRAINT FK_A6A1C1FFDBEC4B1F');
        $this->addSql('DROP TABLE raffles');
        $this->addSql('DROP TABLE raffleEvents');
        $this->addSql('DROP TABLE raffleWinners');
        $this->addSql('DROP TABLE raffleNoShows');
    }
}
