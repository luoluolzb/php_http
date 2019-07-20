<?php
namespace luoluolzb\library;

/**
 * 配置类
 * 每个配置项为键值对
 *
 * @author luoluolzb <luoluolzb@163.com>
 */
class Config
{
    use ArrayAccess;

    /**
     * 配置数据
     *
     * @var array
     */
    public $data;

    /**
     * 构造函数
     *
     * @param  string|array|null $conf 配置文件路径或者配置数组
     * @throws \InvalidArgumentException  参数错误
     */
    public function __construct($conf = null)
    {
        $this->data = [];
        $this->bindAccessArray($this->data);
        if (isset($conf)) {
            if (is_string($conf)) {
                $this->loadFromFile($conf);
            } elseif (is_array($conf)) {
                $this->loadFromArray($conf);
            } else {
                throw new \InvalidArgumentException("Invalid Argument");
            }
        }
    }

    /**
     * 从配置文件加载一个配置
     *
     * @param string $confFilePath 配置文件路径
     *
     * @return bool 加载是否成功
     */
    public function loadFromFile(string $confFilePath): bool
    {
        if (file_exists($confFilePath)) {
            return $this->loadFromArray(include $confFilePath);
        } else {
            return false;
        }
    }

    /**
     * 从配置数组加载一个配置
     *
     * @param array $conf 配置数组
     *
     * @return bool 加载是否成功
     */
    public function loadFromArray(array $conf): bool
    {
        $this->data = array_merge($this->data, $conf);
        $this->bindAccessArray($this->data);
        return true;
    }
}
