<?php
namespace luoluolzb\http;

use luoluolzb\http\Header;
use luoluolzb\http\Body;
use luoluolzb\http\Cookie;

/**
 * http请求类
 *
 * @author luoluolzb <luoluolzb@163.com>
 */
class Request
{
    /**
     * 请求头
     *
     * @var Header
     */
    public $header;
    
    /**
     * 请求正文
     *
     * @var Body
     */
    public $body;
    
    /**
     * 请求cookie
     *
     * @var Cookie
     */
    public $cookie;

    /**
     * 请求路径（有url请求参数）
     *
     * @var string
     */
    public $path;

    /**
     * 请求路径（无url请求参数）
     *
     * @var string
     */
    public $pathinfo;

    /**
     * 请求方法
     *
     * @var string
     */
    public $method;

    /**
     * 请求协议
     *
     * @var string
     */
    public $protocol;
    
    /**
     * 请求url
     *
     * @var string
     */
    public $url;
    
    /**
     * url请求参数
     *
     * @var array
     */
    public $query;
    
    /**
     * url请求参数串（url中'?'后面的）
     *
     * @var string
     */
    public $queryStr;

    /**
     * 接收请求的时间（时间戳）
     *
     * @var int
     */
    public $timestamp;

    /**
     * 已经处理所有请求
     *
     * @var bool
     */
    protected $finish;

    /**
     * 请求是否可以正常解析
     *
     * @var bool
     */
    protected $ok;
    
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->header = new Header();
        $this->body = new Body();
        $this->cookie = new Cookie();
        $this->timestamp = time();
        $this->finish = false;
        $this->ok = false;
    }

    /**
     * 解析请求内容
     *
     * @param string $raw 原始请求内容
     *
     * @return bool 请求是否正常
     */
    public function parseRequestRaw(string $raw): bool
    {
        $pos = strpos($raw, "\r\n\r\n");
        if ($pos === false) {
            return $this->ok = false;
        }
        // 分离头部和正文
        $rawHeader = substr($raw, 0, $pos);
        $rawBody = substr($raw, $pos + 4);
        
        // 解析头部
        $this->header->parseRequestRaw($rawHeader);

        // 获取头部的信息
        $this->path = $this->header->path ?: '/';
        $this->method = $this->header->method;
        $this->protocol = $this->header->protocol;

        // 获取请求路径、url查询串、url请求参数
        $this->query = [];
        $pos = strpos($this->path, '?');
        if ($pos !== false) { // 有url请求参数
            $this->pathinfo = substr($this->path, 0, $pos);
            $this->queryStr = substr($this->path, $pos + 1);
            // 获取url请求参数
            $kvs = explode('&', urldecode($this->queryStr));
            foreach ($kvs as &$kv) {
                @list($name, $value) = explode('=', $kv);
                $this->query[$name] = $value ?? '';
            }
        } else { // 无url请求参数
            $this->pathinfo = $this->path;
            $this->queryStr = '';
        }
        // 获取url
        $host = $this->header->get('Host');
        if (!is_string($host)) {
            return $this->ok = false;
        }
        $this->url = 'http://' . $host . $this->header->path;
        
        // 解析cookie
        $cookieRaw = $this->header->get('Cookie') ?? '';
        if (!is_string($cookieRaw)) {
            return $this->ok = false;
        }
        $this->cookie->parseRequestRaw($cookieRaw);

        // 解析正文
        $this->body->setContent($rawBody);
        return $this->ok = true;
    }

    /**
     * 请求是否正常
     *
     * @return bool 请求是否正常
     */
    public function isOk(): bool
    {
        return $this->ok;
    }

    /**
     * 设置所有请求已经完成
     *
     * @return void
     */
    public function setFinish(): void
    {
        $this->finish = true;
    }

    /**
     * 判断所有请求是否已经完成
     *
     * @return bool 所有请求是否已经完成
     */
    public function isFinish(): bool
    {
        return $this->finish;
    }
}
