<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110151910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eventbooking (id INT AUTO_INCREMENT NOT NULL, manger_id INT DEFAULT NULL, dis VARCHAR(255) NOT NULL, startdate VARCHAR(255) DEFAULT NULL, bookingstart DATE NOT NULL, bookingtime TIME NOT NULL, duration VARCHAR(255) NOT NULL, userid INT NOT NULL, usermail VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_2BA67D0F90F8562A (manger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eventbooking ADD CONSTRAINT FK_2BA67D0F90F8562A FOREIGN KEY (manger_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE eventbooking');
    }
}
