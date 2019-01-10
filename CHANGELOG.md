# Changelog

<!-- We follow Semantic Versioning (http://semver.org/) and Keep a Changelog principles (http://keepachangelog.com/) --> 

## Zatím nevydáno
- Přidána možnost filtrovat při načtení seznamu článků pouze krajské články (pomocí metody `onlyRegional()`).
- Přidána možnost filtrovat při načtení seznamu akcí pouze akce, které organizuje daná jednotka (např. kraj) a její podjednotky (skupiny, oddíly) (pomocí metody `onlyByUnitAndSubunits()`).

## 0.0.2 - 2018-10-23
- Jméno kategorie článku je dostupné prostřednictvím metody `getCategory()`, ID kategorie článku prostřednictvím `getCategoryId()`.
- Při filtrování pomocí kategorie článku metodou `setCategory()` se nově předává její ID, nikoliv instance `ArticleCategory`.

## 0.0.1 - 2018-07-20
- První verze s podporou načítání článků, akcí a pionýrských skupin.
