<?php
use Codeception\Util\Stub;

class identityTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
        session_destroy();
    }

    protected function _after()
    {

    }

    // tests
    public function testSetId()
    {
        $identity = new Identity(1, null);

        $this->assertTrue($identity != false);


    }

    public function testCantSetIdTwice()
    {
        $identity = new Identity(1);

        $ouptput = $identity->set_id(1);

        $this->assertFalse($ouptput);
    }

}