<?php

declare(strict_types = 1);

namespace McMatters\SlackApi;

use InvalidArgumentException;
use McMatters\Ticl\Client;
use RuntimeException;

use const null, true;

use function array_filter, implode, in_array;

/**
 * Class Message
 *
 * @package McMatters\SlackApi
 */
class Message
{
    public const ICON_TYPE_EMOJI = 'icon_emoji';
    public const ICON_TYPE_URL = 'icon_url';

    /**
     * @var string
     */
    protected $webhookUrl;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $icon = ':robot_face:';

    /**
     * @var string
     */
    protected $iconType = self::ICON_TYPE_EMOJI;

    /**
     * @var \McMatters\SlackApi\Attachment[]
     */
    protected $attachments = [];

    /**
     * @var array
     */
    protected $custom = [];

    /**
     * Message constructor.
     *
     * @param string $webhookUrl
     * @param string|null $text
     */
    public function __construct(string $webhookUrl, string $text = null)
    {
        $this->webhookUrl = $webhookUrl;
        $this->text = $text;
    }

    /**
     * @param string $webhookUrl
     * @param string|null $text
     *
     * @return \McMatters\SlackApi\Message
     */
    public static function make(string $webhookUrl, string $text = null): Message
    {
        return new static($webhookUrl, $text);
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
        $this->checkIconType($type);

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
     * @param array $headers
     *
     * @return array
     *
     * @throws \RuntimeException
     * @throws \McMatters\Ticl\Exceptions\RequestException
     * @throws \McMatters\Ticl\Exceptions\JsonDecodingException
     */
    public function send(array $headers = []): array
    {
        return (new Client())
            ->get($this->webhookUrl, [
                'headers' => $headers,
                'json' => $this->preparePayload(),
            ])
            ->json();
    }

    /**
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function preparePayload(): array
    {
        $this->checkRequiredVariables();

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

    /**
     * @param string $type
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function checkIconType(string $type = self::ICON_TYPE_EMOJI): void
    {
        $types = [self::ICON_TYPE_EMOJI, self::ICON_TYPE_URL];

        if (!in_array($type, $types, true)) {
            throw new InvalidArgumentException(
                'Icon type must be '.implode(' or ', $types)
            );
        }
    }

    /**
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function checkRequiredVariables(): void
    {
        if (null === $this->text && empty($this->attachments)) {
            throw new RuntimeException('Missing text message or attachments');
        }
    }
}
