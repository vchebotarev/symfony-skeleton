<?php declare(strict_types = 1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20180914155035 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user_social CHANGE data data JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user_token CHANGE data data JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE user_social CHANGE data data JSON NOT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE user_token CHANGE data data JSON NOT NULL COMMENT \'(DC2Type:json_array)\'');
    }

}
