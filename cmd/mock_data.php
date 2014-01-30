<?php

include 'cmd/base_admin.php';

class Mock_data extends Base_admin
{
	public $table;
	public $rows;
	public $has_one;
	public $has_many;
	public $has_many_schema = array();
	public $has_one_schema;
	public $columns_we_dont_want = array( 'id', 'create_date' );

	public $schema = array();
	public $query;

	public function __Construct()
	{
		parent::__Construct();

		$this->table = $_SERVER['argv'][1];
		$this->rows = $_SERVER['argv'][2];
	}

	public function get_schema ()
	{
		$schema = $this->_query->getAssoc( "DESCRIBE `" . DB_SUFFIX . "_" . $this->table . "`" );

		foreach ( $schema as $row ) {

			if ( !in_array( $row[ 'Field' ], $this->columns_we_dont_want ) ) {
				$this->schema[] = array( 'column' => $row[ 'Field' ],
				   				   		 'data_type' => $row[ 'Type' ] );
			}
		}
	}

	public function get_associations ()
	{
		$model_name = $this->table . '_model';
		$model = new $model_name();

		$this->has_one = $model->has_one;
		$this->has_many = $model->has_many;
	}

	public function get_many_associations_schema ()
	{
		if ( count( $this->has_many ) > 0 ) {
			foreach ( $this->has_many as $assoc ) {
				$schema = $this->_query->getAssoc( "DESCRIBE `" . DB_SUFFIX . "_" . $assoc . "`" );
				
				foreach ( $schema as $row ) {
					if ( !in_array( $row[ 'Field' ], $this->columns_we_dont_want ) ) {
						$this->has_many_schema[ $assoc ][] = array( 'column' => $row[ 'Field' ],
														 			'data_type' => $row[ 'Type' ] );
					}
				}
			}
		}
	}

	public function get_one_associations_schema ()
	{
		if ( !!$this->has_one ) {
			$schema = $this->_query->getAssoc( "DESCRIBE `" . DB_SUFFIX . "_" . $this->has_one . "`" );

			foreach( $schema as $row ) {
				if ( !in_array( $row[ 'Field' ], $this->columns_we_dont_want ) ) {
					$this->has_one_schema[] = array( 'column' => $row[ 'Field' ],
													 'data_type' => $row[ 'Type' ] );
				}
			}
		}
	}

	public function organise_the_query ( $schema_data, $table = "" )
	{
		$columns_and_data = $this->sort_data( $schema_data );

		$query = 'INSERT INTO ' . DB_SUFFIX . '_' . $table . ' ( ' . implode( ', ', $columns_and_data[ 'columns' ] ) .  ' ) VALUES ( ' . implode( ', ', $columns_and_data[ 'data' ] ) . ' )' ;

		for ( $i = 0; $i < $this->rows; $i++ ) {
 			$this->_query->plain( $query );

 			$last_id = $this->_query->return_last_inserted_id();
 			
 			//Now we have the ID of the main piece of data we can insert all the data that is associated to it
 			//Has One
 			if ( !!$this->has_one_schema ) {
 				$has_one_columns_and_data = $this->sort_data( $this->has_one_schema, $last_id );

 				$has_one_query = 'INSERT INTO ' . DB_SUFFIX . '_' . $this->has_one . ' ( ' . implode( ', ', $has_one_columns_and_data[ 'columns' ] ) .  ' ) VALUES ( ' . implode( ', ', $has_one_columns_and_data[ 'data' ] ) . ' )';

 				$this->_query->plain( $has_one_query );
 			}

 			//Has Many
 			if ( !!$this->has_many_schema ) {
 				foreach ( $this->has_many_schema as $assoc ) {
 					$has_many_columns_and_data = $this->sort_data( $assoc, $last_id );

 					$has_many_query = 'INSERT INTO ' . DB_SUFFIX . '_' . $this->has_one . ' ( ' . implode( ', ', $has_many_columns_and_data[ 'columns' ] ) .  ' ) VALUES ( ' . implode( ', ', $has_many_columns_and_data[ 'data' ] ) . ' )';

 					for ( $x = 0; $x < $this->rows; $x++ ) {
 						$this->_query->plain( $has_many_query );
 					}
 				}
 			}
		}

		display( $table . ' data has been inserted successfully' . "\n\n" );
	}

	private function sort_data ( $schema_data, $last_id = "" )
	{
		$columns = array();
		$data = array();

		foreach ( $schema_data as $item ) {
			$columns[] = $item[ 'column' ];

			if ( $item[ 'column' ] == 'image_id' ) {
				$data[] = $this->set_up_image();
			}
			else if ( $item[ 'column' ] == 'upload_id' ) {
				$data[] = $this->set_up_upload();
			}
			else if ( $item[ 'column' ] == $this->table . '_id' && !!$last_id ) {
				$data[] = $last_id;
			}
			else {
				$data[] = $this->get_data( $item[ 'data_type' ] );
			}
		}

		return array( "columns" => $columns,
					  "data" => $data );
	}

	private function get_data ( $data_type )
	{
		$parts = explode( '(', $data_type );
		$data_type = $parts[0];
		$data = '';

		switch ( $data_type ) {

			case 'varchar' :
				$data = 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas';
				break;

			case 'text' : 
				$data = 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo';
				break;

			case 'int' :
				$data = 69;
				break;
		}

		return "'" . $data . "'";
	}

	private function set_up_image ()
	{
		$image = PATH . 'cmd/mock_data/Generic.jpg';
		$random_name = $this->random_string() . '.jpg';

		$item = $this->_operations->table( DB_SUFFIX . '_image' )->insert( array( 'imgname' => $random_name ) );

		//Copy the image to the image uploads folder and all the sub directories
		$locations = scandir( PATH . '_admin/assets/uploads/images' );
		$target_locations = array( PATH . '_admin/assets/uploads/images/' );

		foreach ( $locations as $location ) {
			if ( is_dir( PATH . '_admin/assets/uploads/images/' . $location ) && $location != '.' && $location != '..' ) {
				$target_locations[] = PATH . '_admin/assets/uploads/images/' . $location . '/';
			}
		}

		foreach ( $target_locations as $target_location ) {
			copy( $image, $target_location . $random_name );
		}

		return $item;
	}

	private function set_up_upload ()
	{
		$upload = PATH . 'cmd/mock_data/Test.pdf';
		$random_name = $this->random_string() . '.pdf';

		$item = $this->_operations->table( DB_SUFFIX . '_uploads' )->insert( array( "name" => $random_name, "title" => 'Test.pdf' ) );

		copy( PATH . '_admin/assets/uploads/documents/' . $random_name );

		return $item;
	}

	private function random_string ( $length = 10 )
	{
	    $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    srand ( (double) microtime () * 1000000 );
	    $i = 0;
	    $string = '' ;
	    while ($i <= $length) {
	        $num = rand () % 33;
	        $tmp = substr ( $chars, $num, 1 );
	        $string = $string . $tmp;
	        $i++;
	      }

	    return $string;
	}
}

$mock = new Mock_data();
$mock->get_schema();
$mock->get_associations();
$mock->get_many_associations_schema();
$mock->get_one_associations_schema();

$mock->organise_the_query( $mock->schema, $mock->table );

?>