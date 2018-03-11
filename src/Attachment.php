<?php

declare(strict_types = 1);

namespace McMatters\SlackApi;

use DateTime;
use InvalidArgumentException;
use function array_filter, method_exists, str_replace, ucwords;

/**
 * Class Attachment
 *
 * @package McMatters\SlackApi
 */
class Attachment implements Arrayable
{
    /**
     * @var string
     */
    protected $fallback;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $titleLink;

    /**
     * @var string
     */
    protected $pretext;

    /**
     * @var string
     */
    protected $authorName;

    /**
     * @var string
     */
    protected $authorLink;

    /**
     * @var string
     */
    protected $authorIcon;

    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var string
     */
    protected $thumbUrl;

    /**
     * @var string
     */
    protected $footer;

    /**
     * @var string
     */
    protected $footerIcon;

    /**
     * @var string
     */
    protected $ts;

    /**
     * @var AttachmentField[]
     */
    protected $fields = [];

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public static function make(array $attributes = []): Attachment
    {
        return new static($attributes);
    }

    /**
     * @param string $fallback
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setFallback(string $fallback): self
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * @param string $color
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $titleLink
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setTitleLink(string $titleLink): self
    {
        $this->titleLink = $titleLink;

        return $this;
    }

    /**
     * @param string $pretext
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setPretext(string $pretext): self
    {
        $this->pretext = $pretext;

        return $this;
    }

    /**
     * @param string $authorName
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * @param string $authorLink
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setAuthorLink(string $authorLink): self
    {
        $this->authorLink = $authorLink;

        return $this;
    }

    /**
     * @param string $authorIcon
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setAuthorIcon(string $authorIcon): self
    {
        $this->authorIcon = $authorIcon;

        return $this;
    }

    /**
     * @param string $imageUrl
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @param string $thumbUrl
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setThumbUrl(string $thumbUrl): self
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * @param string $footer
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setFooter(string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @param string $footerIcon
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setFooterIcon(string $footerIcon): self
    {
        $this->footerIcon = $footerIcon;

        return $this;
    }

    /**
     * @param string|int|DateTime $ts
     *
     * @return \McMatters\SlackApi\Attachment
     */
    public function setTs($ts): self
    {
        $this->ts = $ts instanceof DateTime ? $ts->getTimestamp() : $ts;

        return $this;
    }

    /**
     * @param AttachmentField[] $fields
     *
     * @return \McMatters\SlackApi\Attachment
     * @throws \InvalidArgumentException
     */
    public function setFields(array $fields): self
    {
        foreach ($fields as $field) {
            if (!$field instanceof AttachmentField) {
                throw new InvalidArgumentException(
                    'Expected array of AttachmentField elements'
                );
            }
        }

        $this->fields = $fields;

        return $this;
    }

    /**
     * @param AttachmentField $field
     *
     * @return Attachment
     */
    public function addField(AttachmentField $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'fallback'    => $this->fallback,
            'color'       => $this->color,
            'text'        => $this->text,
            'title'       => $this->title,
            'title_link'  => $this->titleLink,
            'pretext'     => $this->pretext,
            'author_name' => $this->authorName,
            'author_link' => $this->authorLink,
            'author_icon' => $this->authorIcon,
            'image_url'   => $this->imageUrl,
            'thumb_url'   => $this->thumbUrl,
            'footer'      => $this->footer,
            'footer_icon' => $this->footerIcon,
            'ts'          => $this->ts,
            'fields'      => $this->getFields(),
        ]);
    }

    /**
     * @param array $attributes
     *
     * @return void
     */
    protected function setAttributes(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $key = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

            if (method_exists($this, "set{$key}")) {
                $this->{"set{$key}"}($value);
            }
        }
    }

    /**
     * @return array
     */
    protected function getFields(): array
    {
        $fields = [];

        foreach ($this->fields as $field) {
            $fields[] = $field->toArray();
        }

        return $fields;
    }
}
