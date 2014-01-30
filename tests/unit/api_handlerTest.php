<?php
use Codeception\Util\Stub;

class api_handlerTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    protected function _after()
    {
    }

    public function testGetall()
    {
        $_SERVER['REQUEST_URI'] = 'api/users/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $api = new API_handler();

        $this->assertNotNull($api);

        $api->call();
    }

    public function testGetById()
    {
        $_SERVER['REQUEST_URI'] = 'api/users/1';

        $api = new API_handler();

        $result = $api->call();
    }

    public function testGetByQueryString()
    {
        $_SERVER['REQUEST_URI'] = 'api/users/?username=asdasd';

        $api = new API_handler();

        $result = $api->call();
    }

}