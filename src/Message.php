<?php

declare(strict_types=1);

namespace McMatters\SlackApi;

use function array_filter;

use const null;

/**
 * Class Message
 *
 * @package McMatters\SlackApi
 */
class Message implements Arrayable
{
    public const ICON_TYPE_EMOJI = 'icon_emoji';
    public const ICON_TYPE_URL = 'icon_url';

    /**
     * @var string|null
     */
    protected ?string $text;

    /**
     * @var string|null
     */
    protected ?string $to;

    /**
     * @var string|null
     */
    protected ?string $from;

    /**
     * @var string
     */
    protected string $icon = ':robot_face:';

    /**
     * @var string
     */
    protected string $iconType = self::ICON_TYPE_EMOJI;

    /**
     * @var \McMatters\SlackApi\Attachment[]
     */
    protected array $attachments = [];

    /**
     * @var array
     */
    protected array $custom = [];

    /**
     * @param string|null $text
     */
    public function __construct(string $text = null)
    {
        $this->text = $text;
    }

    /**
     * @param string|null $text
     *
     * @return \McMatters\SlackApi\Message
     */
    public static function make(string $text = null): Message
    {
        return new static($text);
    }

    /**
     * @param string $text
     *
     * @return \McMatters\SlackApi\Message
     */
    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $to
     *
     * @return \McMatters\SlackApi\Message
     */
    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param string $from
     *
     * @return \McMatters\SlackApi\Message
     */
    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string $icon
     * @param string $type
     *
     * @return \McMatters\SlackApi\Message
     *
     * @throws \InvalidArgumentException
     */
    public function icon(string $icon, string $type = self::ICON_TYPE_EMOJI): self
    {
        $this->icon = $icon;
        $this->iconType = $type;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Attachment $attachment
     *
     * @return \McMatters\SlackApi\Message
     */
    public function attach(Attachment $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Attachment[] $attachments
     *
     * @return \McMatters\SlackApi\Message
     */
    public function attachMultiple(array $attachments): self
    {
        foreach ($attachments as $attachment) {
            $this->attach($attachment);
        }

        return $this;
    }


    /**
     * @param array $data
     *
     * @return \McMatters\SlackApi\Message
     */
    public function setCustom(array $data): self
    {
        $this->custom = $data;

        return $this;
    }

    /**
     * @return array
     *
     * @throws \RuntimeException
     */
    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
            'channel' => $this->to,
            'username' => $this->from,
            $this->iconType => $this->icon,
            'attachments' => $this->getAttachments(),
        ] + $this->custom);
    }

    /**
     * @return array
     */
    protected function getAttachments(): array
    {
        $attachments = [];

        foreach ($this->attachments as $attachment) {
            $attachments[] = $attachment->toArray();
        }

        return $attachments;
    }
}
