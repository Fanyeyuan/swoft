<?php declare(strict_types=1);


namespace App\Tcp\Db;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;

/**
 * Class dbHj212DeviceType
 *
 * @since 2.0
 *
 * @Entity(table="device_type",pool="dbTcp.pool")
 */
class dbHj212DeviceType extends Model
{
    /**
     * @Id(incrementing=true)
     *
     * @Column(name="id")
     * @var int|null
     */
    private $id;

    /**
     * @Column(name="type")
     *
     * @var int|string|null
     */
    private $type;

    /**
     * @Column(name="name")
     *
     * @var string|null
     */
    private $name;

    /**
     * @Column(name="remark")
     *
     * @var string|null
     */
    private $remark;

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
     * @return int|string|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int|string|null $type
     */
    public function setType(?int $type): void
    {
        $this->type = $type;
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
     * @return string|null
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @param string|null $remark
     */
    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }
}
