<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030115403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('custom_activity_logs');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('admin_user_id', 'integer');
        $table->addColumn('action', 'string', ['length' => 255]);
        $table->addColumn('timestamp', 'datetime');
        $table->addColumn('controller', 'string', ['length' => 255, 'notnull' => false]);
        $table->setPrimaryKey(['id']);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable("custom_activity_logs");
    }
}
