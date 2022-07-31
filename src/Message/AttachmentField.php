<?php

declare(strict_types=1);

namespace McMatters\SlackApi\Message;

use const false;

/**
 * Class AttachmentField
 *
 * @package McMatters\SlackApi\Message
 */
class AttachmentField implements Arrayable
{
    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $value;

    /**
     * @var bool
     */
    protected bool $short;

    /**
     * @param string $title
     * @param string $value
     * @param bool $short
     */
    public function __construct(string $title, string $value, bool $short = false)
    {
        $this->title = $title;
        $this->value = $value;
        $this->short = $short;
    }

    /**
     * @param string $title
     * @param string $value
     * @param bool $short
     *
     * @return \McMatters\SlackApi\Message\AttachmentField
     */
    public static function make(
        string $title,
        string $value,
        bool $short = false
    ): AttachmentField {
        return new static($title, $value, $short);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->value,
            'short' => $this->short,
        ];
    }
}
