<?php

namespace TheAentMachine\Service\Volume;

use TheAentMachine\Service\Enum\VolumeTypeEnum;

class NamedVolume extends Volume
{
    /** @var string */
    private $target;
    /** @var bool */
    private $readOnly;

    /**
     * BindVolume constructor.
     * @param string $source
     * @param bool $readOnly
     * @param string $target
     */
    public function __construct(string $source, string $target, bool $readOnly = false)
    {
        parent::__construct($source);
        $this->target = $target;
        $this->readOnly = $readOnly;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return VolumeTypeEnum::NAMED_VOLUME;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return array(
            'type' => $this->getType(),
            'source' => $this->source,
            'target' => $this->target,
            'readOnly' => $this->readOnly,
        );
    }
}
