<?php

declare(strict_types=1);

namespace McMatters\SlackApi\Message;

/**
 * Class Block
 *
 * @package McMatters\SlackApi\Message
 */
class Block implements Arrayable
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $custom = [];

    /**
     * @param string $type
     * @param array $custom
     */
    public function __construct(string $type, array $custom = [])
    {
        $this->type = $type;
        $this->custom = $custom;
    }

    /**
     * @param array $custom
     *
     * @return \McMatters\SlackApi\Message\Block
     */
    public function setCustom(array $custom): Block
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['type' => $this->type] + $this->custom;
    }
}
