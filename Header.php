<?php
/**
 * http头部类
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\http;
use luoluolzb\http\StatusCode;

class Header
{
	/**
	 * 请求方法（请求行第一个参数）
	 * @var string
	 */
	public $method;
	
	/**
	 * 请求路径（请求行第二个参数）
	 * @var string
	 */
	public $path;

	/**
	 * 请求协议/版本（请求行第三个参数）
	 * @var string
	 */
	public $protocol;
	
	/**
	 * 头部参数（键值对）
	 * 如果一个参数有多个值那么元素类型为数组
	 * 一个参数只有一个值则元素类型为字符串
	 * @var Array
	 */
	public $lines;

	/**
	 * 构造函数
	 */
	public function __construct() {
		$this->lines = [];
	}

	/**
	 * 解析原始请求头部内容
	 * @param  $rawHeader 原始请求头内容
	 */
	public function parseRequestRaw(string $rawHeader) {
		// 按行分割头部内容
		$rawLine = explode("\r\n", $rawHeader);
		
		// 解析请求行
		list($this->method, $this->path, $this->protocol) 
		= explode(' ', $rawLine[0]);

		// 解析请求头部参数
		for ($i = 1; isset($rawLine[$i]); ++ $i) {
			$pos = strpos($rawLine[$i], ":");
			$name = trim(substr($rawLine[$i], 0, $pos));
			$value = trim(substr($rawLine[$i], $pos + 1));
			$this->lines[$name] = $value;
		}
	}

	/**
	 * 生成响应头原始内容
	 * @param  int $statusCode 状态码
	 * @return string          响应头原始内容
	 */
	public function makeResponseRaw(int $statusCode): string {
		$protocol = $this->protocol ?: 'HTTP/1.1';
		$desc = StatusCode::getDesc($statusCode);
		
		// 响应行
		$raw = "{$protocol} {$statusCode} {$desc}\r\n";

		// 响应头参数
		foreach ($this->lines as $name => &$value) {
			if (!is_array($value)) {
				$raw .= "{$name}: {$value}\r\n";
			} else { // set-cookie行有多个
				foreach ($value as &$val) {
					$raw .= "{$name}: {$val}\r\n";
				}
			}
		}
		return $raw;
	}

	/**
	 * 设置头部行参数
	 * @param  string  $name  参数名
	 * @param  string  $value 参数值
	 * @param  bool    $multi 此参数允许有多个值(如Set-Cookie)
	 * @return Header         原始Header对象，用于链式操作
	 */
	public function set(string $name, string $value, bool $multi = false) {
		$value = trim($value);
		if (!$multi) { // 不允许多个值
			$this->lines[$name] = $value;
		} else { // 允许多个值
			if (isset($this->lines[$name])) { //已设置
				$tmp = $this->lines[$name];
				if (is_array($this->lines[$name])) {
					$this->lines[$name][] = $value;
				} else {
					$this->lines[$name] = [$tmp, $value];
				}
			} else { //未设置
				$this->lines[$name] = $value;
			}
		}
		return $this;
	}

	/**
	 * 获取头部行参数
	 * @param  string       $name  参数名
	 * @return string|array         获取的参数值
	 */
	public function get(string $name) {
		return $this->lines[$name] ?? '';
	}
}
