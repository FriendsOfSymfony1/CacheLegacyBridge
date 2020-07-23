# [PoC] Symfony1 Cache Legacy bridge

Cache bridge to use modern Symfony cache systems in Symfony1 projects.

This library bridges the functionality offered by the Symfony Cache component to the legacy Symfony1.
Classes defined in this library can be used in place of the Symfony1 `sfCache`. 

## Status
This library is a Proof-of-Concept, do not use unless you are planning to contribute to its code.

## Usage
This library is meant to be used as a replacement for `sfCache` classes.

Before:
```yaml
  # factories.yml
  cache:
    class: sfAPCCache
    param:
      prefix: 'your-cache-prefix'
```

After:
```yaml
  # factories.yml
  cache:
    class: Symfony1\LegacyBridge\Cache\APCUCache
    param:
      prefix: 'your-cache-prefix'
```
