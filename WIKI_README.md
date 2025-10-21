# Wiki Updates for Framework Version 3.0

This directory contains all necessary documentation and files to update the wiki pages for the Vaííčko framework version 3.0.

## Quick Start

To apply the wiki updates, choose one of these options:

### Option 1: Apply Using Git Patch (Recommended)

```bash
# Clone the wiki repository
git clone https://github.com/thevajko/vaiicko.wiki.git
cd vaiicko.wiki

# Apply the patch
git am /path/to/0001-Update-wiki-pages-for-version-3.0-with-new-structure.patch

# Push changes
git push origin master
```

### Option 2: Manual Application

Follow the detailed instructions in [`APPLY_WIKI_UPDATES.md`](APPLY_WIKI_UPDATES.md).

## Files in This Update

| File | Purpose |
|------|---------|
| `WIKI_UPDATE_SUMMARY.md` | Comprehensive overview of all changes and rationale |
| `WIKI_UPDATES.md` | High-level summary of changes made |
| `APPLY_WIKI_UPDATES.md` | Detailed step-by-step application instructions |
| `0001-Update-wiki-pages-for-version-3.0-with-new-structure.patch` | Git patch file with all changes |

## What Changed?

Version 3.0 introduced a major reorganization:

- **Framework code** moved from `App\Core\*` to `Framework\*` namespace
- **Configuration** simplified from `App\Config\Configuration` to `App\Configuration`
- **Document root** changed to `public/` directory
- **PHP requirement** updated to version 8.3

The wiki documentation needed to be updated to reflect these changes so students can follow along correctly.

## Changes Summary

9 wiki pages were updated:

1. ✅ Installation instructions (PHP 8.3, public/ directory)
2. ✅ URL generation (LinkGenerator namespace)
3. ✅ Page layouts (Configuration path)
4. ✅ Database connection (Connection and Model namespaces)
5. ✅ Custom models (Model namespace)
6. ✅ Model persistence (Model namespace)
7. ✅ SQL queries (DB classes namespaces)
8. ✅ Error handling (Configuration path)
9. ✅ Authentication (Auth and IAuthenticator namespaces)

## Verification

All changes have been:
- ✅ Tested locally in the wiki repository clone
- ✅ Verified against the actual framework source code
- ✅ Checked for consistency across all pages
- ✅ Reviewed for correct namespace references

## Need Help?

1. Read [`WIKI_UPDATE_SUMMARY.md`](WIKI_UPDATE_SUMMARY.md) for context and details
2. Follow [`APPLY_WIKI_UPDATES.md`](APPLY_WIKI_UPDATES.md) for instructions
3. Check [`WIKI_UPDATES.md`](WIKI_UPDATES.md) for a quick reference

## Status

- ✅ All wiki pages updated locally
- ✅ Patch file created and tested
- ✅ Documentation complete
- ⏳ Awaiting push to wiki repository (requires authentication)

---

**Note**: The wiki repository (https://github.com/thevajko/vaiicko.wiki.git) is separate from the main repository, so updates must be applied manually by someone with write access to the wiki.
