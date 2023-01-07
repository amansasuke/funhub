<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106165439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE docforpro (id INT AUTO_INCREMENT NOT NULL, proinfo_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, INDEX IDX_C41F4B482BAAA2C (proinfo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE docforpro_doctype (docforpro_id INT NOT NULL, doctype_id INT NOT NULL, INDEX IDX_4E16685C864D82B7 (docforpro_id), INDEX IDX_4E16685CBE1876BC (doctype_id), PRIMARY KEY(docforpro_id, doctype_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documentsforproduct_doctype (documentsforproduct_id INT NOT NULL, doctype_id INT NOT NULL, INDEX IDX_C7AB1F5B3BDA1818 (documentsforproduct_id), INDEX IDX_C7AB1F5BBE1876BC (doctype_id), PRIMARY KEY(documentsforproduct_id, doctype_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE docforpro ADD CONSTRAINT FK_C41F4B482BAAA2C FOREIGN KEY (proinfo_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE docforpro_doctype ADD CONSTRAINT FK_4E16685C864D82B7 FOREIGN KEY (docforpro_id) REFERENCES docforpro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE docforpro_doctype ADD CONSTRAINT FK_4E16685CBE1876BC FOREIGN KEY (doctype_id) REFERENCES doctype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documentsforproduct_doctype ADD CONSTRAINT FK_C7AB1F5B3BDA1818 FOREIGN KEY (documentsforproduct_id) REFERENCES documentsforproduct (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documentsforproduct_doctype ADD CONSTRAINT FK_C7AB1F5BBE1876BC FOREIGN KEY (doctype_id) REFERENCES doctype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documentsforproduct DROP FOREIGN KEY FK_31BFA94C26DF3BD7');
        $this->addSql('DROP INDEX IDX_31BFA94C26DF3BD7 ON documentsforproduct');
        $this->addSql('ALTER TABLE documentsforproduct DROP docinfo_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE docforpro_doctype DROP FOREIGN KEY FK_4E16685C864D82B7');
        $this->addSql('DROP TABLE docforpro');
        $this->addSql('DROP TABLE docforpro_doctype');
        $this->addSql('DROP TABLE documentsforproduct_doctype');
        $this->addSql('ALTER TABLE documentsforproduct ADD docinfo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documentsforproduct ADD CONSTRAINT FK_31BFA94C26DF3BD7 FOREIGN KEY (docinfo_id) REFERENCES doctype (id)');
        $this->addSql('CREATE INDEX IDX_31BFA94C26DF3BD7 ON documentsforproduct (docinfo_id)');
    }
}
