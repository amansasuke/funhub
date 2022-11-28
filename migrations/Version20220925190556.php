<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220925190556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doc_for_client (id INT AUTO_INCREMENT NOT NULL, ordername_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, doclink VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_CB8E71834BAB6B80 (ordername_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE doc_for_client ADD CONSTRAINT FK_CB8E71834BAB6B80 FOREIGN KEY (ordername_id) REFERENCES orders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE doc_for_client');
    }
}
