<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200624121049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(64) NOT NULL, code VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, author_id INT UNSIGNED DEFAULT NULL, tape_id INT DEFAULT NULL, comment TINYTEXT DEFAULT NULL, created_at DATETIME NOT NULL, status TINYINT(1) DEFAULT NULL, INDEX IDX_4DA239F675F31B (author_id), INDEX IDX_4DA2392AC90C65 (tape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(64) NOT NULL, code VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tapes (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, availability TINYINT(1) NOT NULL, INDEX IDX_CC9DF64012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tapes_tags (tape_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5BE346EE2AC90C65 (tape_id), INDEX IDX_5BE346EEBAD26311 (tag_id), PRIMARY KEY(tape_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nick VARCHAR(64) DEFAULT NULL, name VARCHAR(64) DEFAULT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2392AC90C65 FOREIGN KEY (tape_id) REFERENCES tapes (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tapes ADD CONSTRAINT FK_CC9DF64012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE tapes_tags ADD CONSTRAINT FK_5BE346EE2AC90C65 FOREIGN KEY (tape_id) REFERENCES tapes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tapes_tags ADD CONSTRAINT FK_5BE346EEBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tapes DROP FOREIGN KEY FK_CC9DF64012469DE2');
        $this->addSql('ALTER TABLE tapes_tags DROP FOREIGN KEY FK_5BE346EEBAD26311');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2392AC90C65');
        $this->addSql('ALTER TABLE tapes_tags DROP FOREIGN KEY FK_5BE346EE2AC90C65');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239F675F31B');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tapes');
        $this->addSql('DROP TABLE tapes_tags');
        $this->addSql('DROP TABLE users');
    }
}
