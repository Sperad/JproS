<?php
namespace base;

class View
{
	protected $var;
	protected $view;
	public function __construct($path='', $var=array())
	{
		$this->var = $var;
		if(!$path){
			echo '没指定View';
			return ;
		}
		$this->view = file_get_contents(TPL_DIR."/$path.html");
		$this->outPage();
	}

	public function outPage()
	{
		extract($this->var, EXTR_OVERWRITE);
		eval('?>'.$this->view);
	}
}