<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200708071918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A84A0A3ED');
        $this->addSql('ALTER TABLE comments CHANGE content_id content_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9A76ED395');
        $this->addSql('DROP INDEX IDX_FEC530A9A76ED395 ON content');
        $this->addSql('ALTER TABLE content ADD status VARCHAR(100) NOT NULL, ADD media_path_file VARCHAR(255) DEFAULT NULL, CHANGE message message VARCHAR(5000) NOT NULL, CHANGE user_id username_id INT NOT NULL, CHANGE media_path media_path_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9ED766068 FOREIGN KEY (username_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FEC530A9ED766068 ON content (username_id)');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D84A0A3ED');
        $this->addSql('ALTER TABLE likes CHANGE content_id content_id INT NOT NULL');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A84A0A3ED');
        $this->addSql('ALTER TABLE comments CHANGE content_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9ED766068');
        $this->addSql('DROP INDEX IDX_FEC530A9ED766068 ON content');
        $this->addSql('ALTER TABLE content ADD media_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP status, DROP media_path_url, DROP media_path_file, CHANGE message message VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9A76ED395 ON content (user_id)');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D84A0A3ED');
        $this->addSql('ALTER TABLE likes CHANGE content_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
    }
}
