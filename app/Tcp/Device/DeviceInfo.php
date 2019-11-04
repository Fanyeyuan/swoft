<?php declare(strict_types=1);


namespace App\Tcp\Device;


use JsonSerializable;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class DeviceInfo
 */
class DeviceInfo implements JsonSerializable
{
    /**
     * @var bool
     */
    protected $isCreated;
    /**
     * 设备编号
     * @var int|string
     */
    protected $id;

    /**
     * 设备链接 fd
     * @var int
     */
    protected $fd;

    /**
     * 设备备注名称
     * @var string
     */
    protected $name;

    /**
     * 设备类型
     * @var int|string
     */
    protected $type;

    /**
     * 设备生成时间
     * @var int
     */
    protected $createTime;

    /**
     * 设备上次修改信息时间
     * @var int
     */
    protected $modifyTime;

    /**
     * 当前设备是否在线
     * @var true
     * true 在线， false 掉线
     */
    protected $online = true;

    /**
     * 设备状态
     * @var int
     * 0 正常， 其他异常
     */
    protected $status = 0;

    /**
     * 故障信息
     * @var string
     */
    protected $errMsg = '';

    /**
     * 上次刷新时间
     * @var int
     */
    protected $lastUpdateTime;

    /**
     * 上次刷新数据
     * @var string
     */
    protected $lastData;

    /**
     * 上一次刷新数据的原始数据
     * @var string
     */
    protected $lastRawData;

    /**
     * 上次在线时间
     * @var int
     */
    protected $lastOnlineTime;

    /**
     * 上次掉线时间
     * @var int
     */
    protected $lastOffLimeTime;

    /**
     * 矫正系数
     * @var array
     */
    protected $coefficient;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @param int $fd
     */
    public function setFd(int $fd): void
    {
        $this->fd = $fd;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|string
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @param int|string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     */
    public function setCreateTime(int $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getModifyTime(): int
    {
        return $this->modifyTime;
    }

    /**
     * @param int $modifyTime
     */
    public function setModifyTime(int $modifyTime): void
    {
        $this->modifyTime = $modifyTime;
    }

    /**
     * @return bool
     */
    public function getOnline(): bool
    {
        return $this->online;
    }

    /**
     * @param bool $online
     */
    public function setOnline(bool $online): void
    {
        $this->online = $online;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getErrMsg(): string
    {
        return $this->errMsg;
    }

    /**
     * @param string $errMsg
     */
    public function setErrMsg(string $errMsg): void
    {
        $this->errMsg = $errMsg;
    }

    /**
     * @return int
     */
    public function getLastUpdateTime(): int
    {
        return $this->lastUpdateTime;
    }

    /**
     * @param int $lastUpdateTime
     */
    public function setLastUpdateTime(int $lastUpdateTime): void
    {
        $this->lastUpdateTime = $lastUpdateTime;
    }

    /**
     * @return string
     */
    public function getLastData(): string
    {
        return $this->lastData;
    }

    /**
     * @param string $lastData
     */
    public function setLastData(string $lastData): void
    {
        $this->lastData = $lastData;
    }

    /**
     * @return int
     */
    public function getLastOnlineTime(): int
    {
        return $this->lastOnlineTime;
    }

    /**
     * @param int $lastOnlineTime
     */
    public function setLastOnlineTime(int $lastOnlineTime): void
    {
        $this->lastOnlineTime = $lastOnlineTime;
    }

    /**
     * @return int
     */
    public function getLastOffLimeTime(): int
    {
        return $this->lastOffLimeTime;
    }

    /**
     * @param int $lastOffLimeTime
     */
    public function setLastOffLimeTime(int $lastOffLimeTime): void
    {
        $this->lastOffLimeTime = $lastOffLimeTime;
    }

    /**
     * @return array
     */
    public function getCoefficient(): array
    {
        return $this->coefficient;
    }

    /**
     * @param array $coefficient
     */
    public function setCoefficient(array $coefficient): void
    {
        $this->coefficient = $coefficient;
    }

    /**
     * Convert the model instance to JSON.
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        JsonHelper::encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the model instance to an array.
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this as $key=>$val){
            $data[$key] = $val;
        }
        return $data;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getLastRawData(): string
    {
        return $this->lastRawData;
    }

    /**
     * @param string $lastRawData
     */
    public function setLastRawData(string $lastRawData): void
    {
        $this->lastRawData = $lastRawData;
    }

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->isCreated;
    }

    /**
     * @param bool $isCreated
     */
    public function setIsCreated(bool $isCreated): void
    {
        $this->isCreated = $isCreated;
    }
}
