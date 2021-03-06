# Changelog

<!-- We follow Semantic Versioning (http://semver.org/) and Keep a Changelog principles (http://keepachangelog.com/) --> 

## Zatím nevydáno
- BC: Vyžadováno `psr/http-factory-implementation` (PSR-17) a `psr/http-client-implementation` (PSR-18).

## 1.1.0 - 2021-03-23
- Minimální vyžadovaná verze PHP je 7.3.
- Podpora PHP 8.0.

## 1.0.0 - 2019-09-22
- Přidána možnost filtrovat při načtení seznamu článků pouze krajské články (pomocí metody `onlyRegional()`).
- Přidána možnost filtrovat při načtení seznamu akcí pouze akce, které organizuje daná jednotka (např. kraj) a její podjednotky (skupiny, oddíly) (pomocí metody `onlyByUnitAndSubunits()`).
- V objektech akcí (`EventPreview` a `EventDetail`) nahrazena metoda `isNationwide()` metodou `getLocalization()`, která vrací instanci výčtového typu `EventLocalization`.
- Přidána možnost filtrovat při načtení seznamu akcí pouze akce dle zadaného typu lokalizace (regionální / celorepublikové) (pomocí metody `setLocalization()`).
- U pionýrských skupin přidána hodnota jejich IČO.

## 0.0.2 - 2018-10-23
- Jméno kategorie článku je dostupné prostřednictvím metody `getCategory()`, ID kategorie článku prostřednictvím `getCategoryId()`.
- Při filtrování pomocí kategorie článku metodou `setCategory()` se nově předává její ID, nikoliv instance `ArticleCategory`.

## 0.0.1 - 2018-07-20
- První verze s podporou načítání článků, akcí a pionýrských skupin.
