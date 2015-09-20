# simplify-log
简单的可控可分级的记录日志

>记录main日志

>$logger = Log::getLogger('main');

>可以在项目Config文件中配置全局日志级别：

>如：Config::setLogLevel(L_ERROR | L_RUNTIME);

>也可以：强制打开，关闭某个级别的日志

>$logger->turnOn(L_DEBUG); $logger->turnOff(L_INFO);

>$logger->fatal('fatal exit'); // 记录致命错误日志

>$logger->error('error'); // 记录业务错误日志

>$logger->debug('debug') // 记录debug日志
