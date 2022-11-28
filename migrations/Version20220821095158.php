<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220821095158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documentsforproduct (id INT AUTO_INCREMENT NOT NULL, productinfo_id INT DEFAULT NULL, docinfo_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, INDEX IDX_31BFA94CC6C467DF (productinfo_id), INDEX IDX_31BFA94C26DF3BD7 (docinfo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE documentsforproduct ADD CONSTRAINT FK_31BFA94CC6C467DF FOREIGN KEY (productinfo_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE documentsforproduct ADD CONSTRAINT FK_31BFA94C26DF3BD7 FOREIGN KEY (docinfo_id) REFERENCES doctype (id)');
        $this->addSql('ALTER TABLE doctype ADD productdoctype_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE documentsforproduct');
        $this->addSql('ALTER TABLE doctype DROP productdoctype_id');
    }
}
