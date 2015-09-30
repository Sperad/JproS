<?php
namespace base\web;

class HTTP extends WebObject{

	private $url;
	private $method;
	private $body;
	private $isAjax = false;

	public function __construct()
	{
		$this->url    = new URL();
		$this->body   = file_get_contents('php://input');
		$this->method = $_SERVER['REQUEST_METHOD'];
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && 
				strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
			$this->isAjax = true;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getIsAjax(){
		return $this->isAjax;
	}
	public function getBody(){
		return $this->body;
	}

	public function getMethod()
	{
		return $this->method;
	}
	
	function setRequest($appendHeader = null)
	{
		$defaultHeader = array(
				'User-Agent' =>
						"Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12\r\n",
				'Accept' =>
						"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n",
				'Accept-language' => "zh-cn,zh;q=0.5\r\n",
				'Accept-Charset'  => "GB2312,utf-8;q=0.7,*;q=0.7\r\n",
			);
	}

	function getResponse()
	{
		//获取请求url,后返回的
	}

	function getRequest()
	{
		//获取请求当前路径的
	}

	function setResponse()
	{
		//设置返回给用户的Header
	}
}

/**
* 获取文件的mime_content类型
*/
function mime_type($filename) {
    static $contentType = array(
		'ai'      => 'application/postscript',
		'aif'     => 'audio/x-aiff',
		'aifc'    => 'audio/x-aiff',
		'aiff'    => 'audio/x-aiff',
		'asc'     => 'application/pgp', // changed by skwashd - was text/plain
		'asf'     => 'video/x-ms-asf',
		'asx'     => 'video/x-ms-asf',
		'au'      => 'audio/basic',
		'avi'     => 'video/x-msvideo',
		'bcpio'   => 'application/x-bcpio',
		'bin'     => 'application/octet-stream',
		'bmp'     => 'image/bmp',
		'c'       => 'text/plain', // or 'text/x-csrc', //added by skwashd
		'cc'      => 'text/plain', // or 'text/x-c++src', //added by skwashd
		'cs'      => 'text/plain', // added by skwashd - for C# src
		'cpp'     => 'text/x-c++src', // added by skwashd
		'cxx'     => 'text/x-c++src', // added by skwashd
		'cdf'     => 'application/x-netcdf',
		'class'   => 'application/octet-stream', // secure but application/java-class is correct
		'com'     => 'application/octet-stream', // added by skwashd
		'cpio'    => 'application/x-cpio',
		'cpt'     => 'application/mac-compactpro',
		'csh'     => 'application/x-csh',
		'css'     => 'text/css',
		'csv'     => 'text/comma-separated-values', // added by skwashd
		'dcr'     => 'application/x-director',
		'diff'    => 'text/diff',
		'dir'     => 'application/x-director',
		'dll'     => 'application/octet-stream',
		'dms'     => 'application/octet-stream',
		'doc'     => 'application/msword',
		'dot'     => 'application/msword', // added by skwashd
		'dvi'     => 'application/x-dvi',
		'dxr'     => 'application/x-director',
		'eps'     => 'application/postscript',
		'etx'     => 'text/x-setext',
		'exe'     => 'application/octet-stream',
		'ez'      => 'application/andrew-inset',
		'gif'     => 'image/gif',
		'gtar'    => 'application/x-gtar',
		'gz'      => 'application/x-gzip',
		'h'       => 'text/plain', // or 'text/x-chdr',//added by skwashd
		'h++'     => 'text/plain', // or 'text/x-c++hdr', //added by skwashd
		'hh'      => 'text/plain', // or 'text/x-c++hdr', //added by skwashd
		'hpp'     => 'text/plain', // or 'text/x-c++hdr', //added by skwashd
		'hxx'     => 'text/plain', // or 'text/x-c++hdr', //added by skwashd
		'hdf'     => 'application/x-hdf',
		'hqx'     => 'application/mac-binhex40',
		'htm'     => 'text/html',
		'html'    => 'text/html',
		'ice'     => 'x-conference/x-cooltalk',
		'ics'     => 'text/calendar',
		'ief'     => 'image/ief',
		'ifb'     => 'text/calendar',
		'iges'    => 'model/iges',
		'igs'     => 'model/iges',
		'jar'     => 'application/x-jar', // added by skwashd - alternative mime type
		'java'    => 'text/x-java-source', // added by skwashd
		'jpe'     => 'image/jpeg',
		'jpeg'    => 'image/jpeg',
		'jpg'     => 'image/jpeg',
		'js'      => 'application/x-javascript',
		'kar'     => 'audio/midi',
		'latex'   => 'application/x-latex',
		'lha'     => 'application/octet-stream',
		'log'     => 'text/plain',
		'lzh'     => 'application/octet-stream',
		'm3u'     => 'audio/x-mpegurl',
		'man'     => 'application/x-troff-man',
		'me'      => 'application/x-troff-me',
		'mesh'    => 'model/mesh',
		'mid'     => 'audio/midi',
		'midi'    => 'audio/midi',
		'mif'     => 'application/vnd.mif',
		'mov'     => 'video/quicktime',
		'movie'   => 'video/x-sgi-movie',
		'mp2'     => 'audio/mpeg',
		'mp3'     => 'audio/mpeg',
		'mpe'     => 'video/mpeg',
		'mpeg'    => 'video/mpeg',
		'mpg'     => 'video/mpeg',
		'mpga'    => 'audio/mpeg',
		'ms'      => 'application/x-troff-ms',
		'msh'     => 'model/mesh',
		'mxu'     => 'video/vnd.mpegurl',
		'nc'      => 'application/x-netcdf',
		'oda'     => 'application/oda',
		'patch'   => 'text/diff',
		'pbm'     => 'image/x-portable-bitmap',
		'pdb'     => 'chemical/x-pdb',
		'pdf'     => 'application/pdf',
		'pgm'     => 'image/x-portable-graymap',
		'pgn'     => 'application/x-chess-pgn',
		'pgp'     => 'application/pgp', // added by skwashd
		'php'     => 'application/x-httpd-php',
		'php3'    => 'application/x-httpd-php3',
		'pl'      => 'application/x-perl',
		'pm'      => 'application/x-perl',
		'png'     => 'image/png',
		'pnm'     => 'image/x-portable-anymap',
		'po'      => 'text/plain',
		'ppm'     => 'image/x-portable-pixmap',
		'ppt'     => 'application/vnd.ms-powerpoint',
		'ps'      => 'application/postscript',
		'qt'      => 'video/quicktime',
		'ra'      => 'audio/x-realaudio',
		'rar'     => 'application/octet-stream',
		'ram'     => 'audio/x-pn-realaudio',
		'ras'     => 'image/x-cmu-raster',
		'rgb'     => 'image/x-rgb',
		'rm'      => 'audio/x-pn-realaudio',
		'roff'    => 'application/x-troff',
		'rpm'     => 'audio/x-pn-realaudio-plugin',
		'rtf'     => 'text/rtf',
		'rtx'     => 'text/richtext',
		'sgm'     => 'text/sgml',
		'sgml'    => 'text/sgml',
		'sh'      => 'application/x-sh',
		'shar'    => 'application/x-shar',
		'shtml'   => 'text/html',
		'silo'    => 'model/mesh',
		'sit'     => 'application/x-stuffit',
		'skd'     => 'application/x-koan',
		'skm'     => 'application/x-koan',
		'skp'     => 'application/x-koan',
		'skt'     => 'application/x-koan',
		'smi'     => 'application/smil',
		'smil'    => 'application/smil',
		'snd'     => 'audio/basic',
		'so'      => 'application/octet-stream',
		'spl'     => 'application/x-futuresplash',
		'src'     => 'application/x-wais-source',
		'stc'     => 'application/vnd.sun.xml.calc.template',
		'std'     => 'application/vnd.sun.xml.draw.template',
		'sti'     => 'application/vnd.sun.xml.impress.template',
		'stw'     => 'application/vnd.sun.xml.writer.template',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc'  => 'application/x-sv4crc',
		'swf'     => 'application/x-shockwave-flash',
		'sxc'     => 'application/vnd.sun.xml.calc',
		'sxd'     => 'application/vnd.sun.xml.draw',
		'sxg'     => 'application/vnd.sun.xml.writer.global',
		'sxi'     => 'application/vnd.sun.xml.impress',
		'sxm'     => 'application/vnd.sun.xml.math',
		'sxw'     => 'application/vnd.sun.xml.writer',
		't'       => 'application/x-troff',
		'tar'     => 'application/x-tar',
		'tcl'     => 'application/x-tcl',
		'tex'     => 'application/x-tex',
		'texi'    => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tgz'     => 'application/x-gtar',
		'tif'     => 'image/tiff',
		'tiff'    => 'image/tiff',
		'tr'      => 'application/x-troff',
		'tsv'     => 'text/tab-separated-values',
		'txt'     => 'text/plain',
		'ustar'   => 'application/x-ustar',
		'vbs'     => 'text/plain', // added by skwashd - for obvious reasons
		'vcd'     => 'application/x-cdlink',
		'vcf'     => 'text/x-vcard',
		'vcs'     => 'text/calendar',
		'vfb'     => 'text/calendar',
		'vrml'    => 'model/vrml',
		'vsd'     => 'application/vnd.visio',
		'wav'     => 'audio/x-wav',
		'wax'     => 'audio/x-ms-wax',
		'wbmp'    => 'image/vnd.wap.wbmp',
		'wbxml'   => 'application/vnd.wap.wbxml',
		'wm'      => 'video/x-ms-wm',
		'wma'     => 'audio/x-ms-wma',
		'wmd'     => 'application/x-ms-wmd',
		'wml'     => 'text/vnd.wap.wml',
		'wmlc'    => 'application/vnd.wap.wmlc',
		'wmls'    => 'text/vnd.wap.wmlscript',
		'wmlsc'   => 'application/vnd.wap.wmlscriptc',
		'wmv'     => 'video/x-ms-wmv',
		'wmx'     => 'video/x-ms-wmx',
		'wmz'     => 'application/x-ms-wmz',
		'wrl'     => 'model/vrml',
		'wvx'     => 'video/x-ms-wvx',
		'xbm'     => 'image/x-xbitmap',
		'xht'     => 'application/xhtml+xml',
		'xhtml'   => 'application/xhtml+xml',
		'xls'     => 'application/vnd.ms-excel',
		'xlt'     => 'application/vnd.ms-excel',
		'xml'     => 'application/xml',
		'xpm'     => 'image/x-xpixmap',
		'xsl'     => 'text/xml',
		'xwd'     => 'image/x-xwindowdump',
		'xyz'     => 'chemical/x-xyz',
		'z'       => 'application/x-compress',
		'zip'     => 'application/zip',
    );

	$type = strtolower(substr(strrchr($filename, '.'), 1));
 	if(array_key_exists($type, $contentType))
 	    return $contentType[$type];
	    return 'application/octet-stream';
}
