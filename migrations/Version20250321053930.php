<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321053930 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A65B7C0BB7');
        $this->addSql('DROP INDEX IDX_524DB1A65B7C0BB7 ON user_billing_data');
        $this->addSql('ALTER TABLE user_billing_data CHANGE repository_billing_item_id referenced VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A61CCDA85B FOREIGN KEY (referenced) REFERENCES billing_item_reference (uid)');
        $this->addSql('CREATE INDEX IDX_524DB1A61CCDA85B ON user_billing_data (referenced)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A61CCDA85B');
        $this->addSql('DROP INDEX IDX_524DB1A61CCDA85B ON user_billing_data');
        $this->addSql('ALTER TABLE user_billing_data CHANGE referenced repository_billing_item_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A65B7C0BB7 FOREIGN KEY (repository_billing_item_id) REFERENCES billing_item_reference (uid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_524DB1A65B7C0BB7 ON user_billing_data (repository_billing_item_id)');
        $this->addSql('ALTER TABLE usertariff DROP FOREIGN KEY FK_9465207DAEA34913');
        $this->addSql('DROP INDEX IDX_9465207DAEA34913 ON usertariff');
        $this->addSql('ALTER TABLE usertariff DROP reference');
    }
}
