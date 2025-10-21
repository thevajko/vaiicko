# How to Apply Wiki Updates

Since direct push to the wiki repository requires authentication, here are the manual steps to apply the changes:

## Option 1: Using the Patch File

1. Clone the wiki repository (if not already cloned):
   ```bash
   git clone https://github.com/thevajko/vaiicko.wiki.git
   cd vaiicko.wiki
   ```

2. Download the patch file `0001-Update-wiki-pages-for-version-3.0-with-new-structure.patch` from this PR

3. Apply the patch:
   ```bash
   git am 0001-Update-wiki-pages-for-version-3.0-with-new-structure.patch
   ```

4. Push the changes:
   ```bash
   git push origin master
   ```

## Option 2: Manual Edit

If you prefer to make changes manually, here are the specific changes needed:

### 01-Inštalácia.md
- Line 10: Change `PHP min. vo verzii 8.0 a vyššej` to `PHP min. vo verzii 8.3 a vyššej`
- Lines 30-31: Change text from "adresár projektu" to "adresár `public` v rámci projektu"

### 07-Vytváranie-URL-adries.md
- Line 14: Change `/** @var \App\Core\LinkGenerator $link */` to `/** @var \Framework\Core\LinkGenerator $link */`
- Line 22: Update the note to reference `Framework\Core\LinkGenerator`

### 10-Rozloženie-celej-stránky.md
- Line 9: Change `App\Config\Configuration.php` to `App\Configuration.php`

### 11-Pripojenie-k-databáze.md
- Line 4: Change `App\Core\DB\Connecton` to `Framework\DB\Connection`
- Line 4: Change `App\Core\Model` to `Framework\Core\Model`
- Line 5: Change `Config\Configuration` to `App\Configuration`

### 12-Tvorba-vlastných-modelov.md
- Line 9: Change `App\Core\Model` to `Framework\Core\Model`
- Line 23: Change note reference from `App\Core\Model` to `Framework\Core\Model`
- Line 33: Change `use App\Core\Model;` to `use Framework\Core\Model;`

### 14-Ukladanie-modelu-do-DB.md
- Line 33: Change `App\Core\Model` to `Framework\Core\Model` in the note

### 16-Zložitejšie-SQL-dopyty.md
- Line 4: Change `App\Core\Model` to `Framework\Core\Model`
- Line 5: Change `App\Core\DB\Connection` to `Framework\DB\Connection`

### 18-Ošetrovanie-chýb.md
- Line 53: Change `App\Config\Configuration` to `App\Configuration`

### 19-Autentifikácia.md
- Line 11: Change `Config\Configuration::LOGIN_URL` to `App\Configuration::LOGIN_URL`
- Line 15: Change `Config\Configuration` to `App\Configuration`
- Line 16: Change `App\Auth\DummyAuthenticator` to `Framework\Auth\DummyAuthenticator`
- Line 16: Change `App\Core\IAuthenticator` to `Framework\Core\IAuthenticator`
- Line 18: Change `App\Core\IAuthenticator` to `Framework\Core\IAuthenticator`
- Line 19: Change `App\Auth\DummyAuthenticator` to `Framework\Auth\DummyAuthenticator`
- Line 35: Change `/** @var \App\Core\IAuthenticator $auth */` to `/** @var \Framework\Core\IAuthenticator $auth */`

## Verification

After applying the changes, verify that:
1. All namespace references point to the correct location
2. Configuration class is referenced as `App\Configuration` (not `App\Config\Configuration`)
3. Framework classes use the `Framework\` namespace
4. Document root references point to the `public/` directory
5. PHP version requirement is 8.3

## Questions?

If you have any questions about these changes, refer to the `WIKI_UPDATES.md` file for more context.
