<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811190932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9D066842');
        $this->addSql('DROP INDEX IDX_5A8A6C8D9D066842 ON post');
        $this->addSql('ALTER TABLE post ADD category_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD thumbnail VARCHAR(100) NOT NULL, DROP categoty_id, DROP sortby, DROP blogthum, CHANGE update_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D12469DE2 ON post (category_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D12469DE2');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP INDEX IDX_5A8A6C8D12469DE2 ON post');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post ADD categoty_id INT NOT NULL, ADD sortby INT NOT NULL, ADD blogthum VARCHAR(255) NOT NULL, DROP category_id, DROP user_id, DROP thumbnail, CHANGE updated_at update_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9D066842 FOREIGN KEY (categoty_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D9D066842 ON post (categoty_id)');
    }
}
