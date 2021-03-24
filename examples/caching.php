<?php declare(strict_types=1);
/**
 * Pro nastavení cachování odpovědí API je třeba nainstalovat cache-plugin:
 * `composer require php-http/cache-plugin`
 * Dále je třeba cachovací adaptér (implementace CacheItemPoolInterface z PSR-6) - například FilesystemAdapter ze Symfony/Cache
 * `composer require symfony/cache`
 *
 * Pak už stačí jenom zabalit instanci `HttpClientDiscovery` do  instance`PluginClient` - viz příklad níže.
 * Pro správné fungování cachování je také nastavit ignorování cache hlaviček serveru.
 *
 * @see http://docs.php-http.org/en/latest/plugins/cache.html
 */
use Http\Client\Common\Plugin\CachePlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Pionyr\PionyrCz\PionyrCz;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require __DIR__ . '/../vendor/autoload.php';

$pionyrCz = new PionyrCz('...');

$cachePlugin = new CachePlugin(
    new FilesystemAdapter('', 0, __DIR__ . '/cache/'), // cache bude ukládána do složky cache/
    Psr17FactoryDiscovery::findStreamFactory(),
    [
        'respect_response_cache_directives' => [], // pro ignorování no-cache hlaviček v odpovědích API
        'default_ttl' => null, // pro ignorování nesprávných údajů v hlavičkách odpovědí API
        'cache_lifetime' => 3600, // lifetime cache v sekundách - může být i více, ale pak je vhodné si udělat invalidaci (viz příklad níže)
    ]
);

$httpClient = new PluginClient(Psr18ClientDiscovery::find(), [$cachePlugin]);
$pionyrCz->setHttpClient($httpClient);

// Všechny request provedené přes $pionyrCz budou nyní používat cache s platností 1 hodina
$response = $pionyrCz
    ->request()
    ->articles()
    ->send();

// Invalidace cache - smaže všechna nacachovaná data
$cacheAdapter = new FilesystemAdapter('', 0, __DIR__ . '/cache/');
$cacheAdapter->clear();
