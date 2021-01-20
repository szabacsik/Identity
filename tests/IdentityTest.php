<?php
declare(strict_types=1);

namespace Szabacsik\Identity\Test;

use PHPUnit\Framework\TestCase;
use Szabacsik\Identity\Identity;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class IdentityTest extends TestCase
{
    public function testCreateNew(): Identity
    {
        $identity = Identity::create();
        $this->assertInstanceOf('Szabacsik\Identity\Identity', $identity);
        return $identity;
    }

    /**
     * @depends testCreateNew
     * @param Identity $identity
     */
    public function testValidUuidString(Identity $identity)
    {
        $this->assertIsString((string)$identity);
        $re = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
        $this->assertRegExp($re,(string)$identity);
    }

    public function testCreateSucceedFromValidString()
    {
        $identity = Identity::create('7092b32b-7638-48fa-b2f5-48c4938c9b7d');
        $this->assertInstanceOf('Szabacsik\Identity\Identity', $identity);
    }

    public function testCreateFailFromInvalidString()
    {
        $this->expectException(\Exception::class);
        Identity::create('Lorem Ipsum');
    }

    public function testUniqueness()
    {
        $buffer = [];
        $count = 10000;
        for ($i = 0; $i < $count; $i++) {
            $uuid = (string)Identity::create();
            in_array($uuid, $buffer) || $buffer[] = $uuid;
        }
        $this->assertCount($count, $buffer);
    }

    public function testValidator()
    {
        $this->assertFalse(Identity::isValid('lorem ipsum'));
        $this->assertFalse(Identity::isValid('d16064f0-a375-11ea-bb37-0242ac130002')); //Version 1 UUID
        $this->assertFalse(Identity::isValid('0f72cc5b-4db6-31c9-b787-39cc620340c1')); //Version 3 UUID
        $this->assertTrue(Identity::isValid('e92b152a-6ad9-45a1-a4e0-78ea44197c17'));  //Version 4 UUID
    }
}