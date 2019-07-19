<?php
namespace luoluolzb\http;

/**
 * cookie项类（一个cookie）
 * cookie说明：https://www.jianshu.com/p/2ceeaef92f20
 * @author luoluolzb <luoluolzb@163.com>
 */
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
    public $domain;
    
    /**
     * 有效路径
     * @var string
     */
    public $path;
    
    /**
     * 过期时间（秒）
     * 需要转换为GMT格式
     * @var int
     */
    public $expires;
    
    /**
     * 仅在加密传输中使用
     * @var bool
     */
    public $secure;
    
    /**
     * 仅在http/https传输中使用
     * @var bool
     */
    public $httpOnly;

    /**
     * 构造函数
     * @param string $name cookie名称
     */
    public function __construct(string $name, string $value = '')
    {
        $this->name = $name;
        $this->value = $value;
        $this->domain = '';
        $this->path = '/';
        $this->expires = 3600*12;
        $this->secure = false;
        $this->httpOnly = false;
    }

    /**
     * 设置响应Set-Cookie行原始值
     * @param  string $timestamp 过期时间基准时间戳
     * @return string            Set-Cookie行原始值
     */
    public function makeResponseRaw(int $timestamp = -1): string
    {
        $raw = $this->name . '=' . $this->value;
        $raw .= '; Domain=' . $this->domain;
        $raw .= '; Path=' . $this->path;
        if ($this->expires) {
            if (-1 == $timestamp) {
                $timestamp = time();
            }
            $expires = gmdate("l d F Y H:i:s \G\M\T", $timestamp + $this->expires);
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
