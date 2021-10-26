<?php

namespace Georgechem\SqliteDb\Model;

use PHPUnit\Framework\TestCase;

/**
 * @covers Storage
 */
class StorageTest extends TestCase
{
    private Storage $storage;

    /**
     * Prepare environment for tests
     */
    public function setUp():void
    {
        parent::setUp();
        $this->storage = new Storage();

    }

    public function testStorageIsProperlyInstantiated()
    {
        self::assertInstanceOf(Storage::class, $this->storage, 'is proper instance');
    }

    public function testIsStorageCreated()
    {
        $result = $this->storage->create();
        self::assertEquals(true, $result, 'true if storage created successfully or already exists');

    }

    public function testSaveIntoStorage()
    {
        $result = $this->storage->save('key', 'value', false);
        self::assertTrue($result);
        $result = $this->storage->save('key', 'value', false);
        self::assertFalse($result);
        $result = $this->storage->save('key', 'value', true);
        self::assertTrue($result);
    }

    public function testIsStorageDestroyed()
    {
        $result = $this->storage->destroy();
        self::assertEquals(true, $result, 'true if store destroyed successfully');
    }


    /**
     * Recreate Storage after tests
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $result = $this->storage->create();
        //self::assertTrue($result);
    }
}