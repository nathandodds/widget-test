Pegisis - Storm Creative internal PHP Framework
=========
<p><i>The all to guide...</i></p>

<h2 id="getting-started">Getting Started</h2>
<p>Pegisis is completely intuitive - to set up you have to do very little. Unless your Rich and your computer doesn't work like the rest, then you have to change a setting.</p>

<p>If your application displays an error about a controller not being defined - navigate to /core/settings/site.php and replace the proper path with the one that is commented out: </p>

```php

	<?php

	$settings[ 'USE_TAGS' ] = FALSE;

	$settings[ 'DIRECTORY' ] = str_replace ( $_SERVER[ 'DOCUMENT_ROOT' ], '', DR ).'/';

	// If your machine errors:
	// Replace with: '/'.$_SERVER[ "DOCUMENT_ROOT" ] . $settings[ 'DIRECTORY' ]; 
	$settings[ 'PATH' ] = $_SERVER[ "DOCUMENT_ROOT" ] . $settings[ 'DIRECTORY' ];

	$settings[ 'IP' ] = $_SERVER['HTTP_HOST'];

	$settings[ 'LIVE' ] = FALSE;

	?>

```

<strong><i>Database settings</i></strong>
<p>To change your database settings to that of your database - go to /core/settings/database.php just replace all values with your settings</p>

<h2>Command Line Utility</h2>
<!--
<p><strong><i>Tables / Models</i></strong></p>

<p>To make collaboration smoother and easier, it is preferable not to create/ammend tables via PHPMyAdmin but rather through the Command Line. This way another team member can easily migrate your database schema without having to do anything too manual.</p>

<p>As all models will have a table associated with it - you will run b_mod.php command line. This will create your model in the correct place, but also create a migration for you.To run this is a Rake command in the terminal, replace name_of_model with your model name:</p>

```php rake model[name_of_model] ```

<p>This will put you through a simple GUI that will prompt you to choose the name of the model and the column descriptions...easy. This will create a migration within cmd/nameofyourtable - you can edit this file to ammend the table structure and once done just rerun a migration command</p>

-->

<p><strong><i>Database Migration</i></strong></p>

<p>To migrate another persons DB schema or to rerun yours after you have made a change just type in the terminal:</p>

```php rake dbmigrate ```

<p><strong><i>Javascript</i></strong></p>

<p>Instead of having the memorise the basic set up for a Require or Backbone set up - you can just create it quickly via the command line. Just run the following command, and follow the on screen GUI:</p>

```php rake javascript ```

<p><strong><i>Controllers / Views</i></strong></p>

<p>To create your controller and views ( as views correlate to the controller methods ) you can run the controller command. This also builds the tests and Stylesheets according to each controller method, so you don't have to! Just <strong>remember</strong> to use underscore for method names and controller names as your PHP will break! Run the following and follow the GUI instructions. Replace name_of_controller with the controller name:</p>

```php rake controller```

<p><i>Note: the above rake tasks for controller and models will automatically create Unit Tests for each</i></p>

<p><strong><i>MYSQL Dump</i></strong></p>

<p>If you need to dump out your database quickly, to send to someone else or for populating the database dump files for your tests you can run the following command which will create a dump files within /tests/data/dump.sql

```php rake mysqldump```

<p><strong><i>MYSQL Restore</i></strong></p>

<p>If you need to restore a database from a dump file you can run the below command: </p>

```php rake mysqlrestore```

<p><strong><i>Create new unit test</i></strong></p>

<p>To quickly create a new unit test you can do so by using the below task - replacing unit_test_name with the name of your unit test. This saves having to type out the codeception unit test command.</p>

```php rake new_unit unit_test_name```

<p><strong><i>Generate Styleguide</i></strong></p>

<p>Using Styledocco and Compass you can generate a styleguide which will appears in 'docs' of the route directory. This will mean that you will need to follow the Styledocco documentation comments, which will be found in the front-end styleguide.</p>

```php rake new_unit styleguide```



<h2 id="grunt">Grunt</h2>

<p>Just like the above command line utlities in rake - there is also a set of default grunt tasks you can use:</p>

<ul>
	<li><strong>sass:</strong> using the command 'grunt sass' will compile all scss files into the stylesheets folder. This is useful if you have aload of conflicts in your css files, or just mass assignment.</li>
	<li><strong>css min:</strong> 'grunt cssmin' this will minify all compiled stylesheets - this is will need to be used as the grunt task for sass only compiles it doesn't minify.</li>
	<li><strong>uglify:</strong> using the command 'grunt uglify' will minify all javascript files, this will make all minified files go into the 'build' directory of the scripts folder.</li>
	<li><strong>image min:</strong> 'grunt imagemin' will compress all images within the assets/images folder. This is for when the site is going live to get down some sizeage.</li>
</ul>


<h2 id="controllers-views">Controllers / Views </h2>

<p>Controllers are the pages of your website - if you have a controller called page and a method called about, you would access this in the address bar as /page/about </p>

<p>All Controllers that will require rendering within the browser will extend C_Controller.</p>

<p>The C_Controller is pretty intuitive - it can work out which view file is needed for that method ( if named correctly ) and will render the page automatically. So all you need to get up an running is one method within a controller and you can let your view do all the work.</p>

<p>The view will be loaded if it has the *exact* name as the method within the controller. All views are grouped within a directory named by their controller, so take for example:</p>

```php

	class Page extends C_Controller {

		public function about()
		{
			// This will automatically render the view from within /views/templates/page/about
		}


		public function another_page()
		{
			// An example of setting the view if the C_Controller doesn't know where to look
			$this->setView('page/another-page');
		}

	}

```


<p>This is what the views directory would look like ( for grouping the views to the controller ): </p>

```php

	views /
		templates
			/
			Page
				/ about
				/ another_page
			Another_controller
				/ another_view
				/ another_view_2

```

<p>There are numeorus helpful methods for manipulating the view within the C_Controller - documented below:</p>

```php

	/**
	 * Adds a variable to be accessible within the view
	 *
	 * @param string $tag - the reference name of the tag within the view
	 * @param string $value - the value to be associated to the tag
	 */
	addTag( $tag, $value );


	/**
	 * Merges an array of tags to the main tags array
	 * 
	 * @param array $tags - the array of tags to merge!
	 */
	mergeTags( $tags );


	/**
	 * Sets the script for the page ( as we use requireJS - we only have one script on the page )
	 * 
	 * @param string $script - the name of the script
	 */
	setScript( $script );


	/**
	 * Adds a stylesheet to the stylesheet array to associate to the view.
	 * This can either be an internal or external stylesheet - if external provide the whole path
	 * otherwise it will always default to assets/styles/
	 *
	 * @param string $stylesheet - the stylesheet
	 * @param optional boolean $raw - set to FALSE if using external stylesheet
	 */
	addStyle( $stylesheet, $raw = TRUE );


	/**
	 * Sets the view to render
	 *
	 * @param string $view - the view to render
	 */
	setView( $view );


	/**
	 * Renders a 404 page - for whatever reason you wanted to show a dead page
	 * 
	 * @param optional string $reason - the reason for that
	 */
	render404( $reason = "" );


	/**
	 *	Use if the No header/footer needs to be loaded. For example using a raw HTML page.
	 */
	plain();


```



<h2 id="templating">Templating</h2>
<p>You have two options for templating - plain PHP or Blade Runner templates.</p>
<p>To use a Blade Runner template, simply append .tmpl to the PHP view filename. This will render the template file on compile time to a legit PHP file within the _rendered folder of the views directory.</p>

<strong>Blade Runner tags</strong>
<p>Blade Runner tags are based identically to PHP tags - just without the need to put PHP tags at the beginning and end.</p>

<p>Instead of wrapping PHP tags - prepend the first tag with an @ symbol and keep the naming conventions the same. No need for ending with a semi-colon either.</p>

<p>Here is a few examples to demonstrate the replacement - the rest is obvious from there:</p>

```php

	/**
	 * A conditional IF example
	 */
	@if ($fruit == 'banana' )
		// Do something
	@elseif (fruit == 'apple')
		// Do something else
	@else 
		// Do something different


	/**
	 *	Echo'ing a variable within a view
	 *  To do this you just have to wrap two curly braclets around the variable
	 */
	 {{ $variable }}

	/**
	 * Foreach example
	 */
	@foreach ($fruits as $fruit)
		<p>Fruit is: {{ $fruit['title'] }}
	@endforeach
```

<h2 id="routes">Routes</h2>

<p>Because routes are automatically defined by the framework by the controller to method names, you end up having to use ugly names or underscores. If you need to tidy up your routes so that your application is tidy and SEO defined you can.</p>

<p>To change your routes - navigate to core/settings/routes.js. <br> You just need to say what route you want and how that will be accessed. For example: </p>

```php

	[{	
		"test_only" : "test/only",
		"test/again" : "testing",
		"testing" : "home/test",

		"about-us": "page/about"
	}]

```

<p>The first three lines will always be there by default - these are used for passing the Unit tests so DO NOT REMOVE. The last example is how you define a route. The first part is the route you want, then the next part is how that would normally be accessed. These routes are basically just a blanket for the real routes.</p>



<h2 id="active-record">Database : ActiveRecord</h2>

<p>The Active Record handles all database activity - all *base* models will extend ActiveRecord if they require connectivity with the database.</p>

<p>By default - the ActiveRecord will will determine what table to use by the models name: this allows you to instantly call the model within the controller and start using it's database methods to do what you need, without having to do any setting up. You just need a Model!</p>

<strong id="simple-finding-saving">Simple Finding & Saving</strong>

<p>To save a new item to the database it is as simple as below </p>

```php

	<?php
	$model = new Authors_model();

	$model->name = "Ashley Banks";
	$model->email = "ash@stormcreative.co.uk";

	$model->save();
	?>
```

<p>If you are saving an item from a form - you don't have to set any property values to save. You can pass in the POST array and ActiveRecord will do the rest:</p>

```php

	<?php
	/*
		This example is based on the form being arranged correctly into an array, for example:
		<input type="text" name="authors[name]" value="Ashley Banks">
		<input type="text" name="authors[email]" value="ash@stormcreative.co.uk">
	*/

	$model = new Authors_model();

	// using post_set() internal form helper method
	if( post_set() )
		$model->save( $_POST['authors'] ); 
	?>
```

<p>Finding an item by an ID</p>

```php

	<?php

		$model = new Authors_model();

		$author = $model->find(1);

		// You can also choose which column to find by - ActiveRecord will default to ID

		$model = new Authors_model();

		$author = $model->find( 'ash@stormcreative.co.uk', 'email' );

	?>

```

<p>ActiveRecord works out whether you are inserting a record or updating it - so the only method to use ( and avaliable to use ) is always Save. As long an ID is set within the ActiveRecord - it will always default to updating that record</p>

```php

	<?php

		// Here is a couple of examples to updatign a value

		// The normal basic way
		$model = new Authors_model();

		$model->find(1);

		$model->name( "Ashley S Banks" );

		$model->save();

		// Passing the parameters into the save method and chaining the find to the save
		$model = new Authors_model();

		$model->find(1)->save( array( 'name' => "Ashley S Banks" ) );

	?>

```

<p>The Find method will only ever pass back *one* record - if you want to retrieve all records as an associative array you just use the All method: </p>

```php

	<?php

		// Grabbing all data back
		$model = new Authors_model();

		$records = $model->all();

		// Grabbing all data back with a where clause
		$model = new Authors_model();	

		// Notice the prepended : suffix - this is because the DB drive is PDO and we are binding the value.
		// To bind this value you pass through an array to the all method that links the two together ( as below ):

		$record = $model->where( 'email = :email' )->all(array('email' => 'ash@stormcreative.co.uk'));

	?>

```

<strong id="associations">Associations</strong>

<p>ActiveRecord makes associations a hell of alot easier and quicker.</p>

<p>There are two associations at current that do all you need: <i>One to One</i> and <i>One to many</i></p>

<p>If your table has many associations off of it - for example an Author had many Books, you set an association via the Model. Like below.</p>
<p>This automatically brings back a record within the Find/All that holds all the data associated to this</p>

```php

	<?php
		/**
		 * Set the table association in the Model
		 * via the $this->_has_many or $this->_has_one
		 */
		class Authors_model extends Active_record {

			public function __Construct()
			{
				parent::__Construct();

				$this->_has_many = 'posts';

				// This can also be set as an array if you have multiple
				// $this->_has_many = array( 'posts', 'names' );
			}

		}

	?>

	<?php

		$model = new Authors_model();

		$model->find(1);

		// This will set $posts to be an array of posts ( array('title' => 'posts' ))
		$posts = $model->posts;

	?>

```

<strong id="saving-associations"><i>Saving Associations</i></strong>
<p>Associations can also be saved using only <strong>one</strong> save method - there are a couple of ways to do this providing that the associations have been within the model of course. This is demonstrated below:</p>

```php

	<?php

		// This first example is passing the Posts as an array key with it's values

		$authors_model = new Authors_model();

		$author_model->save( array( 'name' => "Ashley Banks", 'posts' => array('title' => "Blog Post" ) ) );

		// This example is passing in the altered object of the posts model

		$authors_model = new Authors_model();
		$posts_model = new Posts_model();

		$posts_model->title = "Post title";

		$authors_model->save( array('name' => "Ashley Banks", $posts_model ) );

		// And of course these can all be done through the update by:

		$authors_model = new Authors_model();
		$posts_model = new Posts_model();

		$posts_model->find(1);
		$posts_model->title = "Blog Post ammended";

		$authors_model->find(1)->save( array('name' => "Ashley Banks update", $posts_model ));
	?>

```

<strong id="validation"><i>Validations</i></strong>

<p>You can validate fields to ensure empty values can not be inserted into the database. This can be done through the Model as a strict rule to anything accessing that model, to say that these fields need a specific validation. If any errors occurr these become accessible as both the columns that were erroring and a message that was generated for these; for quick display within the view.</p>

<p>Validations can be set within the model like so: </p>

```php

	<?php

		class Authors_model extends Active_record {

			public function __Construct()
			{
				parent::__Construct();

				$this->validate( 'not_empty', 'title' );

				// This example is a new method for validating the email address - with an additional message to apply to the errors
				$this->validate( 'valid_email', 'email', 'Email can not be empty!' );
			}

		}

	?>


	<?php

		$model = new Authors_model();

		// Saving without any set data will now bring up errors
		$model->save();

		// Grab the errors by the errors property thats now set
		$errors = $model->errors;

		foreach( $errors as $column => message )
		{
			echo $column . ' ' . $message;
		}

	?>

```

<strong id="build-your-own-method"><i>Build your own method</i></strong>
<p>Sometimes it's quicker to say you want to do something rather than chain aload of methods - well with ActiveRecord you can do just that...aslong as it makes sense to Active Record</p>

<p>We know we have a few useful methods for retrieving data:</p>

<ol>
	<li>Where: Sets up the where clause</li>
	<li>Find: Returns an object</li>
	<li>All: Returns an associative array</li>
</ol>

<p>The ordinary way of constructing a get query is as follows: </p>

```php

	$authors->where( 'name = :name' )->all( array( 'name' => 'Ashley' ) );

```

<p>But this can be fairly elegent by writing out in a bit of clearer English. For example you want to find all records by a name and email address you would do this like so: </p>

```php

	// So you want to say: find all where name and email is...
	$authors->find_all_where_name_and_email( array( 'name' => 'Ashley Banks', 'email' => 'ash@stormcreative.co.uk' ) );

	// So you want one record you say: find one where name and email is whatever you want...
	$authors->find_one_where_name_and_email( array( 'name' => 'Ashley Banks', 'email' => 'ash@stormcreative.co.uk' ) );

	// This means you can write as many wheres as you want and it will still work and build up a where clause. The method technically doesn't exist
	$authors->find_one_where_name_and_email_or_banana_and_chicken();

	// You can also just set the where through it
	$authors->where_name_and_email()->all( array( 'name' => 'Ashley Banks', 'email' => 'ash@stormcreative.co.uk' ) );

```

<p>As long as your start the method with the below, you will get results: </p>

<ol>
	<li>where</li>
	<li>find_all_where</li>
	<li>find_all</li>
	<li>find_one_where</li>
	<li>find_one</li>
</ol>

<strong id="active-record-methods"><i>Active Record methods</i></strong>

<p>See below the list of accessible ActiveRecord database methods for your use - along with their documented arguments:</p>
```php

	/**
	 * Appends a where argument to the where clause
	 * For example: $this->where( "email = :email" );
	 * Pass in the binds array to the search method ( find or all ).
	 * like: $this->where('email = :email')->all( array('email' => "ash@stormcreative.co.uk") );
	 *
	 * @param string $where - just a string of the where clause
	 */
	where( $where );

	/**
	 * Change how the where array is imploded
	 * Either AND or OR for each additional where argument to be concatenated with
	 *
	 * @param string $split - the string to split ( AND/OR )
	 */
	where_split( $split );


	/**
	 * Select the order of the results
	 * 
	 * @param string $col - the column to sort
	 * @param optional string $order - either ASC or DESC
	 */
	order_by( $col, $order = 'ASC');


	/**
	 * Select which columns to select in the get query
	 *
	 * @param mixed $columns - either array or comma seperated string of columns
	 */
	columns( $columns );


	/**
	 * Set the limit of the query
	 * 
	 * @param string $limit - just a number of the limit
	 */
	limit( $limit );


	/**
	 * Set the offset of the query
	 * 
	 * @param string $offset - just a number of the offset
	 */
	offset( $offset );


	/**
	 * Gets an assoc array of results and builds the query
	 * 
	 * @param optional array - the array of values to bind for a query
	 */
	all( $binds = array() );


	/**
	 * Get a specific item from the DB - returns an object
	 *
	 * @param int $id - the id of the field to get
	 * @param optional string $field - the field search
	 * @param optional string $type - either object or assoc
	 */
	find( $id, $field = 'id', $type = 'object' );


	/**
	 * Add a validation rule before saving ( usually put in a model )
	 * Example: $this->validate( 'not_empty', 'name', "please dont have an empty name" );
	 *			$this->validate( 'valid_email', 'email' );
	 * 	
	 * @param limitless $arguments - just keep throwing in arguments!
	 */	
	validate( $arguments );


	/**
	 * Save an item to the database
	 * @params optional array $params - values to save, optional if not setting property
	 */
	save( $params = array() );


	/**
	 * Delete item(s) from a database
	 * This can either delete one specifc row from the set id or many and their associations
	 * @param optional array $items - the array of id's to delete
	 * @param optional array $foreign - the array of foreign ids to delete
	 */
	delete( $items = array(), $foreign = array() )

```


<h2 id="testing">Testing</h2>

<p>Tests are based on PHPUnit which is project-by-project installed by composer (due to the difficulty in installing PHPUnit on some machines with MAMP!)</p>

<p>This Framework uses a PHPUnit wrapper framework called Codeception; to handle both Acceptance tests and Unit tests</p>

<p><p font-style="color:red;"><strong>ALL TESTS MUST BE APPENDED WITH CEPT to work ( except cest tests which are cest instead of cept )</i></strong></p>

<strong id="acceptance-testing"><i>Acceptance Testing</i></strong>

<p>Acceptance tests, test the actual behaviour of the application. These check that forms are working, posting, showing correct feedback messages, links all exist on the page and are going off to where they should and that text matches the correct text that's in design. </p>

<p>To first create an acceptance test ( assuming your in your project directory in terminal ) type the below code in - replacing TheTestName with the test name. This will create a test case within tests/accceptance directory</p>

```php php codecept.phar generate:cept acceptance TheTestNameCept ```

<p>The above will generate a file within tests/acceptance/TheTestNameCept.php. If you view that file you will see the first two lines of the below code snippet. The below code snippet I have provided a couple of examples of how you would make a few acceptance tests. </p>

```php
	
	<?php

	$I = new WebGuy($scenario);
	$I->wantTo('ensure that frontpage works');
	$I->amOnPage('/'); 
	$I->see('Home');

	// Example of checking links exist and work
	$I->seeLink('About us');
	$I->click('About us');
	$I->see('About us text');

	// Example of filling in a field 
	$I->fillField('field[name]', 'nama');
	$I->click("Submit");
	$I->see("Thanks");

	?>
```

<strong id="unit-testing"><i>Unit Testing</i></strong>

<p>Unit Tests of course are for testing the core units of your application work. To create, just as before type in below:</p>

```php php codecept.phar generate:cept unit TheTestNameCept ```

<p>This will create a test within tests/units/. The test methods are all the same as PHPUnit - so use this to reference</p>

<strong id="controller-mock-testing"><i>Controller/Mock class (Unit) Testing</i></strong>

<p>You will also need to test your Controllers or Mock classes if they are accessible. This is done through the tests of Cest ( Controller Tests combined ). This is the same process of Unit test however you will have to make a few changes...</p>

<p>Replace Cept with Cest at the end of the test - remove the exention of the class and pass in CodeGuy into the testing method...basically you will need the skeleton demonstrated below:</p>

```php

	<?php
		use Codeception\Util\Stub as Stub;

		class ExampleTestCest
		{
			public $class = 'thecassname';

			public function index(CodeGuy $I)
			{
				// Prepare classes to be used
				$I->haveFakeClass($controller = Stub::makeEmptyExcept($this->class, 'index'));

				$I->expect('Expect that the Class will load successfully')
				  ->executeTestedMethodOn($controller)
				  ->seeResultNotEquals(false);

				// A few examples of seeing if methods have been invoked ( from the C_controller )
				$I->seeMethodInvoked($controller, 'setScript');
				$I->seeMethodInvoked($controller, 'addTag');
				$I->seeMethodInvoked($controller, 'setView');
				$I->seeMethodInvoked($controller, 'addStyle');
			}

		}
	?>   

```

<strong><i>Running Tests</i></strong>

<p>Tests are all instantiated and run through the Rake file. </p>

<p>To run <i>Acceptance Tests</i>: </p>

```php rake acceptance ```

<p>To run <i>Unit Tests</i>: </p>

```php rake units ```

<p>To run *ALL* tests</p>

```php rake tests ```

<h2>Deploying</h2>

<p>The need to upload files is abolished by using the Ant build software ( however sometimes you *may* need to manually upload images ).</p>

<p>A site will *only* upload if ALL tests have passed - so it is integral an application has all passing tests before it can be uploaded.</p>

<p><strong>Setting-up</strong></p>

<p>First ensure the FTP details are correct and have been set within the /build.xml file. You will see in the file located somewhere something like the below, just change the details to that of the targeted server:</p>

```php

	<ftp server="78.109.163.36"
                 userid=""
                 password=""
                 port="21"
                 remotedir="/httpdocs"

```      

<p><strong id="deploying">Deploying</strong></p>

<p>As mentioned, all tests will run before deploying - so take a close look at your console as it is deploying. To action this, it's another rake command: </p>

```php rake deploy ```

<h2 id="helpers">Helpers</h2>

<p>Helpers are just raw functions - they are accessible from anywhere within the site ( models, controllers, views ). They are there to help you quickly do a task - feel free to write anymore into the list too. </p>

<p>Helpers are located in /core/helpers/helpers.php</p>

<h2 id="cmd">Admin CMD</h2>

<p>When you first clone this repo all the files to make the admin area work will already be available. To get started building the cms you need to be in the root of the project and you need to run " rake admininit". This will set up the database structure for the admin section. The access, images and uploads tables will be created.</p>

<p>The next thing to run is "rake adminlisting" to create a listing page. The process is as follows: </p>

<ul>
	<li>Chooseing a controller name</li>
	<li>A loop to enter fields</li>
</ul>

<p>Each iteration of the loop will ask you for four colon separated options. These are: </p>

<ul>
	<li>Name - the name of the field. This will be used in the view and be the name of the column in the database.</li>
	<li>
		Type - This will be the type of field that will be used in the edit view. You can choose from:
		<ul>
			<li>Text</li>
			<li>Textarea</li>
			<li>Image</li>
			<li>Upload</li>
			<li>Email</li>
			<li>Link</li>
			<li>Date</li>
			<li>Telephone</li>
			<li>Radio</li>
			<li>Select</li>
		</ul>
		
		<p>Both the radio and select types need a extra fifth option but I will explain that later.</p>
	</li>
	
	<li>Data type - the data type that will be applied to the database column. Generally this will either be VARCHAR, INT or TEXT.</li>
	<li>Max length - This is only required for the radio and select options because they need a fifth option. I have set up defaults if nothing is passed in for this option. VARCHAR will be defaulted to 255 and INT will be defaulted to 11.</li>
	<li>Options - This only applies to the dropdown and select types. This needs to be a comma seperated string of the radio buttons or select options.</li>
</ul>

<p>Lets run through an example how this can be used.</p>

```php
	rake admininit
```

<p>You should see: </p>

```php
	Access model created successfully
	
	Access table has been created successfully
	
	Image model has been created successfully
	
	Image table has been created successfully
	
	Uploads model has been created successfully
	
	Uploads table has been created successfully
```

<p>If the table already exists you will see a SQL error.</p>

<p>Next you need to run</p>

```php
	rake adminlisting
```

<p>The first stage is to choose a name for the controller.</p>

<p>Next is the loop of fields. Due to the naming convention of the generic list controller there needs to be a column named "title". If the situation requires something other than title, such as name, this can be manually changed in the edit view. If it is not feasible to use a title field what so ever, the controller will not be able to use the generic listing class without a string of fields. But more on that later. Lets look at some examples: </p>

```php
	//Text field called title
	title:text:varchar:255 OR title:text:varchar ( remember the max lenght is optional )
	
	//Wysiwyg called description
	description:textarea:text
	
	//Image uploader
	image:image
	
	//File uploader
	upload:upload
	
	//Group of radio buttons ( remember the max length is required )
	tags:radio:varchar:255:technology,business,sport
	
	//Dropdown menu ( remember the max lenght is required )
	gender:select:varchar:255:male,female
	
	//Email field
	email:email:varchar:255
```

<p>To stop the loop and continue with the process you need to type "n".<p>

<p>If this will successful you will see something like:</p>

```php
	The news controller has been created successfully
	
	news model has been created successfully
	
	The edit view has been created successfully
	
	The news table has been created successfully
	
	The news has been created successfully
	
	Unit test has been generated successfully
	
	Acceptance test for news has been saved successfully
```

<p>As you can see a lot of files are generated. Lets go through and explain each one.</p>

<ul>
	<li>The controller - Has the edit method with all the logic to save and edit data.</li>
	<li>The model - This will be used by both the front end and the CMS.</li>
	<li>The edit view - A form containing all the fields that we requested during the command line process.</li>
	<li>The database table - With the structure requested during the command line process</li>
	<li>The unit test - A unit test for adding / editing records</li>
	<li>The acceptance test - A acceptance test for the edit page</li>
</ul>

<p>All listing actions will now be routed though a generic listing page. It will work one of two ways:<p>

<ul>
	<li>Passing in the table name through the URL - This will be the default option for every controller. It will only bring back the title and the create date.</li>
	<li>Passing the table name and a list of column names as GET data - This option can be used by changing the destination of the link in the menu file. For example "/listing/news/?columns=title,author,topic".</li>
</ul>

<h2>Data mocking tool</h2>

<p>Manually entering test data into your projects is a pain in the arse so we have a command line tool that does it for you. Simply enter into your terminal...</p>

```php
	rake mock[tablename, number of records]
```

<p>...and thats it. You now have a database full of test data. This reads the associations from the tables model and adds data to them as well. There is also no need to worry about image and document associations because this handles it by moving a photo or document and saving the data the same way so it reacts like a upload would.</p>
