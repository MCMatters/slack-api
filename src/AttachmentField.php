<?php

declare(strict_types = 1);

namespace McMatters\SlackApi;

use const false;

/**
 * Class AttachmentField
 *
 * @package McMatters\SlackApi
 */
class AttachmentField implements Arrayable
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $short;

    /**
     * AttachmentField constructor.
     *
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
     * @return \McMatters\SlackApi\AttachmentField
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
