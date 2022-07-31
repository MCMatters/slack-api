<?php

declare(strict_types=1);

namespace McMatters\SlackApi;

use McMatters\Ticl\Client;

/**
 * Class WebhookClient
 *
 * @package McMatters\SlackApi
 */
class WebhookClient
{
    /**
     * @param string $webhookUrl
     * @param array|\McMatters\SlackApi\Arrayable $data
     * @param array $headers
     *
     * @return string
     */
    public static function send(
        string $webhookUrl,
        $data,
        array $headers = []
    ): string {
        return (new Client())
            ->withHeaders($headers)
            ->withJson($data instanceof Arrayable ? $data->toArray() : $data)
            ->post($webhookUrl)
            ->getBody();
    }
}
