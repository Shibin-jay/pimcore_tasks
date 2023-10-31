<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027110641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $customLogsTable = $schema->createTable('custom_logs');
        $customLogsTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $customLogsTable->addColumn('timeStamp', 'datetime', ['notnull' => false]);
        $customLogsTable->addColumn('route', 'string', ['length' => 255, 'notnull' => false]);
        $customLogsTable->addColumn('userId', 'string', ['length' => 255, 'notnull' => false]);
        $customLogsTable->addColumn('logLevel', 'string', ['length' => 255, 'notnull' => false]);
        $customLogsTable->addColumn('controller', 'string', ['length' => 255, 'notnull' => false]);
        $customLogsTable->addColumn('routeParams', 'text', ['notnull' => false]);
        $customLogsTable->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable("custom_logs");

    }
}
