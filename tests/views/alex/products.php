<?php
include ( "../../base_web_test_case.php" );

class test_alex_products extends base_web_test_case
{
	public function setUp ()
	{
		$this->get ( "http://localhost:8888/pegisis/alex/products" );
	}
	
	public function testHeader ()
	{
		$this->assertText ( 'products' );
	}

}