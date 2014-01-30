<?php
use Codeception\Util\Stub;

class storageTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testStorageKey()
    {
        $storage = new Storage('user');

        $this->assertTrue($storage->type == 'user');
        $this->assertNotNull($storage->type);
    }

    public function testSetStorage()
    {
        $storage = new Storage('user');

        $this->assertTrue($storage->type == 'user');
        $this->assertNotNull($storage->type);
        
        $output = $storage->set('apple', 'green');

        $this->assertNotEmpty($output);

        $this->assertTrue(!!$output['user']['apple']);

        $this->assertTrue($output['user']['apple'] == 'green');
    }

    public function testGetStorage()
    {
        $storage = new Storage('user');

        $storage->set('apple', 'green');

        $output = $storage->get();

        $this->assertTrue(!!$output['apple']);
        $this->assertTrue($output['apple'] == 'green');
    }

}