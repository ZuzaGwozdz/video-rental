<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908155627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_data ADD user_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE user_data ADD CONSTRAINT FK_D772BFAAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D772BFAAA76ED395 ON user_data (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_data DROP FOREIGN KEY FK_D772BFAAA76ED395');
        $this->addSql('DROP INDEX UNIQ_D772BFAAA76ED395 ON user_data');
        $this->addSql('ALTER TABLE user_data DROP user_id');
    }
}
