<?php 

class Language
{

	private $l;
	private $ini;

	public function __construct()
	{

		$this->l = 'default_lang';
		if(!empty($_SESSION['lang']) && file_exists('lang/'.$_SESSION['lang'].'.ini')){
			$this->l = $_SESSION['lang'];
		}
		$this->ini = parse_ini_file('lang/'.$this->l.'.ini');
	}
	
	public function get($word, $return = false)
	{
		if(isset($this->ini[$word])){
			$word = $this->ini[$word];
		}
		if($return){
			return $word;
		}else{
			echo $word;

		}

	}

}