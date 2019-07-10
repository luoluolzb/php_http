<?php
/**
 * 配置类
 * 每个配置项为键值对
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\library;

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
	public function __construct(string $confFilePath = null) {
		if ($confFilePath) {
			$this->load($confFilePath);
		}
		$this->setAccessArray($this->data);
	}

	/**
	 * 加载一个配置
	 * @param string $confFilePath 配置文件路径
	 */
	public function load(string $confFilePath) {
		if (file_exists($confFilePath)) {
			$fileData = include($confFilePath);
			$this->data = array_merge($this->data, $fileData);
		}
		$this->setAccessArray($this->data);
	}
}
