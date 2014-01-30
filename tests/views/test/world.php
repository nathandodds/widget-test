<?php
include ( "../../base_web_test_case.php" );

class test_test_world extends base_web_test_case
{
	public function setUp ()
	{
		$this->get ( "http://localhost:8888/pegisis/test/world" );
	}
	
	public function testHeader ()
	{
		$this->assertText ( 'world' );
	}

}