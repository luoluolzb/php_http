<?php
/**
 * 数组快速访问trait
 * 可以使用点号分割多级数组名来快速访问，例如
 * array[a][b][c] => $ArrayAccess->get('a.b.c')
 * @author      luoluolzb
 */
namespace luoluolzb\library;

trait ArrayAccess
{
	/**
	 * 需要被访问的数组
	 * @var array
	 */
	protected $_array;

	/**
	 * 设置被访问的数组引用
	 * 应该在使用此trai的类先调用此函数
	 * @param array $array 被访问的数组
	 */
	protected function setAccessArray(array &$array) {
		$this->_array = &$array;
	}

	/**
	 * 设置或获取值
	 * @param  string $name  键名
	 * @param  mixed  $value 值
	 * @return mixed
	 */
	public function access($name = null, $value = null, $merge = false) {
		if (isset($value)) {
			return $this->set($name, $value, $merge);
		} else {
			return $this->set($name);
		}
	}

	/**
	 * 获取一个值
	 * @param  string $name 键名
	 * @return mixed
	 */
	public function get($name = null) {
		$data = & $this->_array;
		if (!isset($name)) {
			return $data;
		}
		$keys = explode('.', $name);
		if (is_array($keys)) {
			foreach ($keys as $i => $key) {
				if (isset($data[$key])) {
					$data = & $data[$key];
				} else {
					return null;
				}
			}
			return $data;
		} else {
			return @$arr[$name];
		}
	}

	/**
	 * 设置一个值
	 * @param  string $name  键名
	 * @param  mixed  $value 值
	 * @return boolean
	 */
	public function set($name, $value, $merge = false) {
		$data = & $this->_array;
		$keys = explode('.', $name);
		if (is_array($keys)) {
			foreach ($keys as $i => $key) {
				if (!isset($data[$key])) {
					$data[$key] = [];
				}
				$data = & $data[$key];
			}
			if (!$merge) {
				$data = $value;
			} else {
				$data = array_merge($data, $value);
			}
			return true;
		} else {
			$data[$name] = $value;
			return true;
		}
	}
}
