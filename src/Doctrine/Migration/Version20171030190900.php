<?php declare(strict_types = 1);

namespace App\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20171030190900 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE chat (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) DEFAULT \'\' NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE chat_message (
                id INT AUTO_INCREMENT NOT NULL, 
                chat_id INT NOT NULL, 
                user_id INT NOT NULL, 
                body LONGTEXT NOT NULL, 
                date_created DATETIME NOT NULL, 
                INDEX IDX_FAB3FC161A9A7125 (chat_id), 
                INDEX IDX_FAB3FC16A76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE chat_message_user (
                id INT AUTO_INCREMENT NOT NULL, 
                message_id INT NOT NULL, 
                user_id INT NOT NULL, 
                is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, 
                is_read TINYINT(1) DEFAULT \'0\' NOT NULL, 
                INDEX IDX_DAED2411537A1329 (message_id), 
                INDEX IDX_DAED2411A76ED395 (user_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE chat_user (
                id INT AUTO_INCREMENT NOT NULL, 
                chat_id INT NOT NULL, 
                user_id INT NOT NULL, 
                INDEX IDX_2B0F4B081A9A7125 (chat_id), 
                INDEX IDX_2B0F4B08A76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC161A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message_user ADD CONSTRAINT FK_DAED2411537A1329 FOREIGN KEY (message_id) REFERENCES chat_message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message_user ADD CONSTRAINT FK_DAED2411A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B081A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B08A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC161A9A7125');
        $this->addSql('ALTER TABLE chat_user DROP FOREIGN KEY FK_2B0F4B081A9A7125');
        $this->addSql('ALTER TABLE chat_message_user DROP FOREIGN KEY FK_DAED2411537A1329');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP TABLE chat_message_user');
        $this->addSql('DROP TABLE chat_user');
    }

}
