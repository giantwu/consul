# Upgrade easyswoole/spl to 2.x

## Goal

Align `easyswoole/consul` with EasySwoole ecosystem SPL 2.x and http-client 1.6.2, while preserving Consul request field mapping behavior.

## Dependency constraints

- `php: >=8.1`
- `easyswoole/spl: ^2.0`
- `easyswoole/http-client: ^1.6.2`
- `easyswoole/phpunit: ^1.1` (dev; required for PHP 8.1+ / PHPUnit 9)

## Compatibility layer

SPL 2.x removed `setKeyMapping()` / `toArrayWithMapping()`. This library depends on them for camelCase → Consul PascalCase keys.

Restore that behavior on `BaseCommand` (extends `SplBean`):

- Keep overridable `setKeyMapping(): array` (`['API键' => 'bean属性']`)
- Add `toArrayWithMapping()` that maps keys after `toArray()`
- Leave existing Request subclasses unchanged
- `Config` continues to extend `SplBean` directly (no key mapping)

## BaseFunc / HttpClient

- Keep using `toArrayWithMapping()` via `BaseCommand`
- `HttpClient` 1.6.2 `get` / `put` / `postJson` / `delete` / `setHeader` remain compatible
- `Response` getters used by `checkResponse()` remain available
- No public API surface changes for Consul wrappers

## Verification

1. Composer resolves `spl` 2.x and `http-client` >= 1.6.2
2. Request JSON/query keys remain Consul PascalCase
3. Install / class load succeeds; run tests when environment allows
