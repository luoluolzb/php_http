<?php
namespace luoluolzb\http;

use luoluolzb\http\CookieItem;

/**
 * cookie集合类
 * @author luoluolzb <luoluolzb@163.com>
 */
class Cookie
{
	/**
	 * cookie数组
	 * 元素类型为luoluolzb\http\CookieItem
	 * @var Array
	 */
	protected $items;

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->items = [];
	}

	/**
	 * 解析请求头原始cookie值
	 * @param string $raw 请求头Cookie行原始值
	 */
	public function parseRequestRaw(string $raw): void
	{
		$keyVals = explode(';', $raw);
		if ($keyVals && $keyVals[0]) {
			foreach ($keyVals as &$keyVal) {
				list($name, $value) = explode('=', $keyVal);
				$name = trim($name);
				$value = trim($value);
				$this->items[$name] = new CookieItem($name, $value);
			}
		}
	}

	/**
	 * 生成响应头原始cookie值
	 * @param  string $timestamp 过期时间基准时间戳
	 * @return array             原始Set-Cookie值数组
	 */
	public function makeResponseRaws(int $timestamp = -1): array
	{
		if (-1 == $timestamp) {
			$timestamp = time();
		}
		$raws = [];
		foreach ($this->items as $item) {
			$raws[] = $item->makeResponseRaw($timestamp);
		}
		return $raws;
	}

	/**
	 * 获取全部cookie
	 * @return array CookieItem数组
	 */
	public function all(): array
	{
		return $this->items;
	}

	/**
	 * 获取一个cookie
	 * @param  string $name  cookie名称
	 * @return CookieItem|null
	 */
	public function get(string $name)
	{
		return $this->items[$name] ?? null;
	}
	
	/**
	 * 判断一个cookie是否存在
	 * @param  string $name  cookie名称
	 * @return bool          是否存在
	 */
	public function exists(string $name): bool
	{
		return isset($this->items[$name]);
	}

	/**
	 * 设置一个cookie值
	 * @param  CookieItem  $value  CookieItem
	 * @return Cookie              原cookie对象，方便链式操作
	 */
	public function set(CookieItem $item): Cookie
	{
		$this->items[$item->name] = $item;
		return $this;
	}

	/**
	 * 设置一个cookie值
	 * @param string $name  cookie名称
	 * @return Cookie       原cookie对象，方便链式操作
	 */
	public function delete(string $name): Cookie
	{
		unset($this->items[$name]);
		return $this;
	}
	
	/**
	 * 清空cookie
	 * @return Cookie 原cookie对象，方便链式操作
	 */
	public function clear(): Cookie
	{
		$this->items = [];
		return $this;
	}
}
