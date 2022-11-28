<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221002161104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, prices INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vouchercode (id INT AUTO_INCREMENT NOT NULL, v_id INT DEFAULT NULL, user_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_8C8F9723F6103C7E (v_id), INDEX IDX_8C8F9723A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vouchercode ADD CONSTRAINT FK_8C8F9723F6103C7E FOREIGN KEY (v_id) REFERENCES voucher (id)');
        $this->addSql('ALTER TABLE vouchercode ADD CONSTRAINT FK_8C8F9723A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orderdoc CHANGE status status TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vouchercode DROP FOREIGN KEY FK_8C8F9723F6103C7E');
        $this->addSql('DROP TABLE voucher');
        $this->addSql('DROP TABLE vouchercode');
        $this->addSql('ALTER TABLE orderdoc CHANGE status status TEXT NOT NULL');
    }
}
