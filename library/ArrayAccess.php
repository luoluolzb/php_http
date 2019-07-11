<?php
namespace luoluolzb\library;

/**
 * 数组快速访问trait
 * 可以使用点号分割多级数组名来快速访问，例如
 * array[a][b][c] => $ArrayAccess->get('a.b.c')
 * @author luoluolzb <luoluolzb@163.com>
 */
trait ArrayAccess
{
	/**
	 * 需要被访问的数组
	 * @var array
	 */
	protected $accessArray;

	/**
	 * 绑定被访问的数组引用
	 * 应该在使用此 trai 的类调用此函数绑定
	 * @param array $accessArray 被访问的数组
	 */
	protected function bindAccessArray(array &$accessArray): void
	{
		$this->accessArray = &$accessArray;
	}

	/**
	 * 获取一个值
	 * @param  string $name 键名
	 * @return mixed
	 */
	public function get(string $name = '')
	{
		$data = & $this->accessArray;
		if (empty($name)) {
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
			return $arr[$name] ?? null;
		}
	}

	/**
	 * 设置一个值
	 * @param  string $name  键名
	 * @param  mixed  $value 值
	 * @param  bool   $merge 如果为数组是否合并
	 * @return bool
	 */
	public function set(string $name, $value, bool $merge = false): bool
	{
		$data = & $this->accessArray;
		$keys = explode('.', $name);
		if (is_array($keys)) {
			foreach ($keys as $i => $key) {
				if (!isset($data[$key])) {
					$data[$key] = [];
				}
				$data = & $data[$key];
			}
			$data = $merge ? array_merge($data, $value) : $value;
			return true;
		} else {
			$data[$name] = $value;
			return true;
		}
	}
}
