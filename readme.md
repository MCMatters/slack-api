## Slack API

### Installation

```bash
composer require mcmatters/slack-api
```

### Usage

```php
<?php

use McMatters\SlackApi\Message\Message;
use McMatters\SlackApi\SlackClient;
use McMatters\SlackApi\WebhookClient;

require 'vendor/autoload.php';

// Send message to webhook url.
WebhookClient::send(
    'https://hooks.slack.com/services/YOUR_WEBHOOK_URL',
    Message::make('Hello world')
        ->from('HelloBot')
        ->to('#general')
        ->icon(':rocket:')
);

// Use Slack client wrapper.
$client = new SlackClient('TOKEN');
$client->get('users.info', ['user' => 'W1234567890']);
```
