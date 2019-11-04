<?php declare(strict_types=1);

namespace App\Tcp\Device;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class ChengDuDeviceInfo
 *
 * @Bean(scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class ChengDuDeviceInfo extends DeviceInfo
{
    use PrototypeTrait;

    /**
     * 实时数据 时间间隔 单位为秒，取值 30≤n≤3600 之间
     * @var int
     */
    private $rtdInterval = 30;

    /**
     * 分钟数据 时间间隔 单位为分钟，取值 1、2、3、4、5、6、10、12、15、20、30 分钟
     * @var int
     */
    private $minInterval = 30;

    /**
     * 超时时间 单位为秒，取值 0<n≤99 之间
     * @var
     */
    private $overTime = 10;

    /**
     * 重发次数 取值 0<n≤99 之间
     * @var
     */
    private $reNumber = 5;

    /**
     * 设备MN 号
     * @var string
     */
    private $mn;

    /**
     * 设备PW
     * @var string
     */
    private $pw;

    /**
     * 密钥
     * @var
     */
    private $secret;

    /**
     * @var string
     */
    private $rtdHistoryData;

    /**
     * @var string
     */
    private $minuteData;

    /**
     * @var int
     */
    private $lastMinuteDataTime;

    /**
     * @var string
     */
    private $minuteHistoryData;

    /**
     * @var string
     */
    private $hourData;

    /**
     * @var int
     */
    private $lastHourDataTime;

    /**
     * @var string
     */
    private $hourHistoryData;

    /**
     * @var string
     */
    private $dayData;

    /**
     * @var int
     */
    private $lastDayDataTime;

    /**
     * @var string
     */
    private $dayHistoryData;

    /**
     * 创建一个 成都设备 原型
     * @param array $param
     * @return ChengDuDeviceInfo
     */
    public static function new(array $param): self
    {
        $self        = self::__instance();

        foreach ($param as $attribute => $value)
        {
            if(property_exists($self, $attribute))
            {
                $self->$attribute = $value;
            }
        }

        return $self;
    }

    /**
     * @return int
     */
    public function getRtdInterval(): int
    {
        return $this->rtdInterval;
    }

    /**
     * @param int $rtdInterval
     */
    public function setRtdInterval(int $rtdInterval): void
    {
        $this->rtdInterval = $rtdInterval;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getPW(): string
    {
        return $this->pw;
    }

    /**
     * @param string $PW
     */
    public function setPW(string $PW): void
    {
        $this->pw = $PW;
    }

    /**
     * @return string
     */
    public function getMN(): string
    {
        return $this->mn;
    }

    /**
     * @param string $MN
     */
    public function setMN(string $MN): void
    {
        $this->mn = $MN;
    }

    /**
     * @param mixed $reNumber
     * @return ChengDuDeviceInfo
     */
    public function setReNumber($reNumber)
    {
        $this->reNumber = $reNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverTime()
    {
        return $this->overTime;
    }

    /**
     * @param mixed $overTime
     */
    public function setOverTime($overTime): void
    {
        $this->overTime = $overTime;
    }

    /**
     * @return int
     */
    public function getMinInterval(): int
    {
        return $this->minInterval;
    }

    /**
     * @param int $minInterval
     */
    public function setMinInterval(int $minInterval): void
    {
        $this->minInterval = $minInterval;
    }

    /**
     * Convert the model instance to JSON.
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return JsonHelper::encode($this->jsonSerialize(), $options);
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
     * @return mixed
     */
    public function getReNumber()
    {
        return $this->reNumber;
    }

    /**
     * @return string
     */
    public function getRtdHistoryData(): string
    {
        return $this->rtdHistoryData;
    }

    /**
     * @return string
     */
    public function getMinuteData(): string
    {
        return $this->minuteData;
    }

    /**
     * @return int
     */
    public function getLastMinuteDataTime(): int
    {
        return $this->lastMinuteDataTime;
    }

    /**
     * @return string
     */
    public function getMinuteHistoryData(): string
    {
        return $this->minuteHistoryData;
    }

    /**
     * @return string
     */
    public function getHourData(): string
    {
        return $this->hourData;
    }

    /**
     * @return int
     */
    public function getLastHourDataTime(): int
    {
        return $this->lastHourDataTime;
    }

    /**
     * @return string
     */
    public function getHourHistoryData(): string
    {
        return $this->hourHistoryData;
    }

    /**
     * @return string
     */
    public function getDayData(): string
    {
        return $this->dayData;
    }

    /**
     * @return int
     */
    public function getLastDayDataTime(): int
    {
        return $this->lastDayDataTime;
    }

    /**
     * @return string
     */
    public function getDayHistoryData(): string
    {
        return $this->dayHistoryData;
    }

    /**
     * @param string $rtdHistoryData
     * @return ChengDuDeviceInfo
     */
    public function setRtdHistoryData(string $rtdHistoryData): ChengDuDeviceInfo
    {
        $this->rtdHistoryData = $rtdHistoryData;
        return $this;
    }

    /**
     * @param string $minuteData
     * @return ChengDuDeviceInfo
     */
    public function setMinuteData(string $minuteData): ChengDuDeviceInfo
    {
        $this->minuteData = $minuteData;
        return $this;
    }

    /**
     * @param int $lastMinuteDataTime
     * @return ChengDuDeviceInfo
     */
    public function setLastMinuteDataTime(int $lastMinuteDataTime): ChengDuDeviceInfo
    {
        $this->lastMinuteDataTime = $lastMinuteDataTime;
        return $this;
    }

    /**
     * @param string $minuteHistoryData
     * @return ChengDuDeviceInfo
     */
    public function setMinuteHistoryData(string $minuteHistoryData): ChengDuDeviceInfo
    {
        $this->minuteHistoryData = $minuteHistoryData;
        return $this;
    }

    /**
     * @param string $hourData
     * @return ChengDuDeviceInfo
     */
    public function setHourData(string $hourData): ChengDuDeviceInfo
    {
        $this->hourData = $hourData;
        return $this;
    }

    /**
     * @param int $lastHourDataTime
     * @return ChengDuDeviceInfo
     */
    public function setLastHourDataTime(int $lastHourDataTime): ChengDuDeviceInfo
    {
        $this->lastHourDataTime = $lastHourDataTime;
        return $this;
    }

    /**
     * @param string $hourHistoryData
     * @return ChengDuDeviceInfo
     */
    public function setHourHistoryData(string $hourHistoryData): ChengDuDeviceInfo
    {
        $this->hourHistoryData = $hourHistoryData;
        return $this;
    }

    /**
     * @param string $dayData
     * @return ChengDuDeviceInfo
     */
    public function setDayData(string $dayData): ChengDuDeviceInfo
    {
        $this->dayData = $dayData;
        return $this;
    }

    /**
     * @param int $lastDayDataTime
     * @return ChengDuDeviceInfo
     */
    public function setLastDayDataTime(int $lastDayDataTime): ChengDuDeviceInfo
    {
        $this->lastDayDataTime = $lastDayDataTime;
        return $this;
    }

    /**
     * @param string $dayHistoryData
     * @return ChengDuDeviceInfo
     */
    public function setDayHistoryData(string $dayHistoryData): ChengDuDeviceInfo
    {
        $this->dayHistoryData = $dayHistoryData;
        return $this;
    }
}
