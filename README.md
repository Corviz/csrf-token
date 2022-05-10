# corviz/csrf-token
Creates/Verify CSRF tokens

## Install with Composer

```
composer require corviz/csrf-token
```

## Basic usage

Create a token string:
```php
<?php

use Corviz\CsrfToken\Token;

$token = new Token();
$tokenStr = $token->generate();
echo $tokenStr;

?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $tokenStr; ?>"/>
    <!-- ... -->
</form>
```

Verify token string:
```php
<?php

use Corviz\CsrfToken\Token;

$token = new Token();
$tokenStr = $_POST['csrf_token'];

if ($token->verify($tokenStr)) {
    //Valid token
} else {
    //Invalid token
}
```

<strong>Important!</strong>  
By default, keys and tokens are stored in Sessions. If you're using default components, 
make sure to call `session_start()` or an Exception will be thrown.

## Advanced

### Different tokens according to the current form:

It is a good idea to differ tokens across your pages/forms. To do this, all you have to do is set an identifier
before generating/verifying your token string:

```php
<?php

use Corviz\CsrfToken\Token;

$token = new Token();
$identifier = 'form-1';

//Create new token string
$tokenStr = $token->generate($identifier);

//Verify incoming token string:
echo $token->verify($_POST['csrf_token'], $identifier) ? 'Valid csrf token' : 'Invalid csrf token';
```

### Custom storage:

If you want to use another storage instead of Sessions, first you will have to declare it:

```php
<?php

use Corviz\CsrfToken\StorageInterface;

class DatabaseStorage implements StorageInterface
{
    public function get(string $key) : mixed
    {
        //read '$key' from db
    }

    public function set(string $key, mixed $value) : void
    {
        //write '$key' value to db
    }

    public function isset(string $key) : bool
    {
        //checks if $key exists in database
    }

    public function unset(string $key) : void
    {
        //delete $key from db
    }
}
```

Then, just set it in your token, before generating a token string:
```php
<?php

use Corviz\CsrfToken\Token;

$token = new Token();
$storage = new DatabaseStorage();
$token->setStorage($storage);

echo $token->generate();
```

Before checking as well:
```php
<?php

use Corviz\CsrfToken\Token;

$token = new Token();
$storage = new DatabaseStorage();
$token->setStorage($storage);

echo $token->verify($_POST['csrf_token']) ? 'Valid' : 'Invalid';
```

### Custom key provider

Similar to custom storages, to create a custom key provider, you have to declare it:

```php
<?php

use Corviz\CsrfToken\KeyProviderInterface;
use \Corviz\CsrfToken\StorageInterface;

class MyKeyProvider implements KeyProviderInterface
{
    private StorageInterface $storage;
    
    public function generateKey() : string|false
    {
        $index = '...';
        
        if (!$this->storage->isset($index)) {
            // generate key and store it
            $hashKey = '...';
            $this->storage->set($index, $hashKey);
        }
        
        return $this->storage->get($index);
    }
    
    public function __construct(StorageInterface $storage) 
    {
        $this->storage = $storage;
    }
}
```

Before generating or verifying your token string, set your key provider:

```php
<?php

use Corviz\CsrfToken\Token;

$token = new Token();
$keyProvider = new MyKeyProvider($storage);
$token->setKeyProvider($keyProvider);

echo $token->generate();

//or 

echo $token->verify($_POST['csrf_token']) ? 'Valid' : 'Invalid';
```