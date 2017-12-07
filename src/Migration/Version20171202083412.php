<?php declare(strict_types = 1);

namespace App\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171202083412 extends AbstractMigration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE lot (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                status SMALLINT DEFAULT 0 NOT NULL,
                name VARCHAR(255) DEFAULT \'\' NOT NULL,
                body LONGTEXT NOT NULL,
                price_start NUMERIC(15, 2) DEFAULT \'0\' NOT NULL,
                price_blitz NUMERIC(15, 2) DEFAULT \'0\' NOT NULL,
                bet_last_id INT DEFAULT NULL,
                date_started TIMESTAMP DEFAULT NULL COMMENT \'(DC2Type:datetimetz)\',
                date_finished TIMESTAMP DEFAULT NULL COMMENT \'(DC2Type:datetimetz)\',
                date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\',
                INDEX IDX_B81291BA76ED395 (user_id),
                INDEX IDX_B81291B3929690A (bet_last_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE lot_bet (
                id INT AUTO_INCREMENT NOT NULL,
                lot_id INT NOT NULL,
                user_id INT NOT NULL,
                price NUMERIC(15, 2) DEFAULT \'0\' NOT NULL,
                date_created TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetimetz)\',
                INDEX IDX_B3552812A8CBA5F7 (lot_id),
                INDEX IDX_B3552812A76ED395 (user_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B3929690A FOREIGN KEY (bet_last_id) REFERENCES lot_bet (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lot_bet ADD CONSTRAINT FK_B3552812A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lot_bet ADD CONSTRAINT FK_B3552812A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lot_bet DROP FOREIGN KEY FK_B3552812A8CBA5F7');
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291B3929690A');
        $this->addSql('DROP TABLE lot');
        $this->addSql('DROP TABLE lot_bet');
    }

}
