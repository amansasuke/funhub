<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806185952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD pan_no VARCHAR(255) NOT NULL, ADD phone_no INT NOT NULL, ADD gender VARCHAR(255) NOT NULL, ADD user_category VARCHAR(255) NOT NULL, ADD red_id INT NOT NULL, ADD wellet DOUBLE PRECISION NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP name, DROP address, DROP pan_no, DROP phone_no, DROP gender, DROP user_category, DROP red_id, DROP wellet, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
