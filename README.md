# PHP API klient pro Pionyr.cz

[![Latest Stable Version](https://img.shields.io/packagist/v/pionyr/pionyr-cz-client.svg?style=flat-square)](https://packagist.org/packages/pionyr/pionyr-cz-client)
[![Coverage Status](https://img.shields.io/coveralls/pionyr/pionyr-cz-client/main.svg?style=flat-square)](https://coveralls.io/r/pionyr/pionyr-cz-client)
[![GitHub Actions Build Status](https://img.shields.io/github/workflow/status/pionyr/pionyr-cz-client/Tests%20and%20linting?style=flat-square&label=GitHub%20Actions%20build)](https://github.com/pionyr/pionyr-cz-client/actions)

Pro využití například na webech krajských organizací poskytuje web pionyr.cz API, skrze které se dají načítat články,
akce, termíny a seznamy jednotek (pionýrských skupin, oddílů, klubů).

Tato knihovna poskytuje nástroje pro snadnou práci s tímto API. Knihovna vyžaduje PHP verze 7.3 a novější.

## Instalace

Instalace knihovny se provede skrze [Composer](https://getcomposer.org/). Instalaci je možno spustit tímto příkazem:

```sh
$ composer require pionyr/pionyr-cz-client guzzlehttp/guzzle
```

Čímž se nainstaluje API klient, který bude používat jako transportní HTTP knihovnu Guzzle 7.

Knihovna používá pro HTTP abstrakci za využití specifikací PSR-7, PSR-17 a PSR-18. Není tak svázána s konkrétní
HTTP knihovnou. Díky tomu je dle potřeby možné použít i jinou HTTP knihovnu (např. takovou, kterou již využívá PHP framework,
ve kterém máme aplikaci, např. `symfony/http-client`).
Viz [seznam podporovaných HTTP knihoven](https://packagist.org/providers/psr/http-client-implementation).

Pokud bychom chtěli použít jako transportní knihovnu místo Guzzle 7 například cURL, nainstalujeme API klienta tímto příkazem:

```sh
$ composer require pionyr/pionyr-cz-client php-http/curl-client guzzlehttp/psr7
```

## Použití

Pro použití potřebujeme znát *API token*, který přidělí Ústředí Pionýra. Tokenem je zároveň identifikován uživatel
a je tím stanoven i rozsah přístupů (např. tokenem vystaveným pro jeden kraj nemůžeme přistupovat k seznamu akcí jiného kraje apod.).

Token se také váže na doménu (instanci) API - v případě použití testovací instance API je třeba rovněž použít token,
vystavený právě pro tuto instanci (token pro "produkční" instanci zde nebude fungovat).

Na začátku práce s API je třeba nejprve vytvořit instanci objektu `PionyrCz` a předat mu API token:

```php
$pionyrCz = new PionyrCz('my-api-token');
```

Pokud chceme pracovat s jinou instancí API než "produkční" na pionyr.cz, můžeme nastavit tzv. base URL API:

```php
use Pionyr\PionyrCz\PionyrCz;

$pionyrCz = new PionyrCz('my-api-test-token');
$pionyrCz->setBaseUrl('http://staging.pionyr.cz/api/');
```

V dalších ukázkách se pracuje s tímto objektem `$pionyrCz`.

#### Cachování odpovědí

Odpovědi z API je vhodné cachovat. Ukázku nastavení cachování pomocí PSR-6 je možné najít v souboru
[examples/caching.php](examples/caching.php).

### Články

#### Načtení seznamu článků

Vracejí se pouze publikované články odpovídající právům daného tokenu, které se mají momentálně zobrazovat.

Seznam je stránkovaný po 30 položkách na stránku a je možné jej filtrovat dle kategorie článku.

```php
use Pionyr\PionyrCz\Constants\ArticleCategory;

$response = $pionyrCz->request()
    ->articles()
    ->setPage(3) // volitelné, není-li nastaveno, načte se první strana výpisu
    ->setCategory(ArticleCategory::UVODNI_NOVINKY) // volitelné filtrování dle ID kategorie, není-li nastaveno, načtou se články ve všech kategoriích
    ->onlyRegional() // volitelné filtrování pouze krajských článků, není-li nastaveno, zobrazují se i "celostátání" články
    ->send();

echo $response->getPageCount(); // vypíše celkový počet stránek které daný seznam obsahuje
echo $response->getItemTotalCount(); // vypíše celkový počet položek (na všech stránkách) které daný seznam obsahuje

foreach ($response->getData() as $article) {
    echo $article->getUuid();           // jedinečné ID článku (UUID)
    echo $article->getShortUuid();      // zkrácené jedinečné ID článku - pro použití například v URL
    echo $article->getTitle();          // název článku 
    echo $article->getDatePublished()
        ->format('j. n. Y H:i:s');      // datum publikování
    echo $article->getCategory();       // kategorie článku
    echo $article->getCategoryId();     // ID kategorie článku
    echo $article->getAuthorName();     // jméno a příjmení autora článku
    echo $article->getPerex();          // text perexu - krátký úvod článku
    echo $article->getPerexPhotoUrl();  // URL úvodní fotky k článku (může být null)
}
```

#### Načtení detailu jednoho článku

Detail článku obsahuje stejné položky, jako jeden článek v seznamu, a k tomu je doplněn o další údaje.

```php
$response = $pionyrCz->request()
    ->article('article-uuid') // je třeba předat UUID nebo Short UUID článku
    ->send();

$article = $response->getData();

echo $article->getUuid();           // jedinečné ID článku (UUID)
echo $article->getShortUuid();      // zkrácené jedinečné ID článku - pro použití například v URL
echo $article->getTitle();          // název článku
echo $article->getDatePublished()
    ->format('j. n. Y H:i:s');  // datum publikování
echo $article->getCategory();       // kategorie článku
echo $article->getCategoryId();     // ID kategorie článku
echo $article->getAuthorName();     // jméno a příjmení autora článku
echo $article->getPerex();          // text perexu - krátký úvod článku
echo $article->getPerexPhotoUrl();  // URL fotky k článku (může být null)

echo $article->getText();           // HTML text článku
echo $article->getTextPhotoUrl();   // URL fotky k textu článku (může být null)
echo $article->getDateShowFrom()
    ->format('j. n. Y H:i:s');      // zobrazit článek jako aktualitu od (může být null)
echo $article->getDateShowTo()
    ->format('j. n. Y H:i:s');      // zobrazit článek jako aktualitu do (může být null)
echo $article->isNews();            // je článek aktualita na www.pionyr.cz?
echo $article->isNewsForMembersPublic(); // je článek veřejná aktualita pro členy?
echo $article->isNewsForMembersPrivate(); // je článek aktualita pro členy po přihlášení?
echo $article->isMyRegion();        // můj krajský web (?)
echo $article->isMozaika();         // odeslat do Mozaiky Pionýra?
echo $article->isOfferedToOtherRegions(); // je článek nabídnut dalším krajům?

foreach ($article->getRegions() as $region) { // pole se seznamem KOP, ve kterých se má článek zobrazovat
    echo $region;
}

foreach ($article->getPhotos() as $photo) { // pole fotografií článku
    echo $photo->getUrl();
    echo $photo->getTitle();
}

foreach ($article->getLinks() as $link) { // pole odkazů u článku
    echo $link->getUrl();
    echo $link->getTitle();
}
```

### Akce

#### Načtení seznamu akcí

Vracejí se pouze akce odpovídající právům daného tokenu.

Seznam je stránkovaný po 30 položkách na stránku a je možné jej filtrovat dle kategorie akce a dle termínu akce (jako
výchozí se načítají akce, mající datum začátku dnes nebo v budoucnu).

```php
use Pionyr\PionyrCz\Constants\EventCategory;
use Pionyr\PionyrCz\Constants\EventLocalization;

$response = $pionyrCz->request()
    ->events()
    ->setPage(3) // volitelné, není-li nastaveno, načte se první strana výpisu
    ->setCategory(EventCategory::TABOR()) // volitelné filtrování dle kategorie, není-li nastaveno, načtou se akce ve všech kategoriích
    ->onlyByUnitAndSubunits() // volitelné filtrování - je-li zapnuto, načtou se pouze akce, které organizuje aktuální jednotka které patří token (např. kraj) a její podjednotky (např. pionýrské skupiny a oddíly)
    ->setLocalization(EventLocalization::NATIONWIDE()) // volitelné filtrování dle lokalizace akce - je-li zapnuto, načtou se pouze akce se zadanou lokalizací, a to buďto regionální nebo pouze celorepublikové
    ->setDateFrom(new \DateTime('2018-01-01')) // volitelné filtrování dle data - je-li nastaveno, načtou se pouze akce konající se po tomto datu, jinak se načtou akce konající se ode dnešního dne
    ->setDateTo(new \DateTime('2018-08-31')) // volitelné filtrování dle data - je-li nastaveno, načtou se pouze akce konající se před tímto datem
    ->send();

echo $response->getPageCount();         // vypíše celkový počet stránek které daný seznam obsahuje
echo $response->getItemTotalCount();    // vypíše celkový počet položek (na všech stránkách) které daný seznam obsahuje

foreach ($response->getData() as $event) {
    echo $event->getUuid();             // jedinečné ID akce (UUID)
    echo $event->getShortUuid();        // zkrácené jedinečné ID akce - pro použití například v URL
    echo $event->getTitle();            // název akce
    echo $event->getDescription();      // popis akce
    echo $event->getCategory();         // kategorie akce (může být null)
    echo $event->getPhotoUrl();         // URL fotky (loga) akce (může být null)
    echo $event->getOrganizer();        // pořadatel
    echo $event->getDateFrom()
        ->format('j. n. Y H:i');        // datum a čas začátku akce
    echo $event->getDateTo()
        ->format('j. n. Y H:i');        // datum a čas konce akce
    echo $event->isImportant();         // jedná se o důležitý termín?
    echo $event->getPlace();            // Místo konání akce
    echo $event->getRegion();           // Kraj místa konání akce
    echo $event->getWebsiteUrl();       // Webové stránky akce
    echo $event->getPriceForMembers();  // cena standardní pro členy (může být null)
    echo $event->getPriceForMembersDiscounted();  // cena zvýhodněná pro členy (může být null)
    echo $event->getPriceForPublic();   // cena standardní pro veřejnost (může být null)
    echo $event->getPriceForPublicDiscounted();  // cena zvýhodněná pro veřejnost (může být null)
    echo $event->getDatePublishFrom()
        ->format('j. n. Y');            // datum od kdy akci zveřejnit (může být null)
    echo $event->getDatePublishTo()
        ->format('j. n. Y');            // datum do kdy akci zveřejnit (může být null)
    echo $event->getLocalization();     // lokalizace akce (může být null)
    echo $event->isShownInCalendar();   // zobrazit v kalendáriu?
    echo $event->isOpenEvent();         // jedná se o otevřenou akci?
    echo $event->getOpenEventType();    // typ otevřené akce (může být null, pokud se nejedná o otevřenou akci)
    echo $event->isForKids();           // je akce určena pro děti?
    echo $event->isForLeaders();        // je akce určena pro instruktory a vedoucí?
    echo $event->isForPublic();         // je akce určena pro veřejnost?
}
```

#### Načtení detailu jedné akce

Detail akce obsahuje stejné položky, jako jedna akce v seznamu, a k tomu je doplněn o další údaje.

```php
$response = $pionyrCz->request()
    ->event('event-uuid') // je třeba předat UUID nebo Short UUID akce
    ->send();

$event = $response->getData();

echo $event->getTitle();            // název akce
echo $event->getDescription();      // popis akce

// ... a všechny položky, jako ve výpisu seznamů akcí, a navíc:

echo $event->getAgeFrom();          // doporučený věk od (může být null)
echo $event->getAgeTo();            // doporučený věk do (může být null)
echo $event->getExpectedNumberOfParticipants(); // předpokládaný počet účastníků (může být null)
echo $event->getTransportation();   // informace k dopravě (může být null)
echo $event->getAccommodation();    // informace k ubytování (může být null)
echo $event->getFood();             // informace k zajištění stavy (může být null)
echo $event->getRequiredEquipment(); // požadované vybavení

foreach ($event->getPhotos() as $photo) { // pole fotografií akce
    echo $photo->getUrl();
    echo $photo->getTitle();
}

foreach ($event->getFiles() as $file) { // pole souborů akce
    echo $file->getUrl();
    echo $file->getTitle();
    echo $file->isPublic();         // má se soubor zobrazovat veřejně?
}

foreach ($event->getLinks() as $link) { // pole odkazů u akce
    echo $link->getUrl();
    echo $link->getTitle();
}
```

### Jednotky

#### Pionýrské skupiny

Vracejí se pouze skupiny odpovídající právům daného tokenu (podjednotky kraje).

Seznam je stránkovaný po 50 položkách na stránku.

```php
use Pionyr\PionyrCz\Constants\EventCategory;

$response = $pionyrCz->request()
    ->groups()
    ->setPage(3) // volitelné, není-li nastaveno, načte se první strana výpisu
    ->send();

echo $response->getPageCount();         // vypíše celkový počet stránek které daný seznam obsahuje
echo $response->getItemTotalCount();    // vypíše celkový počet položek (na všech stránkách) které daný seznam obsahuje

foreach ($response->getData() as $group) {
    echo $group->getName();             // název jednotky
    echo $group->getWebsiteUrl();       // Webové stránky jednotky
    echo $group->getPhone();            // Kontaktní telefon jednotky
    echo $group->getEmail();            // E-mail jednotky
    echo $group->getLeaderName();       // Jméno vedoucího jednotky
    echo $group->getIco();              // IČO jednotky

    // Adresa sídla PS
    echo $group->getAddressOfficial()->getCity()); // Město
    echo $group->getAddressOfficial()->getStreet()); // Ulice
    echo $group->getAddressOfficial()->getPostcode()); // PSČ

    // Adresa Kde nás najdete
    echo $group->getAddressWhereToFindUs()->getCity()); // Město
    echo $group->getAddressWhereToFindUs()->getStreet()); // Ulice
    echo $group->getAddressWhereToFindUs()->getPostcode()); // PSČ
}
```

#### Oddíly

Zatím neimplementováno.

#### Kluby

Zatím neimplementováno.

### Výjimky a zpracování chyb

Výjimky, ke kterým dojde v API klientovi při zpracování požadavků/odpovědí, implementují rozhraní `Pionyr\PionyrCz\Exception\PionyrCzExceptionInterface`.

Strom výjimek:

| Výjimka                                           | Thrown when                                                   |
|---------------------------------------------------|---------------------------------------------------------------|
| PionyrCzExceptionInterface                        | Společné rozhraní pro všechny výjimky API klienta             |
| └ ClientErrorException                            | Chyba při odeslání požadavku - chybná data apod.              |
| &nbsp;&nbsp;└ AuthorizationException              | Neautentizovaný požadavek, chybný API token                   |
| └ ServerErrorException                            | Chyba při odpovědi serveru - výpadek služby apod.             |
| └ ResponseDecodingException                       | Odpověd obsahuje chybná data                                  |

V případě použití vlastní instance HTTP klienta (tedy pokud voláme `$pionyrCz->setHttpClient()`) je třeba zajistit,
aby byl nastaven tak, že v případě HTTP chyb (400 a 500) nevyhazuje sám výjimky. Například při použití Guzzle 6 klienta
to znamená že nastavení [`http_errors`](http://docs.guzzlephp.org/en/stable/request-options.html#http-errors)
musí být `false`.

## Changelog - seznam změn
Pro seznam změn viz soubor [CHANGELOG.md](CHANGELOG.md). Dodržujeme [sémantické verzování](http://semver.org/).

## Licence
Knihovna je zveřejněna jako open-source pod licencí [MIT](LICENCE.md).
