<?php

declare(strict_types = 1);

namespace McMatters\SlackApi;

/**
 * Interface Arrayable
 *
 * @package McMatters\SlackApi
 */
interface Arrayable
{
    /**
     * @return array
     */
    public function toArray(): array;
}
