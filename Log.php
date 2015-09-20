<?php
/**
 * Created by PhpStorm.
 * User: mr2longly
 * Date: 9/19/15
 * Time: 00:27
 */
class Log {

    /**
     * 根据不同名字产生的logger实例
     * @var array
     */
    private static $_logger = array();

    /**
     * 默认looger实例
     * @var string
     */
    private $_name = 'main';

    /**
     * 自定义每个等级日志的开关
     *
     * @var array
     */
    private $_switcher = array();

    /**
     * 私有构造函数，防止直接生成实例
     * 必须使用 Log::getLogger($name) 生成对应$name的单例
     *
     * @param $name
     */
    private function __construct($name) {
        $this->_name = $name;
    }

    /**
     * 防止对象被复制
     */
    public function __clone() {}

    /**
     * 根据名字生成Log单例
     *
     * @param $name
     * @return Log
     */
    public static function getLogger($name = 'main') {
        if (isset(self::$_logger[$name]) && (self::$_logger[$name] instanceof self)) {
            return self::$_logger[$name];
        }
        return self::$_logger[$name] = new self($name);
    }

    /**
     * 强制打开某个级别日志
     *
     * @param $lv
     * @return $this
     */
    public function turnOn($lv) {
        $this->_switcher[$lv] = true;
        return $this;
    }

    /**
     * 强制关闭某个级别的日志
     *
     * @param $lv
     * @return $this
     */
    public function turnOff($lv) {
        $this->_switcher[$lv] = false;
        return $this;
    }

    /**
     * 判断某个级别的日志是否打开
     *
     * @param $lv
     * @return bool
     */
    public function on($lv) {
        return isset($this->_switcher[$lv]) ? $this->_switcher[$lv] : Config::getLogLevel() >= $lv;
    }

    /**
     * 主要记录被迫exit的日志
     *
     * @param           $msg
     * @param bool|true $call_stack
     * @return bool
     */
    public function fatal($msg, $call_stack = true) {
        if ($this->on(Config::L_FATAL)) {
            return $this->_write($msg, 'fatal', $call_stack);
        }
        return false;
    }

    /**
     * 主要记录业务失败时的日志信息
     *
     * @param           $msg
     * @param bool|true $call_stack
     * @return bool
     */
    public function error($msg, $call_stack = true) {
        if ($this->on(Config::L_ERROR)) {
            return $this->_write($msg, 'error', $call_stack);
        }
        return false;
    }

    /**
     * 主要用于记录异常日志，一定是要记录调用栈的
     * 如数据库CURD异常
     *
     * @param $e
     * @return bool
     */
    public function exception($e) {
        if ($this->on(Config::L_EXCEPTION)) {
            if (false !== ($_e2str = $this->_e2Str($e))) {
                return $this->_write($_e2str, 'exception', $_call_stack = true);
            }
        }
        return false;
    }

    /**
     * 将异常信息转成字符串
     *
     * @param $e
     * @return string
     */
    private function _e2Str($e) {
        if ($e instanceof Exception) {
            return sprintf(
                "file:%s line:%s %s %s",
                $e->getFile(),
                $e->getLine(),
                $e->getMessage(),
                $e->getTraceAsString()
            );
        }

        if ($this->on(Config::L_ABNORMAL)) {
            $this->abnormal('一定是哪里出错了，$e 不是 Exception 实例');
        } else {
            $this->turnOn(Config::L_ABNORMAL)->abnormal('一定是哪里出错了，$e 不是 Exception 实例');
            $this->turnOff(Config::L_ABNORMAL);
        }

        return false;
    }

    /**
     * 主要记录正常逻辑不可能出现却实实在在存在的信息
     *
     * @param           $msg
     * @param bool|true $call_stack
     * @return bool
     */
    public function abnormal($msg, $call_stack = true) {
        if ($this->on(Config::L_ABNORMAL)) {
            return $this->_write($msg, 'abnormal', $call_stack);
        }
        return false;
    }

    /**
     * 主要用于正常记录操作日志
     * eg：记录发短信记录
     *
     * @param            $msg
     * @param bool|false $call_stack
     * @return bool
     */
    public function runtime($msg, $call_stack = false) {
        if ($this->on(Config::L_RUNTIME)) {
            return $this->_write($msg, 'runtime', $call_stack);
        }
        return false;
    }

    /**
     * 主要用于记录业务警告
     *
     * @param           $msg
     * @param bool|true $call_stack
     * @return bool
     */
    public function warning($msg, $call_stack = true) {
        if ($this->on(Config::L_WARNING)) {
            return $this->_write($msg, 'warning', $call_stack);
        }
        return false;
    }

    /**
     * 主要记录 error_handle 产生的未知的不对付的信息
     *
     * @param           $msg
     * @param bool|true $call_stack
     * @return bool
     */
    public function unknow($msg, $call_stack = true) {
        if ($this->on(Config::L_UNKNOW)) {
            return $this->_write($msg, 'unknow', $call_stack);
        }
        return false;
    }

    /**
     * 主要用于记录调试日志信息，默认记录调用栈
     *
     * @param           $msg
     * @param bool|true $call_stack
     * @return bool
     */
    public function debug($msg, $call_stack = true) {
        if ($this->on(Config::L_DEBUG)) {
            return $this->_write($msg, 'debug', $call_stack);
        }
        return false;
    }

    /**
     * 主要用于记录简单的日志信息
     * eg：跑测试用例，有一些输出可以验证结果。
     *
     * @param            $msg
     * @param bool|false $call_stack
     * @return bool
     */
    public function info($msg, $call_stack = false) {
        if ($this->on(Config::L_INFO)) {
            return $this->_write($msg, 'info', $call_stack);
        }
        return false;
    }

    /**
     * @param           $msg
     * @param string    $level
     * @param bool|true $call_stack
     * @return bool
     */
    private function _write($msg, $level, $call_stack = true) {
        $file = '/tmp/logger-' . $this->_name . '-' . $level . '.log';
        if (($fp = @fopen($file, 'a+'))) {
            fputs($fp, $this->_getFormatLog($msg, $level, $call_stack));
            fclose($fp);
            return true;
        }
        return false;
    }

    /**
     * @param           $msg
     * @param           $flag
     * @param bool|true $call_stack
     * @return string
     */
    private function _getFormatLog($msg, $flag, $call_stack = true) {
        if (!is_string($msg)) {
            $msg = var_export($msg, true);
        }
        return sprintf(
            "[%s] [%s]\n%s\n%s\n",
            date('Y-m-d H:i:s'), $flag, $msg,
            $call_stack ? $this->_getStackInfo() : ''
        );
    }

    /**
     * @return string
     */
    private function _getStackInfo() {
        $m = '';
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']) {
            $m .= 'REQUEST_URL: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n";
        }
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
            $m .= 'REFERER: http://' . $_SERVER['HTTP_REFERER'] . "\n";
        }
        $d = debug_backtrace();
        if (is_array($d)) {
            foreach ($d as $trace) {
                if (isset($trace['file'])) {
                    $m .= $trace['file'] . ' : ' . $trace['line'] . "\n";
                }
            }
        }
        return $m;
    }

}