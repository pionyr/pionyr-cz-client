<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Exception;

class ServerErrorException extends \Http\Client\Common\Exception\ClientErrorException implements PionyrCzExceptionInterface
{
}
