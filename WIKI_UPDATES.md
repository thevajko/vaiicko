# Wiki Updates for Version 3.0

This document summarizes the updates made to the wiki pages (https://github.com/thevajko/vaiicko.wiki.git) to reflect changes in version 3.0.

## Summary of Changes

The following wiki pages have been updated to reflect the framework reorganization in version 3.0:

### Key Changes:

1. **PHP Version Requirement**: Updated from PHP 8.0 to PHP 8.3
2. **Document Root**: Changed from project root to `public/` directory
3. **Namespace Changes**:
   - `App\Core\*` → `Framework\Core\*`
   - `App\Config\Configuration` → `App\Configuration`
   - `App\Auth\*` → `Framework\Auth\*`
   - `App\Core\DB\*` → `Framework\DB\*`

### Files Updated:

1. **01-Inštalácia.md**
   - Updated PHP version requirement to 8.3
   - Updated document root reference to `public/` directory

2. **07-Vytváranie-URL-adries.md**
   - Updated `App\Core\LinkGenerator` → `Framework\Core\LinkGenerator`

3. **10-Rozloženie-celej-stránky.md**
   - Updated `App\Config\Configuration` → `App\Configuration`

4. **11-Pripojenie-k-databáze.md**
   - Updated `App\Core\DB\Connection` → `Framework\DB\Connection`
   - Updated `App\Core\Model` → `Framework\Core\Model`
   - Updated `Config\Configuration` → `App\Configuration`

5. **12-Tvorba-vlastných-modelov.md**
   - Updated `App\Core\Model` → `Framework\Core\Model` in text and code examples

6. **14-Ukladanie-modelu-do-DB.md**
   - Updated `App\Core\Model` → `Framework\Core\Model`

7. **16-Zložitejšie-SQL-dopyty.md**
   - Updated `App\Core\Model` → `Framework\Core\Model`
   - Updated `App\Core\DB\Connection` → `Framework\DB\Connection`

8. **18-Ošetrovanie-chýb.md**
   - Updated `App\Config\Configuration` → `App\Configuration`

9. **19-Autentifikácia.md**
   - Updated `Config\Configuration` → `App\Configuration`
   - Updated `App\Auth\DummyAuthenticator` → `Framework\Auth\DummyAuthenticator`
   - Updated `App\Core\IAuthenticator` → `Framework\Core\IAuthenticator`

## How to Apply These Changes

The changes have been committed locally to the wiki repository clone. To apply them to the official wiki:

```bash
cd /path/to/vaiicko.wiki
git pull
# Or manually apply the changes from this document
```

## Detailed Change List

All changes maintain backward compatibility in terms of functionality while updating references to match the new framework structure where:
- Framework core classes are in the `Framework\` namespace
- Application configuration is directly in the `App\` namespace
- Document root is properly set to the `public/` directory

These updates ensure that the documentation accurately reflects the version 3.0 RC1 framework structure.
