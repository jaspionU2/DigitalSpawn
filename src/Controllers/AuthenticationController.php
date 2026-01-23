<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;

class AuthenticationController extends Controller
{
    public function doRegister(ServerRequestInterface $request)
    {
        $validator = v::key(
            'nome',
            v::stringType()->setTemplate('{{name}} deve ser um texto')
                ->notEmpty()->setTemplate('{{name}} é obrigatório')
                ->length(3, 100)->setTemplate('{{name}} deve ter entre 3 e 100 caracteres')
                ->setName('Nome')
            )
            ->key(
                'sobrenome',
                v::stringType()->setTemplate('{{name}} deve ser um texto')
                    ->notEmpty()->setTemplate('{{name}} é obrigatório')
                    ->length(3, 100)->setTemplate('{{name}} deve ter entre 3 e 100 caracteres')
                    ->setName('Sobrenome')
            )   
            ->key(
                'email',
                v::stringType()->setTemplate('Email deve ser um texto')
                    ->notEmpty()->setTemplate('Email é obrigatório')
                    ->email()->setTemplate('Email deve ser válido')
                    ->setName('Email')
            )
            ->key(
                'password',
                v::stringType()->setTemplate('{name} deve ser um texto')
                    ->notEmpty()->setTemplate('{name} é obrigatória')
                    ->length(8, null)->setTemplate('{name} deve ter no mínimo 8 caracteres')
                    ->regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/')
                    ->setTemplate('{name} deve conter maiúscula, minúscula, número e caractere especial')
                    ->setName('Senha')
            );
        dd($request->getParsedBody());
        // Usar a validação:
        // $dados = $request->getParsedBody();
        // try {
        //     $validator->assert($dados);
        // } catch (\Exception $e) {
        //     $this->erro = $e->getMessage();
        // }
    }
}
