<?php
/**
 * http响应类
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\http;
use luoluolzb\http\{Header, Body};
use luoluolzb\http\Request;

class Response
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
	 * 响应cookie
	 * @var luoluolzb\http\Cookie
	 */
	public $cookie;

	/**
	 * 状态码
	 * @var int
	 */
	protected $statusCode;

	/**
	 * 构造函数
	 * @param  luoluolzb\http\Request $requst 请求对象
	 */
	public function __construct(Request $request) {
		$this->header = new Header();
		$this->body = new Body();
		$this->cookie = clone $request->cookie;
		$this->statusCode = 200;
	}

	/**
	 * 生成原始响应内容
	 * @return string 响应内容
	 */
	public function makeResponseRaw(): string {
		// 设置服务器标志
		$this->header->set('Server', "luoluolzb's HttpServer");
		
		// 设置cookie
		$raws = $this->cookie->makeResponseRaws();
		foreach ($raws as &$raw) {
			$this->header->set('Set-Cookie', $raw, true);
		}
		
		// 设置正文长度
		$this->header->set('Content-Length', $this->body->length());

		return $this->header->makeResponseRaw($this->statusCode)
		. "\r\n" . $this->body->content();
	}

	/**
	 * 设置状态码
	 * @param  int    $code 状态码
	 */
	public function statusCode(int $code) {
		$this->statusCode = $code;
	}
}
