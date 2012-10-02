<?php
/**
 * @author Noah Goodrich
 * @date May 12, 2011
 * Borrowing from this implementation for Doctrine2:
 * https://gist.github.com/525030/38a0dd6a70e58f39e964ec53c746457dd37a5f58
 *
 *
 *
*/

namespace Gacela\Field;

class Binary extends Field
{

	/**
	 * @static
	 * @param  $value
	 * @return bool
	 */
	public static function validate($meta, $value)
	{
		if(empty($value) && !$meta->null) {
			return static::NULL_CODE;
		}

		return true;
	}

	/**
	 * @static
	 * @param  $value
	 * @param bool $in
	 * @return mixed
	 */
	public static function transform($meta, $value, $in = true)
	{
		if(is_null($value)) {
			return $value;
		}

		if($in) {
			return base64_encode($value);
		} else {
			return base64_decode($value);
		}
	}
}