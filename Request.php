<?php
/**
 * http请求类
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\http;
use luoluolzb\http\{Header, Body, Cookie};

class Request
{
	/**
	 * 请求头
	 * @var luoluolzb\http\Header
	 */
	public $header;
	
	/**
	 * 请求正文
	 * @var luoluolzb\http\Body
	 */
	public $body;
	
	/**
	 * 请求cookie
	 * @var luoluolzb\http\Cookie
	 */
	public $cookie;

	/**
	 * 请求路径
	 * @var string
	 */
	public $path;

	/**
	 * 请求方法
	 * @var string
	 */
	public $method;
	
	/**
	 * 请求url
	 * @var string
	 */
	public $url;
	
	/**
	 * url请求参数
	 * @var string
	 */
	public $queryStr;

	/**
	 * 请求是否可以正常解析
	 * @var bool
	 */
	protected $ok;
	
	/**
	 * 构造函数
	 * @param string $raw 原始请求内容
	 */
	public function __construct() {
		$this->header = new Header();
		$this->body = new Body();
		$this->cookie = new Cookie();
		$this->ok = false;
	}

	/**
	 * 解析请求内容
	 * @param  string $raw 原始请求内容
	 * @return bool 请求是否正常
	 */
	public function parseRequestRaw(string $raw) {
		$pos = strpos($raw, "\r\n\r\n");
		if ($pos === false) {
			return $this->ok = false;
		}
		// 分离头部和正文
		$rawHeader = substr($raw, 0, $pos);
		$rawBody = substr($raw, $pos + 4);
		
		// 解析头部
		$this->header->parseRequestRaw($rawHeader);

		// 获取头部的信息
		$this->path = $this->header->path ?: '/';
		$this->method = $this->header->method;
		$this->protocol = $this->header->protocol;

		// 获取请求路径、url查询串、url请求参数
		$this->query = [];
		$pos = strpos($this->header->path, '?');
		if ($pos !== false) {
			$this->path = substr($this->header->path, 0, $pos);
			$this->queryStr = substr($this->header->path, $pos + 1);
			// 获取url请求参数
			$kvs = explode('&', urldecode($this->queryStr));
			foreach ($kvs as &$kv) {
				list($name, $value) = explode('=', $kv);
				$this->query[$name] = $value;
			}
		} else {
			$this->path = $this->header->path;
			$this->queryStr = '';
		}
		// 获取url
		$this->url = 'http://' . $this->header->get('Host') . $this->header->path;
		
		// 解析cookie
		$this->cookie->parseRequestRaw($this->header->get('Cookie'));

		// 解析正文
		$this->body->content($rawBody);
		return $this->ok = true;
	}

	/**
	 * 请求是否正常
	 * @return bool 请求是否正常
	 */
	public function isOk(): bool {
		return $this->ok;
	}
}
