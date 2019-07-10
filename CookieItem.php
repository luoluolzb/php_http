<?php
/**
 * cookie项类（一个cookie）
 * cookie说明：https://www.jianshu.com/p/2ceeaef92f20
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\http;

class CookieItem
{
	/**
	 * cookie名称
	 * @var string
	 */
	public $name;
	
	/**
	 * cookie值
	 * @var string
	 */
	public $value;

	/**
	 * 有效域名
	 * @var string
	 */
	public $domain = '';
	
	/**
	 * 有效路径
	 * @var string
	 */
	public $path = '/';
	
	/**
	 * 过期时间（秒）
	 * 需要转换为GMT格式
	 * @var int
	 */
	public $expires = 3600*12;
	
	/**
	 * 仅在加密传输中使用
	 * @var bool
	 */
	public $secure = false;
	
	/**
	 * 仅在http/https传输中使用
	 * @var bool
	 */
	public $httpOnly = false;

	/**
	 * 构造函数
	 * @param string $name cookie名称
	 */
	public function __construct(string $name, string $value = '') {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * 设置响应Set-Cookie行原始值
	 * @return string Set-Cookie行原始值
	 */
	public function getResponseRaw(): string {
		$raw = $this->name . '=' . $this->value;
		$raw .= '; Domain=' . $this->domain;
		$raw .= '; Path=' . $this->path;
		if ($this->expires) {
			$expires = gmdate("l d F Y H:i:s \G\M\T", time() + $this->expires);
			$raw .= '; Expires=' . $expires;
		}
		if ($this->secure) {
			$raw .= '; Secure=' . $this->secure;
		}
		if ($this->httpOnly) {
			$raw .= '; HttpOnly=' . $this->httpOnly;
		}
		return $raw;
	}
}
