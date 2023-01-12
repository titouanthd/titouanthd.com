<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111205648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__reaction AS SELECT id, user_id, post_id, type FROM reaction');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('CREATE TABLE reaction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, post_id INTEGER DEFAULT NULL, comment_id INTEGER DEFAULT NULL, reply_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, CONSTRAINT FK_A4D707F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A4D707F74B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A4D707F7F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A4D707F78A0E4E7F FOREIGN KEY (reply_id) REFERENCES reply (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reaction (id, user_id, post_id, type) SELECT id, user_id, post_id, type FROM __temp__reaction');
        $this->addSql('DROP TABLE __temp__reaction');
        $this->addSql('CREATE INDEX IDX_A4D707F74B89032C ON reaction (post_id)');
        $this->addSql('CREATE INDEX IDX_A4D707F7A76ED395 ON reaction (user_id)');
        $this->addSql('CREATE INDEX IDX_A4D707F7F8697D13 ON reaction (comment_id)');
        $this->addSql('CREATE INDEX IDX_A4D707F78A0E4E7F ON reaction (reply_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__reaction AS SELECT id, user_id, post_id, type FROM reaction');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('CREATE TABLE reaction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, post_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, CONSTRAINT FK_A4D707F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A4D707F74B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reaction (id, user_id, post_id, type) SELECT id, user_id, post_id, type FROM __temp__reaction');
        $this->addSql('DROP TABLE __temp__reaction');
        $this->addSql('CREATE INDEX IDX_A4D707F7A76ED395 ON reaction (user_id)');
        $this->addSql('CREATE INDEX IDX_A4D707F74B89032C ON reaction (post_id)');
    }
}
