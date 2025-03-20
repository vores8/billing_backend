<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319162205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_billing_object (id INT AUTO_INCREMENT NOT NULL, repository_billing_item VARCHAR(36) DEFAULT NULL, rep_id VARCHAR(255) NOT NULL, INDEX IDX_86F257E55087349D (repository_billing_item), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_billing_object ADD CONSTRAINT FK_86F257E55087349D FOREIGN KEY (repository_billing_item) REFERENCES billing_repository_item (uid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_object DROP FOREIGN KEY FK_86F257E55087349D');
        $this->addSql('DROP TABLE user_billing_object');
    }
}
