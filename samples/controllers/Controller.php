<?php
/** 
 * @author Noah Goodrich
 * @date 9/8/11
 * @brief
 * 
*/

class Controller {

	public $template;
	public $title;

	public function render()
	{
		ob_start();
		
		require('views/'.$this->template.'.php');

		$content = ob_get_contents();

		ob_end_clean();

		return array($content, $this->title);
	}
}
