<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004190717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uservoucher (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uservoucher_voucher (uservoucher_id INT NOT NULL, voucher_id INT NOT NULL, INDEX IDX_67A30D0965D63796 (uservoucher_id), INDEX IDX_67A30D0928AA1B6F (voucher_id), PRIMARY KEY(uservoucher_id, voucher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uservoucher_user (uservoucher_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7B582D9A65D63796 (uservoucher_id), INDEX IDX_7B582D9AA76ED395 (user_id), PRIMARY KEY(uservoucher_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE uservoucher_voucher ADD CONSTRAINT FK_67A30D0965D63796 FOREIGN KEY (uservoucher_id) REFERENCES uservoucher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uservoucher_voucher ADD CONSTRAINT FK_67A30D0928AA1B6F FOREIGN KEY (voucher_id) REFERENCES voucher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uservoucher_user ADD CONSTRAINT FK_7B582D9A65D63796 FOREIGN KEY (uservoucher_id) REFERENCES uservoucher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uservoucher_user ADD CONSTRAINT FK_7B582D9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uservoucher_voucher DROP FOREIGN KEY FK_67A30D0965D63796');
        $this->addSql('ALTER TABLE uservoucher_user DROP FOREIGN KEY FK_7B582D9A65D63796');
        $this->addSql('DROP TABLE uservoucher');
        $this->addSql('DROP TABLE uservoucher_voucher');
        $this->addSql('DROP TABLE uservoucher_user');
    }
}
