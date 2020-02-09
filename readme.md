## Slack API

### Installation

```bash
composer require mcmatters/slack-api
```

### Usage

```php
<?php

use McMatters\SlackApi\Message;
use McMatters\SlackApi\SlackClient;

require 'vendor/autoload.php';

// Send message to webhook url.
Message::make('WEBHOOK_URL')
    ->from('Foo')
    ->to('#bar')
    ->icon(':robot_face:')
    ->text('Hello world')
    ->send();

// Use slack client wrapper
$client = new SlackClient('TOKEN');
$client->get('users.info', ['query' => ['user' => 'W1234567890']]);
```
