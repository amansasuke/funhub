<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823190137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orderdoc (id INT AUTO_INCREMENT NOT NULL, docname VARCHAR(255) NOT NULL, doclink VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orderdoc_order (orderdoc_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_55F8591F80F0D0DD (orderdoc_id), INDEX IDX_55F8591F8D9F6D38 (order_id), PRIMARY KEY(orderdoc_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orderdoc_order ADD CONSTRAINT FK_55F8591F80F0D0DD FOREIGN KEY (orderdoc_id) REFERENCES orderdoc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orderdoc_order ADD CONSTRAINT FK_55F8591F8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orderdoc_order DROP FOREIGN KEY FK_55F8591F80F0D0DD');
        $this->addSql('DROP TABLE orderdoc');
        $this->addSql('DROP TABLE orderdoc_order');
    }
}
