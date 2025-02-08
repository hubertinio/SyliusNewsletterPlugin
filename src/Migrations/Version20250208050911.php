<?php

declare(strict_types=1);

namespace Dotit\SyliusNewsletterPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250208050911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table dotit_newsletter_subscriptions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE dotit_newsletter_subscriptions (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, shopUser_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_DAD11BFC14422B08 (shopUser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dotit_newsletter_subscriptions ADD CONSTRAINT FK_DAD11BFC14422B08 FOREIGN KEY (shopUser_id) REFERENCES sylius_shop_user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dotit_newsletter_subscriptions DROP FOREIGN KEY FK_DAD11BFC14422B08');
        $this->addSql('DROP TABLE dotit_newsletter_subscriptions');
    }
}
