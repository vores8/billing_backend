<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319164327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A65087349D');
        $this->addSql('DROP INDEX IDX_524DB1A65087349D ON user_billing_data');
        $this->addSql('ALTER TABLE user_billing_data CHANGE repository_billing_item repository_billing_item_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A65B7C0BB7 FOREIGN KEY (repository_billing_item_id) REFERENCES billing_repository_item (uid)');
        $this->addSql('CREATE INDEX IDX_524DB1A65B7C0BB7 ON user_billing_data (repository_billing_item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A65B7C0BB7');
        $this->addSql('DROP INDEX IDX_524DB1A65B7C0BB7 ON user_billing_data');
        $this->addSql('ALTER TABLE user_billing_data CHANGE repository_billing_item_id repository_billing_item VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A65087349D FOREIGN KEY (repository_billing_item) REFERENCES billing_repository_item (uid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_524DB1A65087349D ON user_billing_data (repository_billing_item)');
    }
}
