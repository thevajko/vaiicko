### Version 2.0
- Pridaná trieda `RedirectResponse`
- Automatická detekcia názvu tabuľky podľa názvu modelu a stĺpcov v tabuľke
- Pridaný modul autentifikácie
- Metóda `Model:getOne()` vracia teraz `null`, ak sa model nenájde
- Redizajnovaná prvá stránka FW, teraz prístupná bez prihlásenia
- Vytvorený kontroler `AdminController`, ktorý je dostupný až po prihlásení
- Fix, ak sa DB tabuľka alebo stĺpec volá rovnako ako rezervované slovo v DB
- Pridaný `files` attribút do triedy `Request`

### Version 1.0 
- Úvodná verzia FW