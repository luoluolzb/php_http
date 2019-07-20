<?php
namespace luoluolzb\http;

/**
 * http正文类
 *
 * @author    luoluolzb <luoluolzb@163.com>
 */
class Body
{
    /**
     * 正文内容
     *
     * @var string
     */
    protected $content;

    /**
     * 构造函数
     *
     * @param string $content 正文内容
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * 设置或获取正文内容
     *
     * @return string 正文内容
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 设置或获取正文内容
     *
     * @param string $content 正文内容
     *
     * @return Body 原Body对象
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 获取正文长度
     *
     * @return int 正文长度
     */
    public function length(): int
    {
        return strlen($this->content);
    }

    /**
     * 向正文头部添加内容
     *
     * @param string $text 要添加的内容
     *
     * @return Body 原Body对象
     */
    public function begin(string $text): Body
    {
        $this->content = $text . $this->content;
        return $this;
    }

    /**
     * 向正文头部添加内容并换行
     *
     * @param string $line 要写入内容
     *
     * @return Body 原Body对象
     */
    public function beginln(string $line = ''): Body
    {
        return $this->begin($line . "\n");
    }

    /**
     * 向正文头部添加内容
     *
     * @param string $text 要添加的内容
     *
     * @return Body 原Body对象
     */
    public function end(string $text): Body
    {
        $this->content .= $text;
        return $this;
    }
    
    /**
     * 向正文写入一行内容并换行
     *
     * @param string $line 要写入内容
     *
     * @return Body 原Body对象
     */
    public function endln(string $line = ''): Body
    {
        return $this->end($line . "\n");
    }
}
