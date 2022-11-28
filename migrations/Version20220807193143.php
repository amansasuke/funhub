<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220807193143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E1699777D11E');
        $this->addSql('DROP INDEX IDX_7332E1699777D11E ON services');
        $this->addSql('ALTER TABLE services CHANGE category_id_id categoryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1699C370B71 FOREIGN KEY (categoryId) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7332E1699C370B71 ON services (categoryId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E1699C370B71');
        $this->addSql('DROP INDEX IDX_7332E1699C370B71 ON services');
        $this->addSql('ALTER TABLE services CHANGE categoryId category_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1699777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_7332E1699777D11E ON services (category_id_id)');
    }
}
