Di Senpai
=========
Auto resolving dependency injector.

Usage
-----
require the package

    $ composer require codeia/di-senpai

create a container

```lang=php
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
        return new \PDO('sqlite::memory:');
    }
}

$container = new AutoResolve((new ObjectGraphBuilder(new Module))->withScoped([
    // map the module method names to their return types
    'auth' => [Authenticator::class],
    'security' => [Authorization::class],
    'acl' => [SecurityPolicy::class],
    'users' => [UserRepository::class],
    'db' => [PDO::class],
])->build());
```

call your senpai

```lang=php
class LoginController {
    /** @var site\service\Authenticator */
    private $auth;
    /** @var site\service\UserRepository */
    private $users;
    /** @var site\service\Authorization */
    private $checker;

    function __construct(Senpai $pls) {
        $pls->inject($this, Senpai::NO);
    }
}
```

build your object with their privates populated

```lang=php
$loginController = $container->get(LoginController::class);
```

WARNING
-------
This ties your classes to the DI framework! **AVOID THIS IF YOU CAN!** This
severely limits the reusability of your classes. I myself only use this on
classes that I probably won't ever reuse (like controllers) and only if they
have a lot of dependencies (5 is my hwm).

Other legitimate uses are in frameworks where you don't have control over the
instantiation and only provides hooks for your own code. Like activities in
Android.

Even so, the `AutoResolve` and `ObjectGraph` classes are still handy can be
used on their own.
