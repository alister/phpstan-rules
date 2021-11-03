# phpstan-rules

[![Integrate](https://github.com/alister/phpstan-rules/workflows/Integrate/badge.svg?branch=main)](https://github.com/alister/phpstan-rules/actions)
[![Prune](https://github.com/alister/phpstan-rules/workflows/Prune/badge.svg?branch=main)](https://github.com/alister/phpstan-rules/actions)
[![Release](https://github.com/alister/phpstan-rules/workflows/Release/badge.svg?branch=main)](https://github.com/alister/phpstan-rules/actions)
[![Renew](https://github.com/alister/phpstan-rules/workflows/Renew/badge.svg?branch=main)](https://github.com/alister/phpstan-rules/actions)

[![Code Coverage](https://codecov.io/gh/alister/phpstan-rules/branch/main/graph/badge.svg)](https://codecov.io/gh/alister/phpstan-rules)
[![Type Coverage](https://shepherd.dev/github/alister/phpstan-rules/coverage.svg)](https://shepherd.dev/github/alister/phpstan-rules)

[![Latest Stable Version](https://poser.pugx.org/alister/phpstan-rules/v/stable)](https://packagist.org/packages/alister/phpstan-rules)
[![Total Downloads](https://poser.pugx.org/alister/phpstan-rules/downloads)](https://packagist.org/packages/alister/phpstan-rules)

Provides additional rules for [`phpstan/phpstan`](https://github.com/phpstan/phpstan).

## Installation

Run

```sh
$ composer require --dev alister/phpstan-rules
```

## Usage

All of the [rules](https://github.com/alister/phpstan-rules#rules) provided (and used) by this library are included in [`src/LoggerContext/rules.neon`](src/LoggerContext/rules.neon).

When you are using [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer), `rules.neon` will be automatically included (To be confirmed).

Otherwise you need to include `rules.neon` in your `phpstan.neon`:

```neon
includes:
   - vendor/alister/phpstan-rules/src/LoggerContext/rules.neon

parameters:
  logger_context:
    disallowed_namespaces:
      - App\Entity
      - App\DTOs
```

:bulb: You probably want to use these rules on top of the rules provided by:

* [`phpstan/phpstan`](https://github.com/phpstan/phpstan)
* [`phpstan/phpstan-deprecation-rules`](https://github.com/phpstan/phpstan-deprecation-rules)

## Rules

This package provides the following rules for use with [`phpstan/phpstan`](https://github.com/phpstan/phpstan):

* [`Alister\PHPStan\Rules\LoggerContext\KeyDotsRule`](https://github.com/alister/phpstan-rules#keydotsrule)
* [`Alister\PHPStan\Rules\LoggerContext\ObjectAsValuesRule`](https://github.com/alister/phpstan-rules#objectasvaluesrule)

### Rules

#### Disallow full stops as part of a logger context key

`LoggerContext\KeyDotsRule`

```php
// This would not be allowed:
$this->log->notice('The ID of a user:', ['user.id' => $user->getId()]); // key contains '.'

$this->log->notice('Logging this key as user_id is OK', ['user_id' => $user->getId()]);
```

##### Disallowing logging of values within a namespace

This rule allows you to specify objects from namespaces should not be logged in their entirety. Scalar values are allowed, but not entire objects.

```neon
parameters:
	logger_context:
		disallowed_namespaces:
		    - App\Entity
```

```php
// This would not be allowed:
$this->log->notice('here is a User object', ['user' => $user]); // $user is an object instance from \App\Entity namespace

$this->log->notice('Logging the user id is OK', ['user_id' => $user->getId()]);
```


## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/alister/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Credits

The repository layout, workflow & tests, is based on [ergebnis/phpstan-rules](https://github.com/ergebnis/phpstan-rules) (originally licensed under MIT).
