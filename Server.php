<?php
namespace luoluolzb\http;

use luoluolzb\library\{Config, EventContainer};
use luoluolzb\http\{Request, Response};
use luoluolzb\http\exception\ServerStartException;

/**
 * 简单http服务器
 * @author luoluolzb <luoluolzb@163.com>
 */
class Server
{
    /**
     * 配置
     * @var luoluolzb\http\Config
     */
    public $conf;

    /**
     * socket
     * @var socket
     */
    protected $socket;

    /**
     * 服务器监听地址
     * @var String
     */
    public $address;

    /**
     * 服务器监听端口
     * @var String
     */
    public $port;

    /**
     * 客户端地址和端口（当次请求有效）
     * @var String
     */
    public $remoteAddress;
    
    /**
     * 客户端连接端口（当次请求有效）
     * @var String
     */
    public $remotePort;

    /**
     * 事件容器
     * @var luoluolzb\http\EventContainer
     */
    protected $event;

    /**
     * 客户端请求对象（当次请求有效）
     * @var luoluolzb\http\Request
     */
    protected $request;

    /**
     * 客户端响应对象（当次请求有效）
     * @var luoluolzb\http\Response
     */
    protected $response;

    /**
     * 构造函数
     * @param string  $confPath 配置文件路径
     */
    public function __construct(string $confPath)
    {
        $this->conf = new Config($confPath);
        $this->event = new EventContainer();
        $this->address = $this->conf->get('address');
        $this->port = $this->conf->get('port');
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        socket_close($this->socket);
        // 触发 'close' 事件
        $this->event->trigger('close', $this);
    }

    /**
     * 添加事件监听函数
     * @param  string   $eventName 事件名称
     * @param  callable $handler   事件处理函数
     */
    public function on(string $eventName, callable $handler)
    {
        return $this->event->bind($eventName, $handler);
    }

    /**
     * 开始运行http服务器
     */
    public function start(): void
    {
        // 创建socket
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (! $this->socket) {
            throw new ServerStartException("Http Server start failed!");
        }
        // 绑定地址和端口
        socket_bind($this->socket, $this->address, $this->port);
        // 触发 'start' 事件
        $this->event->trigger('start', $this);
        // 开始监听
        socket_listen($this->socket);
        
        while (true) {
            // 等待客户端连接
            $this->msgSocket = socket_accept($this->socket);
            // 获取客户端地址和端口
            socket_getpeername(
                $this->msgSocket, 
                $this->remoteAddress,
                $this->remotePort
            );
            // 读取客户端消息
            $requestRaw = socket_read($this->msgSocket, 1024*10);
            // 处理请求
            $responseRaw = $this->procRequest($requestRaw);
            // 发送消息给客户端
            socket_write($this->msgSocket, $responseRaw);
            // 关闭请求socket
            socket_close($this->msgSocket);
        }
    }

    /**
     * 处理客户端请求
     * @param  string $requestRaw 客户端原始请求内容
     * @return string             响应给客户端的内容
     */
    protected function procRequest(string $requestRaw): string
    {
        // 解析请求
        $this->request = new Request();
        $this->request->parseRequestRaw($requestRaw);
        $this->response = new Response($this->request);
        
        if ($this->request->isOk()) { //请求正常
            // 触发 'request' 事件
            $this->event->trigger(
                'request', 
                $this->request,
                $this->response
            );
            // var_dump($this->request->finish());
            if (!$this->request->isFinish()) { // 继续处理具体请求
                // 触发 用户注册的请求 事件
                if ($this->event->exists($this->request->pathinfo)) {
                    $this->event->trigger(
                        $this->request->pathinfo, 
                        $this->request,
                        $this->response
                    );
                } else { //请求内容不存在
                    $this->badRequest(404);
                }
            } else { // 已经处理所有请求
                // some code
            }
        } else { //请求错误
            $this->badRequest(400);
        }
        
        // 生成原始响应数据
        return $this->response->makeResponseRaw();
    }

    /**
     * 处理错误请求
     * @param  string   $statusCode 错误状态码
     * @return bool                 处理成功或失败
     */
    public function badRequest(int $statusCode): bool
    {
        if ($statusCode < 400) {
            return false;
        }
        $this->response->statusCode($statusCode);
        $errorPage = $this->conf->get("error_page.{$statusCode}");
        $fileContent = file_get_contents($errorPage);
        $this->response->header->set('Content-Type', 'text/html');
        $this->response->body->content($fileContent);
        return true;
    }
}
