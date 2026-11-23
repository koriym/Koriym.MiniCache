# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

Not yet released: `MiniCache` (PHP file cache), `MiniCacheApc`, and the PSR-16 and symfony/cache-contracts adapters. Requires PHP 8.0 or later.

### Fixed

- `MiniCache` writes values with `var_export`, so they read back unchanged: `false` no longer reads back as `null`, `true` and `1.0` no longer as `1`, and strings containing `'` or a trailing backslash no longer cause a `ParseError` or run as PHP code. Clear cache directories written by earlier `1.x-dev` snapshots.
