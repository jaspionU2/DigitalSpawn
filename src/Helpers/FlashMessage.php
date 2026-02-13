<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Support\SessionManager;

/**
 * Classe responsável por gerenciar mensagens flash na sessão.
 * Permite definir e recuperar mensagens temporárias, geralmente usadas para feedback ao usuário.
 */
class FlashMessage
{
    /**
     * Define uma mensagem flash na sessão.
     *
     * @param string $index Índice da mensagem.
     * @param mixed $value Valor da mensagem.
     * @param SessionManager $session Instância de gerenciamento de sessão.
     * @return void
     */
    public static function set(
        string $index,
        mixed $value,
        SessionManager $session,
    ): void {
        if (!$session->sessionStatus()) {
            $session->start();
        }

        $session->__set($index, $value);
    }

    /**
     * Recupera e remove uma mensagem flash da sessão.
     *
     * @param string $index Índice da mensagem.
     * @param SessionManager $session Instância de gerenciamento de sessão.
     * @return mixed Valor da mensagem.
     */
    public static function get(
        string $index,
        SessionManager $session,
    ): mixed {
        if (!$session->sessionStatus()) {
            $session->start();
        }

        $message = $session->hasKey($index) ? $session->__get($index) : '';
        
        $session->unset($index);

        return $message;
    }
}