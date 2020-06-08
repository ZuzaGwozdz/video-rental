<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523122631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tapes_tags (tape_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5BE346EE2AC90C65 (tape_id), INDEX IDX_5BE346EEBAD26311 (tag_id), PRIMARY KEY(tape_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tapes_tags ADD CONSTRAINT FK_5BE346EE2AC90C65 FOREIGN KEY (tape_id) REFERENCES tapes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tapes_tags ADD CONSTRAINT FK_5BE346EEBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tapes_tags');
    }
}
