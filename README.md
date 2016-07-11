DI-senpai
=========
Auto resolving dependency injector. Build your object graphs programmatically.

Installation
------------

    $ composer require codeia/di-senpai


Usage
-----
Create a container:

```php
class Module {
    function auth() {
        return new biz\Auth();
    }

    function security(ContainerInterface $c) {
        return new biz\Acl($c->get(SecurityPolicy::class));
    }

    function acl() {
        return new stub\AllowEveryone();
    }

    function users(ContainerInterface $c) {
        return new db\UserRepository($c->get(PDO::class));
    }

    function db() {
        return new PDO('sqlite::memory:');
    }
}

$provided = (new ObjectGraphBuilder(new Module))->withScoped([
    // map the module method names to their return types
    // you need to do this for interfaces; AutoResolve can handle the concrete
    // dependencies
    'auth' => [Authenticator::class],
    'security' => [Authorization::class],
    'acl' => [SecurityPolicy::class],
    'users' => [UserRepository::class],
    'db' => [PDO::class],
])->build();
$container = new AutoResolve($provided);
```
> You can also use any `Interop\Container\ContainerInterface` impl, but
> `AutoResolve` is quite useful and is what makes `Senpai` work its magic.
> You can wrap a `ContainerInterface` in an `AutoResolve`r then use that.

> `AutoResolve` and `ObjectGraph` both implement `ContainerInterface`, so you
> can use them in other frameworks that can take them, like `zend-expressive`.


Call your senpai:

```php
class LoginController {
    // must use FQCNs in the annotations

    /** @var site\service\Authenticator */
    private $auth;

    /** @var site\service\UserRepository */
    private $users;

    /** @var site\service\Authorization */
    private $checker;

    function __construct(Senpai $pls) {
        $pls->inject($this, Senpai::NO);
    }

    function isFullyUsable() {
        return !empty($this->auth)
            && !empty($this->users)
            && !empty($this->checker);
    }
}
```

Build your object and their privates will be populated:

> Leave out the second argument to `inject()` if you only want to inject
> public members.

```php
$loginController = $container->get(LoginController::class);
$loginController->isFullyUsable();
```

- Only the object's members are injected. The dependencies' insides are not
touched.
- No method injection is done. I don't believe setter injection should be a
thing.

WARNING
-------
This ties your classes to the DI framework! **AVOID THIS IF YOU CAN!** This
severely limits the reusability of your classes. I myself only use this on
classes that I probably won't ever reuse (like controllers) and only if they
have a lot of dependencies (5 is my threshold).

Other legitimate uses are in frameworks where you don't have control over the
instantiation and only provides hooks for your own code. Like activities in
Android. Don't know if there are any frameworks that still work like that, it
seems that lambdas are all the rage these days. Although again, I only recommend
it when you have 5 or more dependencies to populate.

Even so, the `AutoResolve` and `ObjectGraph` classes are still handy can be
used on their own.
