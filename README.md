# Mapping Data Structures to PHP Objects

Most useful applications interact with data in some form. There are multiple solutions for storing data and for each of 
those solutions, sometimes multiple formats in which the data can be stored.
When using object-oriented PHP, that same data is stored, modified and accessed in a class.

Let's assume that you were creating your own blogging system. We'll assume initially that you have need want to create
posts and you want to allow multiple users to author articles. 


Storing the data in a hierarchical format with XML is fairly straightforward. Each 'user' is represented by a node named 
'user' with a child 'contents' node to contain the user's blog posts.

```xml
<xml version="1.0>
	<user id="1" first="Bobby" last="Mcintire" email="bobby@kacela.com" />
	<user id="2" first="Frankfurt" last="McGee" email="sweetcheeks@kacela.com">
		<contents>
			<content id="id" title="Beginners Guide to ORMs">
                In order to start, you need to read the rest of this user's guide.
            </content>
		</contents>
	</user>
</xml>
```

With a relational database, we would create two tables, one to hold the basic information about each user, and a table 
to hold their posts.

'users' table

| id  | name           | email                    |
|-----|----------------|--------------------------|
| 1  | Bobby Mcintire  | bobby@kacela.com         |
|    |                 |                          |
| 2  | Frankfurt McGee | sweetcheeks@kacela.com   |

'contents'

| id | userId | title                   | content                        |
|----|--------|-------------------------|--------------------------------|
| 1  |  2     | Beginners Guide to ORMs | Read the rest of the guide     |

The same data in PHP would be stored in classes like so:

```php
class User {

	protected $data = [
		'id' => 1,
		'name' => 'Bobby Mcintire',
		'email' => 'bobby@kacela.com'
	];

	protected $contents = [];

}

class User {

	protected $data = [
		'id' => 2,
		'name' => 'Frankfurt McGee',
		'email' => 'sweetcheeks@kacela.com',
		'phone' => '9876543214'
	];

	protected $contents = [
        [
            'id' => 1,
            'userId' => 2,
            'title' => 'Beginners Guide to ORMs',
            'content' => 'Read this guide all the way to the end'
        ]
	];

}
```

As you can see the way that data is stored can be vastly different from the way that we interact with data in our 
application code.

This is called the object-impedance mismatch. A common design pattern has arisen to hide the complexities of the 
differences between data in application code and data stores called Object-Relational Mapping.

This design pattern was developed specifically to deal with the complexities of mapping relational database records to 
objects in code, but many of the same principles apply when dealing with any form of raw data because there is almost 
always some mismatch.

# Common Solutions

The most common approach to Object-Relational Mapping, or ORM for short, is the Active Record pattern.

With Active Record, one class instance represents one Record from the Data Source.
With an Active Record instance, business logic and data access logic are contained in a single object.
A basic Active Record class would look like so:

```php
class Model_User extends ORM
{

}
```

And would be accessed like so:

```php
$user = ORM::find('User', 1);

// echo's Bobby Mcintire to the screen
echo $user->name;

$user->email = 'new.user@gacela.com'

$user->save();
```

# Gacela's Basic Philosophies

Working with a Data Mapper for the first time can be quite a bit more difficult than working with a more basic approach 
like Active Record, but Gacela offers large dividends if you tackle the complexity upfront. When developing Gacela, the 
following were just the top features we thought every ORM should have:

- Automatically discover relationships between classes and rules about the data contained within classes.
- Separate data store activities from business logic activities so that our classes have a single responsibility.
- Defaults that are easy to set up and use, but can handle complex mapping between business objects and the underlying 
data store.

# Installation and Configuration

## How to Install

Gacela can be installed with Composer.

Define the following requirement in your composer.json file:

```json
{
    "require": {
        "energylab/gacela": "dev-develop"
    }
}
```

## Configuration

## Data Source Setup

Gacela assumes that in any given application there will be multiple sources of data, even if there just multiple 
databases that need to be used.

Currently there are two supported types of Data Sources for Gacela: Database & Salesforce. We plan to add support for
Xml, RESTful Web Services, and SOAP Web Services as well as to fully support the differences between MySQL, MSSQL,
Postgres, and SQLlite.

Gacela provides a convenience method to create a DataSource object from configuration parameters. Once a DataSource 
object is created, it is easily registered with the Gacela instance so that it is available from anywhere.

### Relational Database

```php
$source = \Gacela\Gacela::createDataSource(
    [
        'name' => 'db',
        'type' => 'mysql' // Other types would be mssql, postgres, sqllite
        'host' => 'localhost',
        'user' => 'gacela',
        'password' => '',
        'schema' => 'gacela'
    ]
);

\Gacela\Gacela::instance()->registerDataSource($source);
```

The default for Gacela is to name the database tables in the plural form (users, contents). Though this can be easily
overridden. We'll look at an example of how to override table name later.

For example:

```sql
CREATE TABLE users (
    id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(200) NOT NULL
) ENGINE=Innodb;

CREATE TABLE contents (
    id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userId INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    CONSTRAINT `fk_user_contents` FOREIGN KEY (userId) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=Innodb;
```

### Salesforce

```php
$source = \Gacela\Gacela::createDataSource(
    [
        'name' => 'salesforce',
        'type' => 'salesforce',
        'soapclient_path' => MODPATH.'sf/vendor/soapclient/',
			/**
			 * Specify the full path to your wsdl file for Salesforce
			 */
		'wsdl_path' => APPPATH.'config/sf.wsdl',
		'username' => 'salesforceuser@domain.com.sandbox',
		'password' => 'SecretPasswordWithSalesforceHash',
		/**
		 * Specifies which Salesforce objects are available for use in Gacela
		 */
         'objects' => []
    ]
);
```

## Registering Namespaces

Gacela contains its own autoloader and registers it when the Gacela instance is constructed. Gacela also registers its 
own namespace for its use. You will want to register a custom namespace for your application even if you only plan on 
creating Mappers and Models for your project.

```php
/*
 * Assuming that you are bootstrapping from the root of your project and that you want to put your 
 * custom application code in an app directory
 */
\Gacela\Gacela::instance()->registerNamespace('App', __DIR__.'/app/');

/*
 * A handy trick if you want to put your Mappers/Models or other custom extensions for Gacela in the global 
 * namespace
 */
\Gacela\Gacela::instance()->registerNamespace('', __DIR__.'/app/');
```

With those two namespaces registered to the same directory, I could declare a new Mapper (User) like so:

```php
/*
 * __DIR__.'/app/Mapper/User.php'
 */

<?php

namespace Mapper;

class User extends \Gacela\Mapper\Mapper {}
```

Or alternatively like this:

```php 
/*
 * __DIR__.'/app/Mapper/User.php'
 */

<?php

namespace App\Mapper;

use \Gacela\Mapper\Mapper as M;

class User extends M {}
```

Even more exciting is that Gacela allows for cascading namespaces so you can easily override default Gacela classes
without having to modify the methods and classes that depend on the modified class. So lets say that you wanted
to create a custom Model class where you could some default functionality for all of your models to use.

```php
/*
 * __DIR__.'/app/Model/Model.php'
 */
<?php

namespace Model;

class Model extends \Gacela\Model\Model {}

?> // This breaks a PSR standard but is shown here to clarify the end of the file

/*
 * __DIR__'/app/Model/User.php'
 */

 <?php

namespace Model;

class User extends Model {}

?>
```

Personally, I always extend the base Model and Mapper classes in projects if for no other reason than it simplifies my 
class declarations.

## Using Caching

Gacela supports caching on two levels, the first is to cache the metadata that it uses to determine relationships,
column data types, and such. The second is to cache requested data. In order to use either, caching must be enabled 
in the Gacela instance.

Gacela will use any caching library that supports get(), set(), and increment() 

```php
$cache = new \Cache;

\Gacela\Gacela::instance()->enableCache($cache);
```

# Basic Usage

As we noted previously, there are two separate functions provided by any given ORM;

- Data Access
- Business Logic

Most ORM's mash these two responsibilities into a single class that will contain custom methods for dealing with 
business or application logic problems as well as custom methods for finding or saving data back to the database. Gacela
takes a different approach in that it separates these two functions into two separate, distinct classes:

- Mappers (Data Access) 
- Models (Business or Application Logic)

To get our basic application up and running, I will need the following files and class definitions:

```php
/*
 * Again assume that we have created an 'app' directory and registered it into the global namespace with Gacela.
 * 
 * As I mentioned before, I prefer to always override the default Model and Mapper classes in my application so 
 * I will that first.
 * app/Mapper/Mapper
 */
<?php 

namespace Mapper;

class Mapper extends \Gacela\Mapper\Mapper {}

?>

/*
 * app/Model/Model
 */
<?php

namespace Model;

class Model extends \Gacela\Model\Model {}

?>

/*
 * app/Mapper/User
 */
<?php

namespace Mapper;

class User extends Mapper {}

?>

/*
 * The underlying database table is named contents, but perhaps we decided after the fact that we'd rather
 * use Post as the name by which we reference records in this table.
 *
 * app/Mapper/Post
 */
<?php

namespace Mapper;

class Post extends Mapper {

    /*
     * Easy peasy. To manually specify the underlying table or as we call it in Gacela, resource, name just set
     * the $_resourceName property in the mapper. This also works great if your table names are singular rather than 
     * the default plural.
     */
    protected $_resourceName = 'contents';
}

?>

/*
 * app/Model/User
 */
<?php

namespace Model;

class User extends Model {}

?>

/*
 * app/Model/Post
 */
<?php

namespace Model;

class Post extends Model {}

?>
```

Now we can load existing Users, create a new Post, or delete a User or Post.

```php
/*
 * You can also easily override the \Gacela\Gacela class by creating a shorthand class in the app/ directory
 * that extends \Gacela\Gacela. To simplify calls, I like to create a extended class 'G'. Future examples all
 * assume that this extended class exists.
 */
$user = \G::instance()->find('user', 1);

// echos Bobby Mcintire to the screen
echo $user->name;

$user->email = 'different@gacel.com'

// Saves the updated record to the database
$user->save();

/*
 * The required argument when using new Model\Model() specifies the name of the Mapper to use from the Model.
 */
$post = new \Model\Post('Post');

$post->setData(
    [
        'userId' => 1,
        'title' => 'A new blog post',
        'content' => 
    ]
);

/*
 * Will output TRUE because the id column is assigned by the database engine at insert in our case.
 */
echo $post->validate();

$post->save(['title' => 'A better title']);
```

Right now you're probably thinking, "Wait! This looks almost EXACTLY like every other ORM I've ever used, where's the 
benefit in creating two files where I only created one before?" 

So far all we've looked at is the most basic scenario - one database table with a mapper that presents simple, default 
find() and findAll() methods with a Model that doesn't have any custom business logic.

We'll explore custom Mapper functions first.

# Fetching Data using Mappers

## To fetch a single record:

When fetching a single record, Gacela::factory() and Gacela::find() both return an instance of the Mapper::_modelName.

Mapper::_modelName defaults to Model_<name> where name is equal to Mapper_<name>.

```php
Gacela::factory($mapper_name, $id)

/*
 * For Example
*/

// Fetch a record with a simple (single field) primary key
Gacela::factory('user', 1);

// Fetch a record with a complex (multiple fields) primary key
Gacela::factory('survey_answer', array('question_id' => 500, 'user_id' => 23);
```

## To fetch multiple records with simple criteria:

```php
/*
 * Kohana_ORM
*/
ORM::factory('User')
	->where('last_login', '>=', '2012-12-31')
	->find_all();

/*
 * Gacela
 * The Gacela_Criteria object allows users to specify simple rules for filtering, sorting and limiting data without all of the complexity of
 * a full built-in query builder.
 * Gacela::find_all() returns an instance of Gacela_Collection_Arr
*/
Gacela::find_all('user', Gacela::criteria()->greater_than_or_equal_to('last_login', '2012-12-31');
```

## Fetching data using complex criteria

```php
/**
 * Gacela
*/
class Mapper_User extends Gacela_Mapper
{
	/**
	 * Encapsulates the above example in a simple method call.
	*/
	public function find_all_by_last_login($last_login)
	{
		return $this->find_all(Gacela::criteria()->greater_than_or_equal_to('last_login', $last_login);
	}

	/**
	 * Fetches a Collection of all users who have never logged in or have registered in the last ten minutes.
	*/
	public function find_new_or_inactive()
	{
		/**
		 * Gacela_Mapper::_getQuery() returns a Query instance specific to the Mapper's data source.
		 * As such, the methods available for each Query instance will vary.
		*/
		$query = $this->_getQuery()
			->from('users')
			->join(array('l' => 'logs'), "users.id = l.user_id AND l.type = 'login'", array(), 'left')
			->where('l.date IS NULL')
			->where('users.registration >= :date', array(':date' => date('Y-m-d H:i:s', strtotime('-10 minutes')));

		/**
		 * For the Database DataSource, returns an instance of PDOStatement.
		 * For all others, returns an array.
		*/
		$data = $this->_runQuery($query)->fetchAll();

		/**
		 * Creates a Gacela_Collection instance based on the internal type of the data passed to Gacela_Mapper::_collection()
		 * Currently, two types of Collections are supported, Arr (for arrays) and PDOStatement
		*/
		return $this->_collection($data);
	}
}
```

## Customizing the Data returned for the Model

Sometimes, it is desirable to pass data to the model that is not strictly represented in the underlying table for a specific model.

In this instance, lets assume that we want to return a comma-delimited list of aliases as part of the main user record.

```php

/**
 * Gacela
**/
class Mapper_User extends Gacela_Mapper
{
	public function find($id)
	{
		$criteria = Gacela::criteria()->equals('id', $id);

		$rs = $this->_run_query($this->_base($criteria))->fetch();

		if(!$rs)
		{
			$rs = new \stdClass;
		}

		return $this->_load($rs);
	}

	public function find_all(Gacela_Criteria $criteria = null)
	{
		/**
		 * Returns an instance Gacela_Collection_Statement
		**/
		return $this->_run_query($this->_base($criteria));
	}

	/**
	 * Allows for a unifying method of fetching the custom data set for find() and find_all()
	**/
	protected function _base(Gacela_Criteria $criteria = null)
	{
		$sub = $this->_get_query()
			->from('aliases', array('user_id' => 'id', 'aliases' => "GROUP_CONCAT(alias)")
			->groupBy('id');

		return $this->_get_query($criteria)
			->from('users')
			->join(array('a' => $sub), 'users.id = a.user_id', array('aliases));
	}
}
```
