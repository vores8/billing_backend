<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321053850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usertariff ADD reference VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE usertariff ADD CONSTRAINT FK_9465207DAEA34913 FOREIGN KEY (reference) REFERENCES tariff_reference (uid)');
        $this->addSql('CREATE INDEX IDX_9465207DAEA34913 ON usertariff (reference)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usertariff DROP FOREIGN KEY FK_9465207DAEA34913');
        $this->addSql('DROP INDEX IDX_9465207DAEA34913 ON usertariff');
        $this->addSql('ALTER TABLE usertariff DROP reference');
    }
}
