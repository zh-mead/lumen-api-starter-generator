<h1 align="center"> lumen-api-starter-generator </h1>

<p align="center"> .</p>


## Installing

```shell
$ composer require zh-mead/lumen-api-starter-generator -vvv
```

## Configuration

Inside your bootstrap/app.php file, add:
```
$app->register(ZhMead\LumenApiStarterGenerator\LumenGeneratorServiceProvider::class);
```

## Usage

```
➜  $ php artisan make:model Permission -a
Controller created successfully.
Service created successfully.
Resource created successfully.
Criteria created successfully.
Presenter created successfully.
Eloquent created successfully.
Repository created successfully.
Validator created successfully.
Transformer created successfully.
Created Migration: 2020_10_22_035537_create_permissions_table
Model created successfully.
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/zh-mead/lumen-api-starter-generator/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/zh-mead/lumen-api-starter-generator/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT

## Thank
1、[GitHub - chunpat/lumen-api-starter-generator](https://github.com/chunpat/lumen-api-starter-generator)
