<?php declare(strict_types = 1);

namespace App\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171201110000 extends AbstractMigration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE ticket (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id INT NOT NULL, 
                status SMALLINT DEFAULT 0 NOT NULL, 
                type SMALLINT DEFAULT 0 NOT NULL, 
                name VARCHAR(255) DEFAULT \'\' NOT NULL, 
                INDEX IDX_97A0ADA3A76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE ticket_message (
                id INT AUTO_INCREMENT NOT NULL, 
                ticket_id INT NOT NULL, 
                user_id INT NOT NULL, 
                is_read TINYINT(1) DEFAULT \'0\' NOT NULL, 
                body LONGTEXT NOT NULL, 
                date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\', 
                INDEX IDX_BA71692D700047D2 (ticket_id), 
                INDEX IDX_BA71692DA76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket_message ADD CONSTRAINT FK_BA71692D700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket_message ADD CONSTRAINT FK_BA71692DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket_message DROP FOREIGN KEY FK_BA71692D700047D2');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_message');
    }

}
