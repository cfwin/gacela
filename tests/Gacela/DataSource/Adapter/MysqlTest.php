<?php
namespace Gacela\DataSource\Adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-22 at 16:53:00.
 * @group Gacela
 * @group Gacela.DataSource
 * @group Gacela.DataSource.Adapter
 */
class MysqlTest extends \Test\TestCase
{
    /**
     * @var Mysql
     */
    protected $object;

	protected $cols = null;

	protected $primary = null;

	protected $relations = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
		$this->object = new Mysql(
			array(
				'schema' => 'test',
				'host' => 'localhost',
				'password' => 'gacela',
				'user' => 'gacela',
				'type' => 'mysql'
			)
		);

		$meta = $this->object->load('types');

		$this->cols = $meta['columns'];
		$this->primary = $meta['primary'];
		$this->relations = $meta['relations'];
    }

	public function providerBinary()
	{
		return array(
			array('binary', 75),
			array('varbinary', 255),
			array('tinyblob', 255),
			array('blob', 65535),
			array('mediumblob', 16777215),
			array('longblob', 4294967295)
		);
	}

	public function providerDate()
	{
		return array(
			array('datetime'),
			array('date'),
			array('timestamp')
		);
	}

	public function providerDecimal()
	{
		return array(
			array('decimal', 15, 5),
			array('dec', 10, 0),
			array('numeric', 10, 0),
			array('fixed', 12, 2)
		);
	}

	public function providerDefaults()
	{
		return array(
			array('int', '100'),
			array('bool', '0'),
			array('fixed', '10.50'),
			array('timestamp', 'CURRENT_TIMESTAMP'),
			array('year', '2004'),
			array('char', 'CHAR'),
			array('varbinary', 'BINARY')
		);
	}

	public function providerFloat()
	{
		return array(
			array('float', 12),
			array('double', 22),
			array('double_precision', 22),
			array('real', 22)
		);
	}

	public function providerInt()
	{
		return array
		(
			array('primary', '0', '4294967295', 10, true),
			array('utiny',  '0', '255', 3, true),
			array('tiny', '-128', '127', 3, false),
			array('usmall','0', '65535', 5, true),
			array('small', '-32768', '32767', 5, false),
			array('umedium', '0', '16777215', 7, true),
			array('medium', '-8388608', '8388607', 7, false),
			array('uint', '0', '4294967295', 10, true),
			array('int', '-2147483648', '2147483647', 10, false),
			array('ubig', '0', '18446744073709551615', 20, true),
			array('big', '-9223372036854775808', '9223372036854775807', 19, false)
		);
	}

	public function providerString()
	{
		return array(
			array('char', 100),
			array('varchar', 250),
			array('tinytext', 255),
			array('text', 65535),
			array('mediumtext', 16777215),
			array('longtext', 4294967295)
		);
	}

	/**
	 * @expectedException \PDOException
	 */
	public function testConstructThrowsPDOExceptionOnConnection()
	{
		$this->object = new Mysql(
			array(
				'schema' => 'invalid',
				'host' => 'localhost',
				'password' => 'invalid',
				'user' => 'invalid',
				'type' => 'mysql'
			)
		);

		$meta = $this->object->load('types');

		$this->setExpectedException('\PDOException');
	}

	public function testPrimaryIsPrimary()
	{
		$this->assertSame(array('primary'), $this->primary);
	}

	public function testPrimaryKeySequenced()
	{
		$this->assertTrue($this->cols['primary']->sequenced);
	}

	public function testPrimaryKeyIsNotNullable()
	{
		$this->assertFalse($this->cols['primary']->null);
	}

	/**
	 * @param $col
	 * @param $length
	 * @dataProvider providerBinary
	 */
	public function testLoadBinary($col, $length)
	{
		$this->assertAttributeSame('binary', 'type', $this->cols[$col]);
		$this->assertAttributeSame($length, 'length', $this->cols[$col]);
	}

	/**
	 * @dataProvider providerDate
	 */
	public function testLoadDate($type)
	{
		$this->assertAttributeSame('date', 'type', $this->cols[$type]);
	}

	/**
	 * @param $col
	 * @param $precision
	 * @param $scale
	 * @dataProvider providerDecimal
	 */
	public function testLoadDecimal($col, $precision, $scale)
	{
		$this->assertAttributeSame('decimal', 'type', $this->cols[$col]);
		$this->assertAttributeSame($precision, 'length', $this->cols[$col]);
		$this->assertAttributeSame($scale, 'scale', $this->cols[$col]);
	}

	/**
	 *
	 */
	public function testLoadEnum()
	{
		$this->assertAttributeSame('enum', 'type', $this->cols['enum']);
		$this->assertAttributeSame(array('one', 'two', 'three'), 'values', $this->cols['enum']);
	}

	/**
	 * @param $col
	 * @param $length
	 * @dataProvider providerFloat
	 */
	public function testLoadFloat($col, $length)
	{
		$this->assertAttributeSame('float', 'type', $this->cols[$col]);
		$this->assertAttributeSame($length, 'length', $this->cols[$col]);
	}

	/**
	 * @dataProvider providerInt
	 * @requires pdo_mysql
	 */
	public function testLoadInt($col, $min, $max, $length, $unsigned)
	{
		$this->assertAttributeSame('int', 'type', $this->cols[$col]);
		$this->assertAttributeSame($min, 'min', $this->cols[$col]);
		$this->assertAttributeSame($max, 'max', $this->cols[$col]);
		$this->assertAttributeSame($length, 'length', $this->cols[$col]);
		$this->assertAttributeSame($unsigned, 'unsigned', $this->cols[$col]);
	}

	public function testLoadSet()
	{
		$this->assertAttributeSame('set', 'type', $this->cols['set']);
		$this->assertAttributeSame(array('one', 'two', 'three'), 'values', $this->cols['set']);
	}

	/**
	 * @param $col
	 * @param $length
	 * @dataProvider providerString
	 */
	public function testLoadString($col, $length)
	{
		$this->assertAttributeSame('string', 'type', $this->cols[$col]);
		$this->assertAttributeSame($length, 'length', $this->cols[$col]);
	}

	public function testLoadTime()
	{
		$this->assertAttributeSame('time', 'type', $this->cols['time']);
	}

	public function testColumnsCached()
	{
		$this->assertInternalType('array', \Gacela\Gacela::instance()->cacheMetaData('test_columns'));
	}

	/**
	 * @param $field
	 * @param $default
	 * @dataProvider providerDefaults
	 */
	public function testLoadDefaults($field, $default)
	{
		$this->assertAttributeSame($default, 'default', $this->cols[$field]);
	}
}

