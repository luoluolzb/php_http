<?php
namespace luoluolzb\http;

use luoluolzb\http\Header;
use luoluolzb\http\Body;
use luoluolzb\http\Request;

/**
 * http响应类
 *
 * @author luoluolzb <luoluolzb@163.com>
 */
class Response
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
     * 响应cookie
     *
     * @var Cookie
     */
    public $cookie;

    /**
     * 状态码
     *
     * @var int
     */
    protected $statusCode;

    /**
     * 构造函数
     *
     * @param Request $request 请求对象
     */
    public function __construct(Request $request)
    {
        $this->header = new Header();
        $this->body = new Body();
        $this->cookie = clone $request->cookie;
        $this->statusCode = 200;
    }

    /**
     * 生成原始响应内容
     *
     * @return string 响应内容
     */
    public function makeResponseRaw(): string
    {
        // 设置服务器标志
        $this->header->set('Server', "luoluolzb's HttpServer");
        
        // 设置cookie
        $raws = $this->cookie->makeResponseRaws(time());
        foreach ($raws as &$raw) {
            $this->header->set('Set-Cookie', $raw, true);
        }

        // 设置默认的响应内容类型
        if (!$this->header->exists('Content-Type')) {
            $this->header->set('Content-Type', 'text/plain');
        }
        
        // 设置正文长度
        $this->header->set('Content-Length', (string)$this->body->length());

        // 获取原始头部内容和正文内容然后组合
        return
        $this->header->makeResponseRaw($this->statusCode)
        . "\r\n"
        . $this->body->getContent();
    }

    /**
     * 设置状态码
     *
     * @param int $code 状态码
     */
    public function statusCode(int $code): void
    {
        $this->statusCode = $code;
    }
}
