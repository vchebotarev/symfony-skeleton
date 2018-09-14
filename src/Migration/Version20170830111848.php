<?php declare(strict_types = 1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20170830111848 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE user_auth_log (
                id INT AUTO_INCREMENT NOT NULL, 
                type SMALLINT DEFAULT 0 NOT NULL, 
                user_id INT NOT NULL, 
                visitor_id INT NOT NULL, 
                user_agent_id INT NOT NULL, 
                ip BIGINT DEFAULT 0 NOT NULL, 
                date_created DATETIME NOT NULL, 
                INDEX IDX_FD5FB9B8A76ED395 (user_id), 
                INDEX IDX_FD5FB9B870BEE6D (visitor_id), 
                INDEX IDX_FD5FB9B8D499950B (user_agent_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE user_auth_log ADD CONSTRAINT FK_FD5FB9B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_auth_log ADD CONSTRAINT FK_FD5FB9B870BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_auth_log ADD CONSTRAINT FK_FD5FB9B8D499950B FOREIGN KEY (user_agent_id) REFERENCES user_agent (id) ON DELETE CASCADE');
    }

    /**
     * @inheritDoc
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_auth_log');
    }

}
