# ZenginCode

The PHP implementation of ZenginCode.

ZenginCode is datasets of bank codes and branch codes for japanese.

## Requirements

- PHP 8.1 or higher

## Installation

```bash
composer require zengin-code/zengin-php
```

## Usage

```php
$banks = ZenginCode::all();

foreach ($banks as $bank) {
    echo "{$bank->code}: {$bank->name}\n";
}
```

## Development

### Running Tests

```bash
composer test
```

### Static Analysis

```bash
composer phpstan
```

## License

MIT License

## Related Projects

- [zengin-rb](https://github.com/zengin-code/zengin-rb) - Ruby implementation
- [zengin-js](https://github.com/zengin-code/zengin-js) - JavaScript implementation
- [zengin-py](https://github.com/zengin-code/zengin-py) - Python implementation
