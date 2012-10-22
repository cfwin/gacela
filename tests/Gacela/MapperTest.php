<?php

use Test\Mapper as T;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-10-21 at 14:11:44.
 */
class MapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mapper
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
		$gacela = \Gacela::instance();

		$arr = array(
			'name' => 'db',
			'type' => 'mysql',
			'schema' => 'gacela',
			'user' => '',
			'password' => ''
		);

		$source = $this->getMock(
			'Gacela\DataSource\Database',
			array(),
			array(
				$gacela,
				$this->getMock(
					'\Gacela\DataSource\Adapter\Mysql',
					array(),
					array(
						$gacela,
						(object) $arr
					)
				),
				$arr
			)
		);

		$source->expects($this->once())
			->method('getName')
			->will($this->returnValue('db'));

		$source->expects($this->any())
			->method('loadResource')
			->will(
			$this->returnValue(
				new \Gacela\DataSource\Resource(
					array(
						'name' => 'houses',
						'columns' => array(),
						'relations' => array(),
						'primary' => array('id')
					)
				)
			)
		);

		$gacela->registerDataSource($source);

        //$this->object = new T\Mapper(\Gacela::instance());
    }

	public function testSleep()
	{
		$source = Gacela::createDataSource(
			array(
				'type' => 'mysql',
				'name' => 'db',
				'user' => 'gacela',
				'password' => 'gacela',
				'schema' => 'gacela',
				'host' => 'localhost'
			)
		);

		Gacela::instance()->registerDataSource($source);

		Gacela::instance()->registerNamespace('App', __DIR__.'/../../samples/');

		$mapper = Gacela::instance()->loadMapper('house');

		$str = serialize($mapper);

		$this->assertEquals($mapper, unserialize($str));
	}

    /**
     * @covers Gacela\Mapper\Mapper::addAssociation
     * @todo   Implement testAddAssociation().
     */
    public function testAddAssociation()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::count
     * @todo   Implement testCount().
     */
    public function testCount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::debug
     * @todo   Implement testDebug().
     */
    public function testDebug()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::delete
     * @todo   Implement testDelete().
     */
    public function testDelete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::find
     * @todo   Implement testFind().
     */
    public function testFind()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::findAll
     * @todo   Implement testFindAll().
     */
    public function testFindAll()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::findAllByAssociation
     * @todo   Implement testFindAllByAssociation().
     */
    public function testFindAllByAssociation()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::findRelation
     * @todo   Implement testFindRelation().
     */
    public function testFindRelation()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::getFields
     * @todo   Implement testGetFields().
     */
    public function testGetFields()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::getPrimaryKey
     * @todo   Implement testGetPrimaryKey().
     */
    public function testGetPrimaryKey()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::getRelations
     * @todo   Implement testGetRelations().
     */
    public function testGetRelations()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::init
     * @todo   Implement testInit().
     */
    public function testInit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::load
     * @todo   Implement testLoad().
     */
    public function testLoad()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::removeAssociation
     * @todo   Implement testRemoveAssociation().
     */
    public function testRemoveAssociation()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Gacela\Mapper\Mapper::save
     * @todo   Implement testSave().
     */
    public function testSave()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
