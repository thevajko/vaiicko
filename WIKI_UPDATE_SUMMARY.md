# Wiki Update Summary for Version 3.0

## Overview

This PR contains the necessary updates to synchronize the wiki documentation with the framework reorganization introduced in version 3.0 RC1. The main repository underwent a significant restructuring where framework code was moved to a dedicated `Framework/` namespace and the application gained a proper `public/` directory as the document root.

## Changes Made

### 1. Framework Structure Updates

The most significant change was updating all namespace references to reflect the new structure:

**Before (v2.x):**
```
App/
├── Config/
│   └── Configuration.php
├── Core/
│   ├── Model.php
│   ├── IAuthenticator.php
│   ├── LinkGenerator.php
│   ├── DB/
│   │   └── Connection.php
│   └── ...
├── Auth/
│   └── DummyAuthenticator.php
└── ...
```

**After (v3.0):**
```
App/
├── Configuration.php         (moved up from Config/)
├── Controllers/
├── Models/
└── Views/

Framework/
├── Core/
│   ├── Model.php
│   ├── IAuthenticator.php
│   ├── LinkGenerator.php
│   └── ...
├── DB/
│   └── Connection.php
├── Auth/
│   └── DummyAuthenticator.php
└── ...

public/                        (new document root)
├── index.php
├── css/
├── js/
└── images/
```

### 2. Namespace Mapping

All references were updated according to this mapping:

| Old Namespace | New Namespace |
|---------------|---------------|
| `App\Core\*` | `Framework\Core\*` |
| `App\Config\Configuration` | `App\Configuration` |
| `App\Core\DB\*` | `Framework\DB\*` |
| `App\Auth\*` | `Framework\Auth\*` |

### 3. Technical Updates

- **PHP Version**: Updated minimum requirement from 8.0 to 8.3
- **Document Root**: Changed from project root to `public/` directory
- **Configuration**: Simplified path from `App\Config\Configuration` to `App\Configuration`

### 4. Files Updated

Nine wiki pages were modified:
1. **01-Inštalácia.md** - PHP version and document root
2. **07-Vytváranie-URL-adries.md** - LinkGenerator namespace
3. **10-Rozloženie-celej-stránky.md** - Configuration path
4. **11-Pripojenie-k-databáze.md** - Database connection classes
5. **12-Tvorba-vlastných-modelov.md** - Model base class
6. **14-Ukladanie-modelu-do-DB.md** - Model references
7. **16-Zložitejšie-SQL-dopyty.md** - Database query classes
8. **18-Ošetrovanie-chýb.md** - Configuration references
9. **19-Autentifikácia.md** - Authentication classes

## Files in This PR

1. **WIKI_UPDATES.md** - High-level summary of changes
2. **APPLY_WIKI_UPDATES.md** - Step-by-step instructions for applying updates
3. **0001-Update-wiki-pages-for-version-3.0-with-new-structure.patch** - Git patch file with all changes

## How to Apply

Since the wiki is in a separate repository (https://github.com/thevajko/vaiicko.wiki.git), the changes need to be applied manually. Two options are provided:

### Option 1: Using the Patch File (Recommended)
```bash
cd /path/to/vaiicko.wiki
git am /path/to/0001-Update-wiki-pages-for-version-3.0-with-new-structure.patch
git push origin master
```

### Option 2: Manual Edits
Follow the detailed instructions in `APPLY_WIKI_UPDATES.md` for line-by-line changes.

## Verification

All changes have been verified to ensure:
- ✅ Correct namespace references (Framework vs App)
- ✅ Proper Configuration class path
- ✅ Updated PHP version requirement
- ✅ Correct document root references
- ✅ Consistent code examples
- ✅ No broken links or references

## Impact

These updates ensure that:
1. Students following the wiki will use the correct class names and namespaces
2. Code examples will work with version 3.0 of the framework
3. Installation instructions reflect the new Docker configuration
4. All documentation aligns with the actual framework structure

## Notes

- No functionality changes were made, only documentation updates
- The changes are backward incompatible only in terms of namespace usage
- All existing concepts and patterns remain the same
- The MVC architecture and framework philosophy are unchanged

## Next Steps

After this PR is merged, a repository maintainer with wiki access should:
1. Apply the patch to the wiki repository
2. Verify the changes render correctly on GitHub wiki
3. Close this PR

## Questions?

For questions about these changes, please refer to:
- The main repository commit: `3b123a5` (Framework files reorganization)
- The Configuration class: `App/Configuration.php`
- The README.md file in the main repository
