<?php declare(strict_types = 1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20171207115621 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE user_review (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id INT NOT NULL, 
                user_created_id INT NOT NULL, 
                type SMALLINT DEFAULT 0 NOT NULL, 
                body LONGTEXT NOT NULL, 
                date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\', 
                INDEX IDX_1C119AFBF987D8A8 (user_created_id), 
                INDEX IDX_1C119AFBA76ED395 (user_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFBF987D8A8 FOREIGN KEY (user_created_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_review');
    }

}
