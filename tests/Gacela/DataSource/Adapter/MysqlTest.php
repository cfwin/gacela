<?php
namespace Gacela\DataSource\Adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-22 at 16:53:00.
 */
class MysqlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mysql
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
		\Gacela::instance()->configPath(__DIR__.'config');

        $this->object = new Mysql(array());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Gacela\DataSource\Adapter\Mysql::load
     * @todo   Implement testLoad().
     */
    public function testLoad()
    {

    }
}

