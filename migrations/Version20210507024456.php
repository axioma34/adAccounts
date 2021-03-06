<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507024456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO public."user" (id, email, roles, password, name, active, api_token)
         VALUES (777, \'admin@mail.ru\', \'["ROLE_ADMIN"]\',
         \'$argon2id$v=19$m=65536,t=4,p=1$6+y2e46u2v2ixDomI2crMA$Fcnv1BhR6V8OnXr2H6pKyeDJPim3fK/8t4F8ewFzOcY\',
         \'admin\', true, \'admin\')');
        $this->addSql('INSERT INTO public.user_group (id, system_id, code) VALUES (1, 1, \'EDIT\');');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
