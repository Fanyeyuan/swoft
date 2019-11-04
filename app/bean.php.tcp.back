<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
use App\Common\DbSelector;
use App\Http\Middleware\FavIconMiddleware;
use App\Process\MonitorProcess;
use Swoft\Crontab\Process\CrontabProcess;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Http\Server\Middleware\ValidatorMiddleware;
use Swoft\Task\Swoole\SyncTaskListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\Tcp\Packer\Hj212Packer;
use Swoft\View\Middleware\ViewMiddleware;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\Server\SwooleEvent;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;

return [
    'noticeHandler'      => [
        'logFile' => '@runtime/logs/notice-%d{Y-m-d-H}.log',
    ],
    'applicationHandler' => [
        'logFile' => '@runtime/logs/error-%d{Y-m-d}.log',
    ],
    'logger'            => [
        'flushRequest' => false,
        'enable'       => false,
        'json'         => false,
    ],
    'tcpServer'         => [
        'port'  => 18309,
        'debug' => 1,
        'on'      => [
            // 启用任务必须添加 task, finish 事件处理
            SwooleEvent::TASK   => bean(TaskListener::class),
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        'listener' => [
            'http' => \bean('httpServer'),
            'rpc' => \bean('rpcServer') // 引入 rpcServer
        ],
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
            // 任务需要配置 task worker
            'tcpDispatcher'      => false,
            'task_worker_num'       => 4,
            'task_enable_coroutine' => true
        ],
    ],
    'rpcServer'         => [
        'class' => ServiceServer::class,
        'port'      => 18308,
    ],
    /** @see \Swoft\Tcp\Protocol */
    'tcpServerProtocol' => [
        'type'               => Swoft\Tcp\Packer\ChengduHj212Packer::TYPE,
        'packageEof'        => "\r\n\r\n",
        'openEofCheck'      => true,
        'openLengthCheck'   => false,
    ],
    'dbTcp' => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=hj212;host=mysql',
        'username' => 'root',
        'password' => 'Gpf_1039355112',
        'charset'   => 'utf8mb4',
    ],
    'dbTcp.pool' => [
        'class'    => Pool::class,
        'database' => bean('dbTcp'),
    ],
    'redisTcp' => [
        'class'    => RedisDb::class,
        'host'     => 'redis',
        'port'     => 6379,
        'database' => 0,
        'option'   => [
            'prefix'        => 'tcp:',
            'serializer'    => Redis::SERIALIZER_PHP
        ]
    ],
    'redisTcp.pool' => [
        'class'   => \Swoft\Redis\Pool::class,
        'redisDb' => \bean('redisTcp'),
        'minActive'   => 10,
        'maxActive'   => 20,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 40,
    ],
    'cliRouter'         => [
        // 'disabledGroups' => ['demo', 'test'],
    ]
];
