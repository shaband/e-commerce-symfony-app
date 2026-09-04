<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260829181025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD github_id TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD google_id TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP github_id');
        $this->addSql('ALTER TABLE "user" DROP google_id');
        $this->addSql('ALTER TABLE "user" ALTER password SET NOT NULL');
    }
}
