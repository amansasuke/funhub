<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501102320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliateproduct ADD orderinfo_id INT DEFAULT NULL, ADD commissionpaid VARCHAR(255) DEFAULT NULL, ADD paymentdate DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE affiliateproduct ADD CONSTRAINT FK_A5A9D3D4673736AE FOREIGN KEY (orderinfo_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_A5A9D3D4673736AE ON affiliateproduct (orderinfo_id)');
        $this->addSql('ALTER TABLE orders CHANGE createat createat DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliateproduct DROP FOREIGN KEY FK_A5A9D3D4673736AE');
        $this->addSql('DROP INDEX IDX_A5A9D3D4673736AE ON affiliateproduct');
        $this->addSql('ALTER TABLE affiliateproduct DROP orderinfo_id, DROP commissionpaid, DROP paymentdate');
        $this->addSql('ALTER TABLE orders CHANGE createat createat DATE DEFAULT NULL');
    }
}
