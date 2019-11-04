<?php declare(strict_types=1);


namespace App\Tcp\Db;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;

/**
 * Class dbHj212Data
 *
 * @since 2.0
 *
 * @Entity(table="data",pool="dbTcp.pool")
 */
class dbHj212Data extends Model
{
    /**
     * @Id(incrementing=true)
     *
     * @Column(name="id", prop="id")
     * @var int|null
     */
    private $id;

    /**
     * @Column(name="id_device_info", prop="deviceInfo")
     *
     * @var int|null
     */
    private $idDeviceInfo;

    /**
     * @Column(name="$insertTime", prop="insertTime")
     *
     * @var int|null
     */
    private $insertTime;

    /**
     * @Column(name="package", prop="package")
     *
     * @var string|null
     */
    private $package;

    /**
     * @Column(name="data", prop="data")
     *
     * @var string|null
     */
    private $data;

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
    public function getIdDeviceInfo(): ?int
    {
        return $this->idDeviceInfo;
    }

    /**
     * @param int|null $idDeviceInfo
     */
    public function setIdDeviceInfo(?int $idDeviceInfo): void
    {
        $this->idDeviceInfo = $idDeviceInfo;
    }

    /**
     * @return int|null
     */
    public function getInsertTime(): ?int
    {
        return $this->insertTime;
    }

    /**
     * @param int|null $insertTime
     */
    public function setInsertTime(?int $insertTime): void
    {
        $this->insertTime = $insertTime;
    }

    /**
     * @return string|null
     */
    public function getPackage(): ?string
    {
        return $this->package;
    }

    /**
     * @param string|null $package
     */
    public function setPackage(?string $package): void
    {
        $this->package = $package;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     */
    public function setData(?string $data): void
    {
        $this->data = $data;
    }

}
