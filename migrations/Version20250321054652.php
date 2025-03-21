<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321054652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C6B9DB506');
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C92348FD2');
        $this->addSql('CREATE TABLE user_tariff (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(36) NOT NULL, INDEX IDX_F1373B40AEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE userbilingitem (id INT AUTO_INCREMENT NOT NULL, user_billing_object_id INT NOT NULL, reference VARCHAR(36) NOT NULL, INDEX IDX_35FD248A6F218F5E (user_billing_object_id), INDEX IDX_35FD248AAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_tariff ADD CONSTRAINT FK_F1373B40AEA34913 FOREIGN KEY (reference) REFERENCES tariff_reference (uid)');
        $this->addSql('ALTER TABLE userbilingitem ADD CONSTRAINT FK_35FD248A6F218F5E FOREIGN KEY (user_billing_object_id) REFERENCES user_billing_object (id)');
        $this->addSql('ALTER TABLE userbilingitem ADD CONSTRAINT FK_35FD248AAEA34913 FOREIGN KEY (reference) REFERENCES billing_item_reference (uid)');
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A61CCDA85B');
        $this->addSql('ALTER TABLE user_billing_data DROP FOREIGN KEY FK_524DB1A66F218F5E');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207DAEA34913');
        $this->addSql('DROP TABLE user_billing_data');
        $this->addSql('DROP TABLE tariff');
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C6B9DB506');
        $this->addSql('DROP INDEX IDX_CEDBF54C92348FD2 ON collector');
        $this->addSql('ALTER TABLE collector CHANGE tariff_id usertariff_id INT NOT NULL');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C5C792EB2 FOREIGN KEY (usertariff_id) REFERENCES user_tariff (id)');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C6B9DB506 FOREIGN KEY (user_billing_item_id) REFERENCES userbilingitem (id)');
        $this->addSql('CREATE INDEX IDX_CEDBF54C5C792EB2 ON collector (usertariff_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C5C792EB2');
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C6B9DB506');
        $this->addSql('CREATE TABLE user_billing_data (id INT AUTO_INCREMENT NOT NULL, user_billing_object_id INT NOT NULL, referenced VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_524DB1A66F218F5E (user_billing_object_id), INDEX IDX_524DB1A61CCDA85B (referenced), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tariff (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9465207DAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A61CCDA85B FOREIGN KEY (referenced) REFERENCES billing_item_reference (uid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_billing_data ADD CONSTRAINT FK_524DB1A66F218F5E FOREIGN KEY (user_billing_object_id) REFERENCES user_billing_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207DAEA34913 FOREIGN KEY (reference) REFERENCES tariff_reference (uid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_tariff DROP FOREIGN KEY FK_F1373B40AEA34913');
        $this->addSql('ALTER TABLE userbilingitem DROP FOREIGN KEY FK_35FD248A6F218F5E');
        $this->addSql('ALTER TABLE userbilingitem DROP FOREIGN KEY FK_35FD248AAEA34913');
        $this->addSql('DROP TABLE user_tariff');
        $this->addSql('DROP TABLE userbilingitem');
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C6B9DB506');
        $this->addSql('DROP INDEX IDX_CEDBF54C5C792EB2 ON collector');
        $this->addSql('ALTER TABLE collector CHANGE usertariff_id tariff_id INT NOT NULL');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C92348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C6B9DB506 FOREIGN KEY (user_billing_item_id) REFERENCES user_billing_data (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CEDBF54C92348FD2 ON collector (tariff_id)');
    }
}
