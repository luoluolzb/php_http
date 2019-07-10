<?php
/**
 * @description 简单Http服务器
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\http;
use luoluolzb\library\{Config, EventContainer};
use luoluolzb\http\{Request, Response};
use luoluolzb\http\exception\ServerStartException;

class Server
{
	/**
	 * 配置
	 * @var luoluolzb\http\Config
	 */
	protected $conf;

	/**
	 * 事件容器
	 * @var luoluolzb\http\EventContainer
	 */
	protected $eventContainer;

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
	 * 构造函数
	 * @param string  $confFilePath 配置文件
	 */
	public function __construct(string $confFilePath) {
		$this->conf = new Config($confFilePath);
		$this->eventContainer = new EventContainer();
		$this->address = $this->conf->get('address');
		$this->port = $this->conf->get('port');
	}

	/**
	 * 析构函数
	 */
	public function __destruct() {
		socket_close($this->socket);
		// 调用 'close' 事件处理
		$this->eventContainer->call('close', $this);
	}

	/**
	 * 开始运行http服务器
	 */
	public function start() {
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if (! $this->socket) {
			throw new ServerStartException("Http Server start failed!");
		}
		socket_bind($this->socket, $this->address, $this->port);

		// 调用 'start' 事件处理
		$this->eventContainer->call('start', $this);
		
		socket_listen($this->socket);
		while (true) {
			// 等待客户端连接
			$this->msgSocket = socket_accept($this->socket);
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
	 * 添加事件监听函数
	 * @param  string   $eventName 事件名称
	 * @param  callable $handler   事件处理函数
	 */
	public function on($eventName, callable $handler) {
		return $this->eventContainer->bind($eventName, $handler);
	}

	/**
	 * 处理客户端请求
	 * @param  string $requestRaw 客户端原始请求内容
	 * @return string             响应给客户端的内容
	 */
	protected function procRequest(string $requestRaw): string {
		// 解析请求
		$request = new Request();
		$request->parseRequestRaw($requestRaw);
		$response = new Response($request);
		
		if ($request->isOk()) { //请求正常
			// 调用 'request' 事件处理
			$this->eventContainer->call('request', $request, $response);

			// 调用 用户注册的请求 处理
			if ($this->eventContainer->exists($request->path)) {
				$this->eventContainer->call($request->path, $request, $response);
			} else if ($this->eventContainer->exists('404')) {
				$this->eventContainer->call('404', $request, $response);
			} else { //请求内容不存在
				$response->statusCode(404);
				$errorPage = $this->conf->get('error_page.404');
				$fileContent = file_get_contents($errorPage);
				$response->header->set('Content-Type', 'text/html');
				$response->body->content($fileContent);
			}
		} else { //请求错误
			$response->statusCode(400);
			$errorPage = $this->conf->get('error_page.400');
			$fileContent = file_get_contents($errorPage);
			$response->header->set('Content-Type', 'text/html');
			$response->body->content($fileContent);
		}
		
		// 生成原始响应数据
		return $response->makeResponseRaw();
	}
}
