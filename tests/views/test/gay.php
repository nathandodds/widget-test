<?php
include ( "../../base_web_test_case.php" );

class test_test_gay extends base_web_test_case
{
	public function setUp ()
	{
		$this->get ( "http://localhost:8888/pegisis/test/gay" );
	}
	
	public function testHeader ()
	{
		$this->assertText ( 'gay' );
	}

}