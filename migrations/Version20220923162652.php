<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220923162652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assign_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assign_group_user (assign_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E04E051A88817250 (assign_group_id), INDEX IDX_E04E051AA76ED395 (user_id), PRIMARY KEY(assign_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, userid INT NOT NULL, starttime DATE NOT NULL, endtime DATE NOT NULL, startslot VARCHAR(255) NOT NULL, start_slot TIME NOT NULL, start_slot_m TIME NOT NULL, end_slot_m TIME NOT NULL, manget_statu VARCHAR(255) NOT NULL, clintstarttime DATE NOT NULL, clint_endtime DATE NOT NULL, clint_start_slot TIME NOT NULL, clint_end_slot TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE docofpro (id INT AUTO_INCREMENT NOT NULL, docid INT DEFAULT NULL, proid INT DEFAULT NULL, INDEX docid (docid), INDEX proid (proid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_assign_group (user_group_id INT NOT NULL, assign_group_id INT NOT NULL, INDEX IDX_55C3E0BC1ED93D47 (user_group_id), INDEX IDX_55C3E0BC88817250 (assign_group_id), PRIMARY KEY(user_group_id, assign_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_user (user_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3AE4BD51ED93D47 (user_group_id), INDEX IDX_3AE4BD5A76ED395 (user_id), PRIMARY KEY(user_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assign_group_user ADD CONSTRAINT FK_E04E051A88817250 FOREIGN KEY (assign_group_id) REFERENCES assign_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assign_group_user ADD CONSTRAINT FK_E04E051AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE docofpro ADD CONSTRAINT FK_53C7777E7DA4C113 FOREIGN KEY (docid) REFERENCES doctype (id)');
        $this->addSql('ALTER TABLE docofpro ADD CONSTRAINT FK_53C7777E43AD8677 FOREIGN KEY (proid) REFERENCES product (id)');
        $this->addSql('ALTER TABLE user_group_assign_group ADD CONSTRAINT FK_55C3E0BC1ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_assign_group ADD CONSTRAINT FK_55C3E0BC88817250 FOREIGN KEY (assign_group_id) REFERENCES assign_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_3AE4BD51ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_3AE4BD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product CHANGE service_id service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone_no phone_no VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assign_group_user DROP FOREIGN KEY FK_E04E051A88817250');
        $this->addSql('ALTER TABLE user_group_assign_group DROP FOREIGN KEY FK_55C3E0BC88817250');
        $this->addSql('ALTER TABLE user_group_assign_group DROP FOREIGN KEY FK_55C3E0BC1ED93D47');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_3AE4BD51ED93D47');
        $this->addSql('DROP TABLE assign_group');
        $this->addSql('DROP TABLE assign_group_user');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE docofpro');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_group_assign_group');
        $this->addSql('DROP TABLE user_group_user');
        $this->addSql('ALTER TABLE product CHANGE service_id service_id INT NOT NULL');
        $this->addSql('ALTER TABLE services CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone_no phone_no INT NOT NULL');
    }
}
