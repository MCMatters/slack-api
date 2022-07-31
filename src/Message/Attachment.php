<?php

declare(strict_types=1);

namespace McMatters\SlackApi\Message;

use DateTime;

use function array_filter;
use function array_map;
use function method_exists;
use function str_replace;
use function ucwords;

/**
 * Class Attachment
 *
 * @package McMatters\SlackApi\Message
 */
class Attachment implements Arrayable
{
    /**
     * @var string|null
     */
    protected ?string $fallback;

    /**
     * @var string|null
     */
    protected ?string $color;

    /**
     * @var string|null
     */
    protected ?string $text;

    /**
     * @var string|null
     */
    protected ?string $title;

    /**
     * @var string|null
     */
    protected ?string $titleLink;

    /**
     * @var string|null
     */
    protected ?string $pretext;

    /**
     * @var string|null
     */
    protected ?string $authorName;

    /**
     * @var string|null
     */
    protected ?string $authorLink;

    /**
     * @var string|null
     */
    protected ?string $authorIcon;

    /**
     * @var string|null
     */
    protected ?string $imageUrl;

    /**
     * @var string|null
     */
    protected ?string $thumbUrl;

    /**
     * @var string|null
     */
    protected ?string $footer;

    /**
     * @var string|null
     */
    protected ?string $footerIcon;

    /**
     * @var string|null
     */
    protected ?string $ts;

    /**
     * @var \McMatters\SlackApi\Message\AttachmentField[]
     */
    protected array $fields = [];

    /**
     * @var \McMatters\SlackApi\Message\Block[]
     */
    protected array $blocks = [];

    /**
     * @var array
     */
    protected array $custom = [];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public static function make(array $attributes = []): Attachment
    {
        return new static($attributes);
    }

    /**
     * @param string $fallback
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setFallback(string $fallback): Attachment
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * @param string $color
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setColor(string $color): Attachment
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setText(string $text): Attachment
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setTitle(string $title): Attachment
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $titleLink
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setTitleLink(string $titleLink): Attachment
    {
        $this->titleLink = $titleLink;

        return $this;
    }

    /**
     * @param string $pretext
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setPretext(string $pretext): Attachment
    {
        $this->pretext = $pretext;

        return $this;
    }

    /**
     * @param string $authorName
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setAuthorName(string $authorName): Attachment
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * @param string $authorLink
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setAuthorLink(string $authorLink): Attachment
    {
        $this->authorLink = $authorLink;

        return $this;
    }

    /**
     * @param string $authorIcon
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setAuthorIcon(string $authorIcon): Attachment
    {
        $this->authorIcon = $authorIcon;

        return $this;
    }

    /**
     * @param string $imageUrl
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setImageUrl(string $imageUrl): Attachment
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @param string $thumbUrl
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setThumbUrl(string $thumbUrl): Attachment
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * @param string $footer
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setFooter(string $footer): Attachment
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @param string $footerIcon
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setFooterIcon(string $footerIcon): Attachment
    {
        $this->footerIcon = $footerIcon;

        return $this;
    }

    /**
     * @param string|int|DateTime $ts
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setTs($ts): Attachment
    {
        $this->ts = $ts instanceof DateTime ? $ts->getTimestamp() : $ts;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Message\AttachmentField $field
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function addField(AttachmentField $field): Attachment
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Message\AttachmentField[] $fields
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function addFields(array $fields): Attachment
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        $this->fields = $fields;

        return $this;
    }

    /**
     * @param \McMatters\SlackApi\Message\Block $block
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function addBlock(Block $block): Attachment
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * @param array $blocks
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function addBlocks(array $blocks): Attachment
    {
        foreach ($blocks as $block) {
            $this->addBlock($block);
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return \McMatters\SlackApi\Message\Attachment
     */
    public function setCustom(array $data): Attachment
    {
        $this->custom = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'fallback' => $this->fallback,
            'color' => $this->color,
            'text' => $this->text,
            'title' => $this->title,
            'title_link' => $this->titleLink,
            'pretext' => $this->pretext,
            'author_name' => $this->authorName,
            'author_link' => $this->authorLink,
            'author_icon' => $this->authorIcon,
            'image_url' => $this->imageUrl,
            'thumb_url' => $this->thumbUrl,
            'footer' => $this->footer,
            'footer_icon' => $this->footerIcon,
            'ts' => $this->ts,
            'fields' => array_map(
                static fn (AttachmentField $field) => $field->toArray(),
                $this->fields ?? [],
            ),
            'blocks' => array_map(
                static fn (Block $block) => $block->toArray(),
                $this->blocks ?? [],
            ),
        ]) + $this->custom;
    }

    /**
     * @param array $attributes
     *
     * @return void
     */
    protected function setAttributes(array $attributes = []): void
    {
        foreach ($attributes as $key => $value) {
            $key = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

            if ($key === 'blocks' || $key === 'fields') {
                $setter = "add{$key}";
            } else {
                $setter = "set{$key}";
            }

            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }
    }
}
