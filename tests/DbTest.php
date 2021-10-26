<?php
declare(strict_types=1);

namespace Georgechem\SqliteDb\Model;

use PHPUnit\Framework\TestCase;

/**
 * @covers Db
 */
class DbTest extends TestCase
{
    private ?\PDO $conn;

    public function setUp():void
    {
        $this->conn = Db::getConnection();
    }

    public function testIsConnectionAvailable()
    {
        self::assertInstanceOf(\PDO::class, $this->conn, 'PDO object is available');
    }

    public function tearDown():void
    {
        $this->conn = null;
    }

}