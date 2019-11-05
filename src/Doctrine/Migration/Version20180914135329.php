<?php declare(strict_types = 1);

namespace App\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20180914135329 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_message CHANGE date_created date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user CHANGE date_created date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user_auth_log CHANGE date_created date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user_review CHANGE date_created date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user_token CHANGE date_created date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_message CHANGE date_created date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user CHANGE date_created date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user_auth_log CHANGE date_created date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user_review CHANGE date_created date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
        $this->addSql('ALTER TABLE user_token CHANGE date_created date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\'');
    }

}
