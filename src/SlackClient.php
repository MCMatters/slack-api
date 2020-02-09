<?php

declare(strict_types = 1);

namespace McMatters\SlackApi;

use McMatters\Ticl\Client;

use const null;

/**
 * Class SlackClient
 *
 * @package McMatters\SlackApi
 */
class SlackClient
{
    /**
     * @var \McMatters\Ticl\Client
     */
    protected $httpClient;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * SlackClient constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://slack.com/api',
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
        ]);
    }

    /**
     * @param string $method
     * @param array $options
     *
     * @return array
     */
    public function get(string $method, array $options = []): array
    {
        return $this->call('get', $method, $options);
    }

    /**
     * @param string $method
     * @param array $options
     *
     * @return array
     */
    public function post(string $method, array $options = []): array
    {
        return $this->call('post', $method, $options);
    }

    /**
     * @param string $token
     *
     * @return \McMatters\SlackApi\SlackClient
     */
    public function withToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return \McMatters\SlackApi\SlackClient
     */
    public function useDefaultToken(): self
    {
        $this->token = null;

        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     *
     * @return array
     */
    protected function call(string $method, string $url, array $options): array
    {
        if ($this->token) {
            $options['headers']['Authorization'] = "Bearer {$this->token}";
        }

        return $this->httpClient->{$method}($url, $options)->json();
    }
}
