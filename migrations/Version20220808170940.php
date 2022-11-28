<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808170940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E1699C370B71');
        $this->addSql('DROP INDEX IDX_7332E1699C370B71 ON services');
        $this->addSql('ALTER TABLE services ADD category_id INT NOT NULL, CHANGE categoryId categoryname_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E16987CCB12E FOREIGN KEY (categoryname_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E16912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7332E16987CCB12E ON services (categoryname_id)');
        $this->addSql('CREATE INDEX IDX_7332E16912469DE2 ON services (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E16987CCB12E');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E16912469DE2');
        $this->addSql('DROP INDEX IDX_7332E16987CCB12E ON services');
        $this->addSql('DROP INDEX IDX_7332E16912469DE2 ON services');
        $this->addSql('ALTER TABLE services DROP category_id, CHANGE categoryname_id categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1699C370B71 FOREIGN KEY (categoryId) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7332E1699C370B71 ON services (categoryId)');
    }
}
