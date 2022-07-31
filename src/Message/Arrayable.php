<?php

declare(strict_types=1);

namespace McMatters\SlackApi\Message;

/**
 * Interface Arrayable
 *
 * @package McMatters\SlackApi\Message
 */
interface Arrayable
{
    /**
     * @return array
     */
    public function toArray(): array;
}
