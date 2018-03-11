## Slack API

### Installation

```bash
composer require mcmatters/slack-api
```

### Usage

```php
<?php

declare(strict_types = 1);

use McMatters\SlackApi\Message;

require 'vendor/autoload.php';

Message::make('WEBHOOK_URL')
    ->from('Foo')
    ->to('#bar')
    ->icon(':robot_face:')
    ->text('Hello world')
    ->send();
```
