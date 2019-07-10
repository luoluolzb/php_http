<?php
/**
 * http正文类
 * @author luoluolzb <luoluolzb@163.com>
 */
namespace luoluolzb\http;

class Body
{
	/**
	 * 正文内容
	 * @var string
	 */
	protected $content;

	/**
	 * 构造函数
	 * @param string $content 正文内容
	 */
	public function __construct(string $content = '') {
		$this->content = $content;
	}

	/**
	 * 设置或获取正文内容
	 * @param  string      $content 正文内容
	 * @return Body|string          原Body对象|正文内容
	 */
	public function content(string $content = null) {
		if (isset($content)) {
			$this->content = $content;
			return $this;
		} else {
			return $this->content;
		}
	}

	/**
	 * 获取正文长度
	 * @return int  正文长度
	 */
	public function length(): int {
		return strlen($this->content);
	}

	/**
	 * 向正文头部添加内容
	 * @param  string $text 要添加的内容
	 * @return Body   原Body对象
	 */
	public function begin(string $text) {
		$this->content = $text . $this->content;
		return $this;
	}

	/**
	 * 向正文头部添加内容并换行
	 * @return $line 要写入内容
	 * @return Body  原Body对象
	 */
	public function beginln(string $line = '') {
		return $this->begin($line . "\n");
	}

	/**
	 * 向正文头部添加内容
	 * @param  string $text 要添加的内容
	 * @return Body   原Body对象
	 */
	public function end(string $text) {
		$this->content .= $text;
		return $this;
	}
	
	/**
	 * 向正文写入一行内容并换行
	 * @return $line 要写入内容
	 * @return Body  原Body对象
	 */
	public function endln(string $line = '') {
		return $this->end($line . "\n");
	}
}
