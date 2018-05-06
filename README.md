# PHP API klient pro Pionyr.cz

Pro využití například na webech krajských organizací poskytuje web pionyr.cz API, skrze které se dají načítat články,
akce, termíny a seznamy jednotek (pionýrských skupin, oddílů, klubů).

Tato knihovna poskytuje nástroje pro snadnou práci s tímto API. Knihovna vyžaduje PHP verze 7.1 a novější.

## Instalace
To-Be-Done

## Použití

Pro použití potřebujeme znát *API token*, který přidělí Ústředí Pionýra. Tokenem je zároveň identifikován uživatel
a je tím stanoven i rozsah přístupů (např. tokenem vystaveným pro jeden kraj nemůžeme přistupovat k seznamu akcí jiného kraje apod.).

Token se také váže na doménu (instanci) API - v případě použití testovací instance API je třeba použít i token vystavený právě pro
tuto instanci (token pro "produkční" instanci zde nebude fungovat).

Na začátku práce s API je třeba nejprve vytvořit instanci objektu `PionyrCz` a předat mu API token:

```php
$pionyrCz = new PionyrCz('my-api-token');
```

Pokud chceme pracovat s jinou instancí API než "produkční" na pionyr.cz, můžeme předat jako druhý parametr URL API:

```php
$pionyrCz = new PionyrCz('my-api-test-token', 'http://staging.pionyr.cz/api/');
```

V dalších ukázkách se pracuje s tímto objektem `$pionyrCz`.

### Články

#### Načtení seznamu článků

Vracejí se pouze publikované články odpovídající právům daného tokenu, které se mají momentálně zobrazovat.

Seznam je stránkovaný po 30 položkách na stránku a je možné jej filtrovat dle kategorie článku.

```php
$response = $pionyrCz->request()
    ->articles()
    ->setPage(3) // volitelné, není-li nastaveno, načte se první strana výpisu
    ->setCategory(ArticleCategory::VZDELAVANI) // volitelné filtrování dle kategorie, není-li nastaveno, načtou se články ve všech kategoriích
    ->send();

echo $response->getPageCount(); // vypíše celkový počet stránek které daný seznam obsahuje
echo $response->getItemCount(); // vypíše celkový počet položek (na všech stránkách) které daný seznam obsahuje

foreach ($response->getData() as $article) {
    echo $article->getUuid();           // jedinečné ID článku (UUID)
    echo $article->getShortUuid();      // zkrácené jedinečné ID článku - pro použití například v URL
    echo $article->getTitle();          // název článku 
    echo $article->getDatePublished();  // datum publikování
    echo $article->getCategory();       // kategorie článku
    echo $article->getAuthorName();     // jméno a příjmení autora článku
    echo $article->getPerex();          // text perexu - krátký úvod článku
    echo $article->getPerexPhotoUrl();  // URL úvodní fotky k článku
}
```

#### Načtení detailu jednoho článku

Detail článku obsahuje stejné položky, jako jeden článek v seznamu, a k tomu je doplněn o dalších údaje.

```php
$response = $pionyrCz->request()
    ->article('article-uuid') // je třeba předat UUID nebo Short UUID článku
    ->send();

$article = $response->getData();

echo $article->getUuid();           // jedinečné ID článku (UUID)
echo $article->getShortUuid();      // zkrácené jedinečné ID článku - pro použití například v URL
echo $article->getTitle();          // název článku
echo $article->getDatePublished();  // datum publikování
echo $article->getCategory();       // kategorie článku
echo $article->getAuthorName();     // jméno a příjmení autora článku
echo $article->getPerex();          // text perexu - krátký úvod článku
echo $article->getPerexPhotoUrl();  // URL fotky k článku
echo $article->getText();           // HTML text článku
echo $article->getTextPhotoUrl();   // URL fotky k textu článku
echo $article->getDateShowFrom();   // zobrazit článek od
echo $article->getDateShowTo();     // zobrazit článek jako aktualitu do
echo $article->isNews();            // je článek aktualita na www.pionyr.cz?
echo $article->isNewsForMembersPublic(); // je článek veřejná aktualita pro členy?
echo $article->isNewsForMembersPrivate(); // je článek aktualita pro členy po přihlášení?
echo $article->isMyRegion();        // můj krajský web (?)
echo $article->isMozaika();         // odeslat do Mozaiky Pionýra?
echo $article->isOfferedToOtherRegions(); // je článek nabídnut dalším krajům?
echo $article->getRegions();        // pole se seznamem KOP, ve kterých se má článek zobrazovat
echo $article->getPhotos();         // pole fotografií článku (každá fotografie obsahuje UR a popisek)
echo $article->getLinks();          // pole odkazů u článku (každý odkaz obsahuje URL a popisek)
```

### Akce
To-Be-Done

### Jednotky
To-Be-Done

## Changelog - seznam změn
Pro seznam změn viz soubor [CHANGELOG.md](CHANGELOG.md). Dodržujeme [sémantické verzování](http://semver.org/).

## Licence
Knihovna je zveřejněna jako open-source pod licencí [MIT](LICENCE.md).