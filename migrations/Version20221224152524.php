<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221224152524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD full_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD birth_date DATE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD mobile INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD addres VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP full_name');
        $this->addSql('ALTER TABLE "user" DROP birth_date');
        $this->addSql('ALTER TABLE "user" DROP mobile');
        $this->addSql('ALTER TABLE "user" DROP addres');
    }
}
