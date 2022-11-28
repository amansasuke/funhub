<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221009095028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affiliateproduct (id INT AUTO_INCREMENT NOT NULL, affiliateid_id INT DEFAULT NULL, productname VARCHAR(255) NOT NULL, servicename VARCHAR(255) NOT NULL, productprice VARCHAR(255) NOT NULL, affiliateprice VARCHAR(255) NOT NULL, affiliateuserid INT NOT NULL, orderuserid INT NOT NULL, INDEX IDX_A5A9D3D45CAB03F1 (affiliateid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affiliateproduct ADD CONSTRAINT FK_A5A9D3D45CAB03F1 FOREIGN KEY (affiliateid_id) REFERENCES affiliate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE affiliateproduct');
    }
}
