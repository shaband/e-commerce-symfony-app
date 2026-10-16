<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260918233847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image ADD product_variant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F03A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
        $this->addSql('CREATE INDEX IDX_64617F03A80EF684 ON product_image (product_variant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image DROP CONSTRAINT FK_64617F03A80EF684');
        $this->addSql('DROP INDEX IDX_64617F03A80EF684');
        $this->addSql('ALTER TABLE product_image DROP product_variant_id');
    }
}
