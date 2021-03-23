<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Exception;

use Http\Client\Exception\HttpException;

class ServerErrorException extends HttpException implements PionyrCzExceptionInterface
{
}
