<?php
/**
 *  This is an example controller for news
 * 
 *  Shows how to upload multiple images
 */
class news extends application_controller
{
	private $_news;
	private $_admin_helper;

	public function __Construct ()
	{
		parent::__Construct ();

		$this->_news = new news_model();
		
		$this->forms->setActionTable ( 'news' );
	}

	public function edit ( $id = "" )
	{
		$this->_news->attributes[ 'id' ] = !!$id ? $id : $_POST['news']['id'];

		if ( post_set() )
		{
			if ( !$this->_news->save( $_POST[ 'news' ] ) ) {

				$feedback = organise_feedback ( $this->_news->errors, TRUE );
			}
			else {

				$images = Image_helper::multi_image_move();

                if( !!$images || $_POST[ 'multi-image' ] ) {
                	// This is used only if there is a field within the image table for the table type - eg. news_id
                    //Image_model::save_multi( ( !!$images ? $images : $_POST[ 'multi-image' ] ), $this->_news->attributes[ 'id' ] );

                    // otherwise use the below - where the callback will be a comma seperated string within the main news table
                    // Image_helper::save_many()
                }

				$feedback = organise_feedback ( $this->forms->getSuccessMessage () );
			}
		}

		$this->_news->find( $this->_news->attributes[ 'id' ] );

		// Sets the form values by the properties set through the Find method in active record
		$this->mergeTags ( $this->_news->attributes );
		
		
		$this->addTag ( 'image', $this->_news->image );
		$this->addTag ( 'feedback', $feedback );

		$this->addStyle ( 'edit' );
		$this->addStyle ( 'button' );

		$this->setScript( 'main' );
	}
}

?>