<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Tests\Functional;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Tests\FunctionalTestCase;
use Doctrine\DBAL\Types\Types;

class AutoIncrementColumnTest extends FunctionalTestCase
{
    private bool $shouldDisableIdentityInsert = false;

    /** @throws Exception */
    protected function setUp(): void
    {
        $table = new Table('auto_increment_table');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $table->addColumn('val', Types::INTEGER);
        $table->setPrimaryKey(['id']);

        $this->dropAndCreateTable($table);
    }

    /** @throws Exception */
    protected function tearDown(): void
    {
        if (! $this->shouldDisableIdentityInsert) {
            return;
        }

        $this->setIdentityInsert('OFF');
    }

    /** @throws Exception */
    public function testInsertAutoGeneratesValue(): void
    {
        $this->connection->insert('auto_increment_table', ['val' => 0]);
        self::assertEquals(1, $this->connection->fetchOne('SELECT MAX(id) FROM auto_increment_table'));
    }

    /** @throws Exception */
    public function testInsertIdentityValue(): void
    {
        $platform    = $this->connection->getDatabasePlatform();
        $isSQLServer = $platform instanceof SQLServerPlatform;

        if ($isSQLServer) {
            $this->setIdentityInsert('ON');
            $this->shouldDisableIdentityInsert = true;
        }

        $this->connection->insert('auto_increment_table', ['id' => 2, 'val' => 0]);
        self::assertEquals(2, $this->connection->fetchOne('SELECT MAX(id) FROM auto_increment_table'));

        if ($isSQLServer) {
            $this->setIdentityInsert('OFF');
            $this->shouldDisableIdentityInsert = false;
        }

        // using an explicit value for an autoincrement column does not affect the next value
        // on the following platforms
        if ($platform instanceof PostgreSqlPlatform || $platform instanceof DB2Platform) {
            return;
        }

        $this->connection->insert('auto_increment_table', ['val' => 0]);
        self::assertEquals(3, $this->connection->fetchOne('SELECT MAX(id) FROM auto_increment_table'));
    }

    /** @throws Exception */
    private function setIdentityInsert(string $value): void
    {
        $this->connection->executeStatement('SET IDENTITY_INSERT auto_increment_table ' . $value);
    }
}
