<?php declare(strict_types = 1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20170830090408 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL,
                username VARCHAR(255) DEFAULT \'\' NOT NULL,
                email VARCHAR(255) DEFAULT \'\' NOT NULL,
                is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL,
                is_locked TINYINT(1) DEFAULT \'0\' NOT NULL,
                password VARCHAR(255) DEFAULT \'\' NOT NULL,
                roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', 
                date_created DATETIME NOT NULL,
                fio_f VARCHAR(255) DEFAULT \'\' NOT NULL,
                fio_i VARCHAR(255) DEFAULT \'\' NOT NULL,
                fio_o VARCHAR(255) DEFAULT \'\' NOT NULL,
                gender SMALLINT DEFAULT 0 NOT NULL,
                date_birthday DATE DEFAULT NULL,
                UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
                UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
    }

}
