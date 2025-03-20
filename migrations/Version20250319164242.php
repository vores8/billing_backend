<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319164242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_data ADD repository_billing_item VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A65087349D FOREIGN KEY (repository_billing_item) REFERENCES billing_repository_item (uid)');
        $this->addSql('CREATE INDEX IDX_524DB1A65087349D ON user_billing_data (repository_billing_item)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A65087349D');
        $this->addSql('DROP INDEX IDX_524DB1A65087349D ON user_billing_data');
        $this->addSql('ALTER TABLE user_billing_data DROP repository_billing_item');
    }
}
