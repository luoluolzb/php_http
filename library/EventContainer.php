<?php
/**
 * 事件容器类
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\library;

class EventContainer
{
	/**
	 * 事件处理函数列表
	 * @var Array
	 */
	protected $handlers = [
		// 'eventName1' => [handler1, handler2, ...],
		// 'eventName2' => [handler1, handler2, ...],
		// ...
	];

	/**
	 * 构造函数
	 */
	public function __construct() {
		// some code
	}

	/**
	 * 绑定一个处理函数到指定事件
	 * @param string   $eventName 事件名称
	 * @param callable $handler   事件处理函数
	 */
	public function bind(string $eventName, callable $handler) {
		if (!isset($this->handlers[$eventName])) {
			$this->handlers[$eventName] = [];
		}
		array_push($this->handlers[$eventName], $handler);
	}

	/**
	 * 调用某个事件名的处理函数
	 * @param  string $eventName 事件名称
	 * @param  Array  $args      传给处理函数的参数
	 */
	public function trigger(string $eventName, ...$params) {
		if (isset($this->handlers[$eventName])) {
			foreach ($this->handlers[$eventName] as $handler) {
				call_user_func_array($handler, $params);
			}
		}
	}

	/**
	 * 判断某个事件是否有处理函数
	 * @param  string $eventName 事件名称
	 * @return bool              是否有处理函数
	 */
	public function exists(string $eventName = null): bool {
		return isset($this->handlers[$eventName]);
	}
}
