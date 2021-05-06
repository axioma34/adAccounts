<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505053459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_account (user_id INT NOT NULL, account_id INT NOT NULL, PRIMARY KEY(user_id, account_id))');
        $this->addSql('CREATE INDEX IDX_253B48AEA76ED395 ON user_account (user_id)');
        $this->addSql('CREATE INDEX IDX_253B48AE9B6B5FBA ON user_account (account_id)');
        $this->addSql('CREATE TABLE user_group (id INT NOT NULL, system_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users_groups (user_id INT NOT NULL, user_group_id INT NOT NULL, account_id INT NOT NULL, PRIMARY KEY(user_id, user_group_id, account_id))');
        $this->addSql('CREATE INDEX IDX_FF8AB7E0A76ED395 ON users_groups (user_id)');
        $this->addSql('CREATE INDEX IDX_FF8AB7E01ED93D47 ON users_groups (user_group_id)');
        $this->addSql('CREATE INDEX IDX_FF8AB7E09B6B5FBA ON users_groups (account_id)');
        $this->addSql('ALTER TABLE user_account ADD CONSTRAINT FK_253B48AEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE9B6B5FBA FOREIGN KEY (account_id) REFERENCES ad_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E01ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E09B6B5FBA FOREIGN KEY (account_id) REFERENCES ad_account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT FK_FF8AB7E01ED93D47');
        $this->addSql('DROP SEQUENCE user_group_id_seq CASCADE');
        $this->addSql('DROP TABLE user_account');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE users_groups');
    }
}
