<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822162353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, address LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_2530ADE68D9F6D38 (order_id), INDEX IDX_2530ADE64584665A (product_id), PRIMARY KEY(order_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assigndoc DROP FOREIGN KEY FK_F45E8C054584665A');
        $this->addSql('DROP INDEX idx_f45e8c054584665a ON assigndoc');
        $this->addSql('CREATE INDEX IDX_76AF0EA64584665A ON assigndoc (product_id)');
        $this->addSql('ALTER TABLE assigndoc ADD CONSTRAINT FK_F45E8C054584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE68D9F6D38');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('ALTER TABLE Assigndoc DROP FOREIGN KEY FK_76AF0EA64584665A');
        $this->addSql('DROP INDEX idx_76af0ea64584665a ON Assigndoc');
        $this->addSql('CREATE INDEX IDX_F45E8C054584665A ON Assigndoc (product_id)');
        $this->addSql('ALTER TABLE Assigndoc ADD CONSTRAINT FK_76AF0EA64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }
}
