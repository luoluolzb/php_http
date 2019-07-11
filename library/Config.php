<?php
namespace luoluolzb\library;

/**
 * 配置类
 * 每个配置项为键值对
 * @author luoluolzb <luoluolzb@163.com>
 */
class Config
{
	use ArrayAccess;

	/**
	 * 配置数据
	 * @var array
	 */
	public $data = [];

	/**
	 * 构造函数
	 * @param  配置文件路径
	 */
	public function __construct(string $confFilePath = null)
	{
		if ($confFilePath) {
			$this->load($confFilePath);
		}
		$this->bindAccessArray($this->data);
	}

	/**
	 * 加载一个配置
	 * @param string $confFilePath 配置文件路径
	 */
	public function load(string $confFilePath): bool
	{
		if (file_exists($confFilePath)) {
			$fileData = include($confFilePath);
			$this->data = array_merge($this->data, $fileData);
			$this->bindAccessArray($this->data);
			return true;
		} else {
			return false;
		}
	}
}
