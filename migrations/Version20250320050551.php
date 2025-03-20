<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320050551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_object DROP FOREIGN KEY FK_86F257E55087349D');
        $this->addSql('DROP INDEX IDX_86F257E55087349D ON user_billing_object');
        $this->addSql('ALTER TABLE user_billing_object DROP repository_billing_item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_object ADD repository_billing_item VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_billing_object ADD CONSTRAINT FK_86F257E55087349D FOREIGN KEY (repository_billing_item) REFERENCES billing_repository_item (uid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_86F257E55087349D ON user_billing_object (repository_billing_item)');
    }
}
