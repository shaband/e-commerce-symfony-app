<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260824214431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD show_children_in_home BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE category ADD show_products_in_home BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE category ALTER category_group DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP show_children_in_home');
        $this->addSql('ALTER TABLE category DROP show_products_in_home');
        $this->addSql('ALTER TABLE category ALTER category_group SET DEFAULT \'Main\'');
    }
}
