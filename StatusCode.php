<?php
namespace luoluolzb\http;

/**
 * http状态码类
 * @author luoluolzb <luoluolzb@163.com>
 */
class StatusCode
{
    /**
     * 状态码对应的描述
     * 来源于：http://tools.jb51.net/table/http_status_code
     * @var Array
     */
    protected const CODE_DESC = [
        // 1xx: 信息
        // 服务器收到请求，需要请求者继续执行操作
        100 => 'Continue',
        101 => 'Switching Protocols',

        // 2xx: 成功
        // 操作被成功接收并处理
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // 3xx: 重定向，需要进一步的操作以完成请求
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Unused',
        307 => 'Temporary Redirect',

        // 4xx: 客户端错误
        // 请求包含语法错误或无法完成请求
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // 50x: 服务器错误
        // 服务器在处理请求的过程中发生了错误
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version Not Supported',
    ];

    /**
     * 获取状态码描述
     * @param  int    $code 状态码
     * @return string       状态码描述
     */
    public static function getDesc(int $code): string
    {
        return self::CODE_DESC[$code] ?? '';
    }

    /**
     * 判断某个状态码是否存在
     * @param  int    $code 状态码
     * @return bool         是否存在
     */
    public static function exists(int $code): bool
    {
        return isset(self::CODE_DESC[$code]);
    }
}
