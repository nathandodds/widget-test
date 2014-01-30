<?php
use Codeception\Util\Stub;

class User_authenticatorTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    private $id;

    private $user;

    /**
     * Add in test data to each of the tables
     * automatically removes after end of the test
     */
    protected function _before()
    {
        $this->user = new Users_model();
        $this->user->username = 'ash';
        $this->user->password = sha1('password');

        $this->user->id = $this->user->save();

        $access_model = new Access_model();
        $access_model->username = 'ash';
        $access_model->password = sha1('password');
        $access_model->save();
    }

    /**
     * Test authentication with standard autoloaded model
     */
    public function testAuthenticate()
    {
        $authenticate = new User_Authenticator();
        $result = $authenticate->authenticate(array('ash', 'password'));

        $this->assertTrue($result);
    }

    /**
     * Test authentication after changing the model
     */
    public function testAuthenticateWithAccessModel()
    {
        $authenticate = new User_Authenticator('access');
        $result = $authenticate->authenticate(array('ash', 'password'));

        $this->assertTrue($result);
    }

}