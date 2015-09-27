<?php
namespace base\core;

class Template extends Object{
	private $fromPath ;
	private $toPath ;
	private $cacheTplFile;
	private $tplFile;

	function __construct($viewPath)
	{
		$this->toPath   = APP_DIR.CACHE_TPL_DIR.TPL_DIR.$viewPath.CACHE_TPL_EXT;
		$this->fromPath = APP_DIR.              TPL_DIR.$viewPath.VIEW_EXT;
		$this->parseTpl();
		IS_USE_CACHE_TPL and $this->createCacheTplFile();
		$this->outPage();
	}

	/**
	 * [parseTpl 将源模板通过正则替换解析]
	 * 模板 引用文件 example： @base/common/header#
	 */
	private function parseTpl()
	{
		$pattern = "/@[a-zA-Z\/\.]+#/";

		$this->tplFile = file_get_contents($this->fromPath) or die('模板 源文件 不存在');
		//正则 找到 文件 路径
		preg_match_all($pattern, $this->tplFile, $fileList);
		//去掉 前@后# 符号
		for ($i=count($fileList = $fileList[0]); $i-->0;)
		{ 
			$filePath = substr($fileList[$i],1,strlen($fileList[$i])-2);
			$replacement = "<?php include '".APP_DIR.STATIC_DIR.'/'.$filePath."'; ?>";
			$this->tplFile = preg_replace($pattern, $replacement, $this->tplFile);
		}
	}

	private function createCacheTplFile()
	{
		//判断文件是否存在
		if(!file_exists($this->toPath))
		{
			$parts = explode('/', $this->toPath);
	        $file = array_pop($parts);
	        $tmpDir = '';
	        for($i =0; $i<count($parts);$i++){
	        	// $dir = $parts[$i];
	        	$tmpDir .= $parts[$i].'/';
	            if(!is_dir($tmpDir)){
		            $result = mkdir($tmpDir) or die('文件夹创建失败');	
	            }
	        }
	        file_put_contents($tmpDir."/$file", $this->tplFile) or die('缓存文件生成失败');
		}
	}

	private function outPage()
	{
		if(IS_USE_CACHE_TPL){
			include $this->toPath;
		}else{
		   //eval 是在你当前程序代码处嵌入PHP代码，所以你需要将当前程序代码结束掉才可以。
		   echo eval('?>'.$this->tplFile );
		}
	}

}