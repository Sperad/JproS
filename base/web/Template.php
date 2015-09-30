<?php
namespace base\web;

class Template extends WebObject{
	private $fromPath ;
	private $toPath ;
	private $cacheTplFile;
	private $tplFile;
	private $val;

	function __construct($viewPath,$val)
	{
		$this->toPath   = APP_DIR.CACHE_TPL_DIR.TPL_DIR.$viewPath.CACHE_TPL_EXT;
		$this->fromPath = APP_DIR.              TPL_DIR.$viewPath.VIEW_EXT;
		$this->val 		= $val;
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
		preg_match_all($pattern, $this->tplFile, $pathList);
		//去掉 前@后# 符号
		$pathList =$pathList[0];
		for ($i=0; $i<count($pathList); $i++)
		{ 
			$path = substr($pathList[$i],1,strlen($pathList[$i])-2);
			//解析文件导入的加载或替换
			$staticDir = WWW_DIR==='' ? STATIC_DIR : '../'.STATIC_DIR;
			if(stripos($pathList[$i],'js') == 1){
				$replacement = '<script type="text/javascript" src="'.$staticDir.'/'.$path.'"" ></script>';			
			}elseif(stripos($pathList[$i],'css') == 1){
				$replacement = '<link rel="stylesheet" type="text/css" href="'.$staticDir.'/'.$path.'">';
			}else{
				$replacement = "<?php include '".APP_DIR.STATIC_DIR.'/'.$path."'; ?>";
			}
			$this->tplFile = str_replace($pathList[$i], $replacement, $this->tplFile);
		}
		//解析变量标签
		$this->parseVal();
	}

    /**
	* 解析模板
	* @param $str  模板内容  
	* @return ture  
	*/  
	private function parseVal()
	{
		$str = $this->tplFile;
	    //变量
		//if elseif 判断
		$str = preg_replace("/#if\s+(.+?)@/", 	    "<?php if(\\1) {    	?>", $str);  
		$str = preg_replace("/#else@/", 			"<?php } else {     	?>", $str);  
		$str = preg_replace("/#elseif\s+(.+?)@/", 	"<?php } elseif (\\1) { ?>", $str);  
		$str = preg_replace("/#if\/@/", 			"<?php } 				?>", $str);  
	    //for 循环 
	    $str = preg_replace("/#for\s+(.+?)@/",    	"<?php for(\\1) { 		?>", $str);  
	    $str = preg_replace("/#for\/@/",			"<?php}?>", $str);  
	    //foreach 循环 
	    $str = preg_replace("/#foreach\s+(\S+)\s+(\S+)@/", 			"<?php if(is_array(\\$\\1)) foreach(\\$\\1 AS \\$\\2) 	   { ?>", $str );
	    $str = preg_replace("/#foreach\s+(\S+)\s+(\S+)\s+(\S+)@/", 	"<?php if(is_array(\\$\\1)) foreach(\\$\\1 AS \\$\\2  =>\\$\\3){?>", $str );  
	    $str = preg_replace("/#foreach\/@/", 						"<?php } ?>", $str);  
	    //++ --  
	    $str = preg_replace("/#\+\+(.+?)@/",		"<?php ++\\1; 			?>", $str);  
	    $str = preg_replace("/#\-\-(.+?)@/",		"<?php ++\\1; 			?>", $str);  
	    $str = preg_replace("/#(.+?)\+\+@/",		"<?php \\1++; 			?>", $str);  
	    $str = preg_replace("/#(.+?)\-\-@/",		"<?php \\1--; 			?>", $str);
	    $str = preg_replace("/#([a-zA-Z0-9_\x7f-\xff]*)@/",	"<?php echo \$\\1;	?>", $str);//输出值--支持中文变量
	    $this->tplFile = $str;
	    var_dump($str);
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
		   //eval('>'.$this->tplFile);
		    // echo htmlspecialchars_decode($this->tplFile);
		}
	}

}