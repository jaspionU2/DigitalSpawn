<?php declare(strict_types=1);

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

class AuthenticationController extends Controller
{
    public function doRegister(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getQueryParams();
    }
}
