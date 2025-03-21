<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321053143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE billing_item_reference (uid VARCHAR(36) NOT NULL, title VARCHAR(255) NOT NULL, is_root TINYINT(1) DEFAULT NULL, PRIMARY KEY(uid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collector (id INT AUTO_INCREMENT NOT NULL, user_billing_item_id INT NOT NULL, tariff_id INT NOT NULL, uid VARCHAR(36) NOT NULL, INDEX IDX_CEDBF54C6B9DB506 (user_billing_item_id), INDEX IDX_CEDBF54C92348FD2 (tariff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collector_data (id INT AUTO_INCREMENT NOT NULL, collector_id INT NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, timestamp INT NOT NULL, INDEX IDX_8C8F0B4670BAFFE (collector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usertariff (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tariff_reference (uid VARCHAR(36) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(uid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_billing_data (id INT AUTO_INCREMENT NOT NULL, user_billing_object_id INT NOT NULL, repository_billing_item_id VARCHAR(36) NOT NULL, INDEX IDX_524DB1A66F218F5E (user_billing_object_id), INDEX IDX_524DB1A65B7C0BB7 (repository_billing_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_billing_object (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C6B9DB506 FOREIGN KEY (user_billing_item_id) REFERENCES user_billing_data (id)');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C92348FD2 FOREIGN KEY (tariff_id) REFERENCES usertariff (id)');
        $this->addSql('ALTER TABLE collector_data ADD CONSTRAINT FK_8C8F0B4670BAFFE FOREIGN KEY (collector_id) REFERENCES collector (id)');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A66F218F5E FOREIGN KEY (user_billing_object_id) REFERENCES user_billing_object (id)');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A65B7C0BB7 FOREIGN KEY (repository_billing_item_id) REFERENCES billing_item_reference (uid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C6B9DB506');
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C92348FD2');
        $this->addSql('ALTER TABLE collector_data DROP FOREIGN KEY FK_8C8F0B4670BAFFE');
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A66F218F5E');
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A65B7C0BB7');
        $this->addSql('DROP TABLE billing_item_reference');
        $this->addSql('DROP TABLE collector');
        $this->addSql('DROP TABLE collector_data');
        $this->addSql('DROP TABLE usertariff');
        $this->addSql('DROP TABLE tariff_reference');
        $this->addSql('DROP TABLE user_billing_data');
        $this->addSql('DROP TABLE user_billing_object');
    }
}
