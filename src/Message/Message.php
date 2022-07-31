<?php

declare(strict_types=1);

namespace McMatters\SlackApi\Message;

use function array_filter;
use function array_map;

/**
 * Class Message
 *
 * @package McMatters\SlackApi\Message
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
     * @var string
     */
    protected string $to;

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
     * @var \McMatters\SlackApi\Message\Attachment[]
     */
    protected array $attachments = [];

    /**
     * @var \McMatters\SlackApi\Message\Block[]
     */
    protected array $blocks = [];

    /**
     * @var array
     */
    protected array $custom = [];

    /**
     * @param string $to
     */
    public function __construct(string $to)
    {
        $this->to = $to;
    }

    /**
     * @param string $to
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public static function make(string $to): Message
    {
        return new static($to);
    }

    /**
     * @param string $text
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function text(string $text): Message
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $to
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function to(string $to): Message
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param string $from
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function from(string $from): Message
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string $icon
     * @param string $type
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function icon(string $icon, string $type = self::ICON_TYPE_EMOJI): Message
    {
        $this->icon = $icon;
        $this->iconType = $type;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Message\Attachment $attachment
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function attach(Attachment $attachment): Message
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Message\Attachment[] $attachments
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function attachMultiple(array $attachments): Message
    {
        foreach ($attachments as $attachment) {
            $this->attach($attachment);
        }

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Message\Block $block
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function addBlock(Block $block): Message
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * @param array $blocks
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function addBlocks(array $blocks): Message
    {
        foreach ($blocks as $block) {
            $this->addBlock($block);
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return \McMatters\SlackApi\Message\Message
     */
    public function setCustom(array $data): Message
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
            'attachments' => array_map(
                static fn (Attachment $attachment) => $attachment->toArray(),
                $this->attachments ?? [],
            ),
            'blocks' => array_map(
                static fn (Block $block) => $block->toArray(),
                $this->blocks ?? [],
            ),
        ]) + $this->custom;
    }
}
