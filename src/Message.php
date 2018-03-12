<?php

declare(strict_types = 1);

namespace McMatters\SlackApi;

use InvalidArgumentException;
use RuntimeException;
use const null, true;
use const CURLOPT_HTTPHEADER, CURLOPT_POST, CURLOPT_POSTFIELDS, CURLOPT_RETURNTRANSFER, CURLOPT_URL;
use function array_filter, curl_close, curl_errno, curl_error, curl_init,
    curl_setopt, implode, in_array, json_encode;

/**
 * Class Message
 *
 * @package McMatters\SlackApi
 */
class Message
{
    const ICON_TYPE_EMOJI = 'icon_emoji';
    const ICON_TYPE_URL = 'icon_url';

    /**
     * @var string
     */
    protected $endpoint;

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
     * @var Attachment[]
     */
    protected $attachments = [];

    /**
     * Message constructor.
     *
     * @param string $endpoint
     * @param string|null $text
     */
    public function __construct(string $endpoint, string $text = null)
    {
        $this->endpoint = $endpoint;
        $this->text = $text;
    }

    /**
     * @param string $endpoint
     * @param string|null $text
     *
     * @return \McMatters\SlackApi\Message
     */
    public static function make(string $endpoint, string $text = null): Message
    {
        return new static($endpoint, $text);
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
     * @param Attachment $attachment
     *
     * @return \McMatters\SlackApi\Message
     */
    public function attach(Attachment $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param array $attachments
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
     * @return mixed
     * @throws \RuntimeException
     */
    public function send()
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type' => 'application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->preparePayload()));

        $response = curl_exec($curl);
        $errorNumber = curl_errno($curl);
        $errorMessage = curl_error($curl);

        curl_close($curl);

        if (0 !== $errorNumber) {
            throw new RuntimeException($errorMessage, $errorNumber);
        }

        return $response;
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    protected function preparePayload(): array
    {
        $this->checkRequiredVariables();

        return array_filter([
            'text'          => $this->text,
            'channel'       => $this->to,
            'username'      => $this->from,
            $this->iconType => $this->icon,
            'attachments'   => $this->getAttachments(),
        ]);
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
     * @throws \InvalidArgumentException
     */
    protected function checkIconType(string $type = self::ICON_TYPE_EMOJI)
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
     * @throws \RuntimeException
     */
    protected function checkRequiredVariables()
    {
        if (null === $this->text && empty($this->attachments)) {
            throw new RuntimeException('Missing text message or attachments');
        }
    }
}
