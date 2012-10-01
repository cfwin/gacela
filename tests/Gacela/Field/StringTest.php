<?php
namespace Gacela\Field;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-29 at 20:14:17.
 */
class StringTest extends \PHPUnit_Framework_TestCase
{

	protected $object;

	protected function setUp()
	{
		$this->object = (object) array(
			'type' => 'string',
			'length' => 10,
			'null' => false
		);
	}

    /**
     * @covers Gacela\Field\String::validate
     * @todo   Implement testValidate().
     */
    public function testValidateNull()
    {
		$this->assertEquals(String::NULL_CODE, String::validate($this->object, null));
    }

	public function testValidateLength()
	{
		$this->assertEquals(String::LENGTH_CODE, String::validate($this->object, 'This is a string longer than 10 characters.'));
	}

    /**
     * @covers Gacela\Field\String::transform
     * @todo   Implement testTransform().
     */
    public function testTransform()
    {
		$string = 'I am a very fine string';

        $this->assertEquals($string, String::transform($this->object, $string));
    }
}