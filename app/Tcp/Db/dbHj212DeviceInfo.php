<?php declare(strict_types=1);


namespace App\Tcp\Db;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;

/**
 * Class dbHj212DeviceInfo
 *
 * @since 2.0
 *
 * @Entity(table="device_info",pool="dbTcp.pool")
 */
class dbHj212DeviceInfo extends Model
{
    /**
     * @Id(incrementing=true)
     *
     * @Column(name="id")
     * @var int|null
     */
    private $id;

    /**
     * @Column(name="facId")
     *
     * @var int|null
     */
    private $facId;

    /**
     * @Column(name="name")
     *
     * @var string|null
     */
    private $name;

    /**
     * @Column(name="deviceType")
     *
     * @var int|string|null
     */
    private $deviceType;

    /**
     * @Column(name="modifyTime")
     *
     * @var int|null
     */
    private $modifyTime;

    /**
     * @Column(name="createTime")
     *
     * @var int|null
     */
    private $createTime;

    /**
     * @Column(name="param")
     *
     * @var string|null
     */
    private $param;

    /**
     * @Column(name="deviceInfo")
     *
     * @var string|null
     */
    private $deviceInfo;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getFacId(): ?int
    {
        return $this->facId;
    }

    /**
     * @param int|null $facId
     */
    public function setFacId(?int $facId): void
    {
        $this->facId = $facId;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|string|null
     */
    public function getDeviceType(): ?int
    {
        return $this->deviceType;
    }

    /**
     * @param int|string|null $deviceType
     */
    public function setDeviceType(?int $deviceType): void
    {
        $this->deviceType = $deviceType;
    }

    /**
     * @return int|null
     */
    public function getModifyTime(): ?int
    {
        return $this->modifyTime;
    }

    /**
     * @param int|null $modifyTime
     */
    public function setModifyTime(?int $modifyTime): void
    {
        $this->modifyTime = $modifyTime;
    }

    /**
     * @return int|null
     */
    public function getCreateTime(): ?int
    {
        return $this->createTime;
    }

    /**
     * @param int|null $createTime
     */
    public function setCreateTime(?int $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return string|null
     */
    public function getParam(): ?string
    {
        return $this->param;
    }

    /**
     * @param string|null $param
     */
    public function setParam(?string $param): void
    {
        $this->param = $param;
    }

    /**
     * @return string|null
     */
    public function getDeviceInfo(): ?string
    {
        return $this->deviceInfo;
    }

    /**
     * @param string|null $deviceInfo
     */
    public function setDeviceInfo(?string $deviceInfo): void
    {
        $this->deviceInfo = $deviceInfo;
    }
}
