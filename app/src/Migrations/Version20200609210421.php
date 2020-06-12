<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609210421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations ADD tape_id INT DEFAULT NULL, DROP email');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2392AC90C65 FOREIGN KEY (tape_id) REFERENCES tapes (id)');
        $this->addSql('CREATE INDEX IDX_4DA2392AC90C65 ON reservations (tape_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2392AC90C65');
        $this->addSql('DROP INDEX IDX_4DA2392AC90C65 ON reservations');
        $this->addSql('ALTER TABLE reservations ADD email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP tape_id');
    }
}
