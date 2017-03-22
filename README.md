##### This package is in Alpha version. (not recommended for production)

This package catches & records all changes of an Eloquent model and stores these changes into a temporary table.
In order to actually update the model, you need to approve these changes.

You can use this package in order to moderate changes of your content performed by your application users.

## Possible Use Case
- User A creates a resource (post, comment or any Eloquent Model)
- User B modify this resource but the changes are not visible in website (`Post::all()`)
- Admin/moderator approve these changes (`$post->markApproved()`)
- Resource is now public and updated

## Installation
```php
composer require lonnyx/laravel-approvable
```

Then include the service provider inside `config/app.php`.

```php
'providers' => [
    ...
    LonnyX\Approvable\ApprovableServiceProvider::class,
    ...
];
```
Publishing

```
php artisan approvable:install
```

Database
```
php artisan migrate
```


## Prepare Model

Setting up a model for auditing couldn't be simpler. Just use the `LonnyX\Approvable\Approvable` trait in the model you wish to audit and implement the `LonnyX\Approvable\Contracts\Approvable`.
```php
use Illuminate\Database\Eloquent\Model;
use LonnyX\Approvable\Contracts\Approvable as ApprovableContract;
use LonnyX\Approvable\Approvable;

class User extends Model implements ApprovableContract;
{
    use Approvable;

    // ...

    // this is an example
    public function approvalCondition()
    {
        // if it's admin, we don't want to trigger Approvator
        return \Auth::user()->role == 'admin';
    }
}
```

**You are ready to go!**

```php
$post = Post::firstOrFail();
// title = Hi!

$post->update(['title' => 'Hello']);
// title = Hi!

$post->markApproved();
// title = Hello
```

## Credits
- This package is entirly based on [laravel-auditing](https://github.com/owen-it/laravel-auditing) (Big thanks to [Antério Vieira](https://github.com/anteriovieira), [Quetzy Garcia](https://github.com/quetzyg), [Raphael França](https://github.com/raphaelfranca))

## License

The laravel-auditing package is open source software licensed under the [MIT LICENSE](LICENSE.md)
