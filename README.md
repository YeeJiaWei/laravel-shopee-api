# Laravel Shopee API


## Usage
First, initialise the Client
```php
$client = new  \Yeejiawei\LaravelShopeeApi\Client([
    'shop_id' => 'SHOP_ID',
    'access_token' => 'ACCESS_TOKEN',
]);
```

Second, get the node
```php
$chat = $client->chat();
```

Or use the function in the node
```php
$chat = $client->chat()->getConversation(197091754292034);
```

## Current avaialable Nodes
### Chat node
- getMessage
- sendMessage
- sendAutoreplyMessage
- getConversations
- getConversation
- deleteConversation
- getUnreadConversationCount
- pinConversation
- unpinConversation
- readConversationn
- unreadConversation
- getOfferToggleStatus
- setOfferToggleStatus
- uploadImage
