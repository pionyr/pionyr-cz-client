# PHP API klient pro Pionyr.cz

Pro využití například na webech krajských organizací poskytuje web pionyr.cz API, skrze které se dají načítat články,
akce, termíny a seznamy jednotek (pionýrských skupin, oddílů, klubů).

Tato knihovna poskytuje nástroje pro snadnou práci s tímto API. Knihovna vyžaduje PHP verze 7.1 a novější.

## Instalace

Instalace knihovny se provede skrze [Composer](https://getcomposer.org/). Instalaci je možno spustit tímto příkazem:

```sh
$ composer require pionyr/pionyr-cz-client php-http/guzzle6-adapter
```

Čímž se nainstaluje API klient, který bude používat jako transportní HTTP knihovnu Guzzle 6.

Knihovna používá abstrakci skrze [HTTPlug](https://github.com/php-http/httplug), takže není svázána s konkrétní
HTTP knihovnou, a je možné dle potřeby použít i jinou HTTP knihovnu - viz
[seznam podporovaných HTTP knihoven](http://docs.php-http.org/en/latest/clients.html).

Pokud bychom chtěli použít jako transporní knihovnu místo Guzzle 6 například cURL, nainstalujeme API klienta tímto příkazem:

```sh
$ composer require pionyr/pionyr-cz-client php-http/curl-client guzzlehttp/psr7
```

## Použití

Pro použití potřebujeme znát *API token*, který přidělí Ústředí Pionýra. Tokenem je zároveň identifikován uživatel
a je tím stanoven i rozsah přístupů (např. tokenem vystaveným pro jeden kraj nemůžeme přistupovat k seznamu akcí jiného kraje apod.).

Token se také váže na doménu (instanci) API - v případě použití testovací instance API je třeba použít i token vystavený právě pro
tuto instanci (token pro "produkční" instanci zde nebude fungovat).

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

### Články

#### Načtení seznamu článků

Vracejí se pouze publikované články odpovídající právům daného tokenu, které se mají momentálně zobrazovat.

Seznam je stránkovaný po 30 položkách na stránku a je možné jej filtrovat dle kategorie článku.

```php
use Pionyr\PionyrCz\Constants\ArticleCategory;

$response = $pionyrCz->request()
    ->articles()
    ->setPage(3) // volitelné, není-li nastaveno, načte se první strana výpisu
    ->setCategory(ArticleCategory::VZDELAVANI()) // volitelné filtrování dle kategorie, není-li nastaveno, načtou se články ve všech kategoriích
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
    echo $article->getAuthorName();     // jméno a příjmení autora článku
    echo $article->getPerex();          // text perexu - krátký úvod článku
    echo $article->getPerexPhotoUrl();  // URL úvodní fotky k článku (může být null)
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
echo $article->getDatePublished()
    ->format('j. n. Y H:i:s');  // datum publikování
echo $article->getCategory();       // kategorie článku
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
Zatím neimplementováno.

### Jednotky
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

V případě použití vlastního HTTP klienta (tedy pokud voláme `$matej->setHttpClient()`) je třeba zajistit, aby byl nastaven tak,
že v případě HTTP chyb (400 a 500) nevyhazuje sám výjimky. Například při použití Guzzle 6 klienta to znamená že nastavení
[`http_errors`](http://docs.guzzlephp.org/en/stable/request-options.html#http-errors) musí být `false`.

## Changelog - seznam změn
Pro seznam změn viz soubor [CHANGELOG.md](CHANGELOG.md). Dodržujeme [sémantické verzování](http://semver.org/).

## Licence
Knihovna je zveřejněna jako open-source pod licencí [MIT](LICENCE.md).
