<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class CookieManager
{
    // Tempos de expiração em minutos
    public const ACCESS_TOKEN_EXPIRY = 10; // 10 minutos
    public const REFRESH_TOKEN_EXPIRY = 1440; // 1 dia (24 horas)

    /**
     * Retorna o prefixo baseado no APP_NAME (mesmo padrão do Laravel para session/cache)
     */
    private static function prefix(): string
    {
        return Str::slug(config('app.name', 'laravel'), '_');
    }

    /**
     * Retorna o nome do cookie de access token com prefixo
     */
    public static function accessTokenCookieName(): string
    {
        return self::prefix() . '_access_token';
    }

    /**
     * Retorna o nome do cookie de refresh token com prefixo
     */
    public static function refreshTokenCookieName(): string
    {
        return self::prefix() . '_refresh_token';
    }

    /**
     * Cria um cookie de token com as configurações padrão de segurança
     *
     * @param string $name Nome do cookie
     * @param string $token Valor do token
     * @param int $minutes Tempo de expiração em minutos
     * @return SymfonyCookie
     */
    public function createTokenCookie(string $name, string $token, int $minutes): SymfonyCookie
    {
        return Cookie::make(
            $name,
            $token,
            $minutes,
            config('session.path', '/'),
            config('session.domain'),
            config('session.secure', true),
            config('session.http_only', true),
            false,    // raw
            config('session.same_site', 'lax')
        );
    }

    /**
     * Cria um cookie de access token
     *
     * @param string $token
     * @return SymfonyCookie
     */
    public function createAccessTokenCookie(string $token): SymfonyCookie
    {
        return $this->createTokenCookie(
            self::accessTokenCookieName(),
            $token,
            self::ACCESS_TOKEN_EXPIRY
        );
    }

    /**
     * Cria um cookie de refresh token
     *
     * @param string $token
     * @return SymfonyCookie
     */
    public function createRefreshTokenCookie(string $token): SymfonyCookie
    {
        return $this->createTokenCookie(
            self::refreshTokenCookieName(),
            $token,
            self::REFRESH_TOKEN_EXPIRY
        );
    }

    /**
     * Remove o cookie de access token
     *
     * @return SymfonyCookie
     */
    public function forgetAccessTokenCookie(): SymfonyCookie
    {
        // Criar cookie com valor vazio e tempo expirado para garantir remoção
        return Cookie::make(
            self::accessTokenCookieName(),
            '',
            -1,       // tempo negativo para expirar imediatamente
            config('session.path', '/'),
            config('session.domain'),
            config('session.secure', true),
            config('session.http_only', true),
            false,    // raw
            config('session.same_site', 'lax')
        );
    }

    /**
     * Remove o cookie de refresh token
     *
     * @return SymfonyCookie
     */
    public function forgetRefreshTokenCookie(): SymfonyCookie
    {
        // Criar cookie com valor vazio e tempo expirado para garantir remoção
        return Cookie::make(
            self::refreshTokenCookieName(),
            '',
            -1,       // tempo negativo para expirar imediatamente
            config('session.path', '/'),
            config('session.domain'),
            config('session.secure', true),
            config('session.http_only', true),
            false,    // raw
            config('session.same_site', 'lax')
        );
    }

    /**
     * Renova os cookies com os mesmos tokens mas com novos tempos de expiração
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @return array Array com os cookies renovados
     */
    public function renewTokenCookies(string $accessToken, string $refreshToken): array
    {
        return [
            $this->createAccessTokenCookie($accessToken),
            $this->createRefreshTokenCookie($refreshToken)
        ];
    }

    /**
     * Obtém o access token do request
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function getAccessTokenFromRequest($request): ?string
    {
        return $request->cookie(self::accessTokenCookieName());
    }

    /**
     * Obtém o refresh token do request
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function getRefreshTokenFromRequest($request): ?string
    {
        return $request->cookie(self::refreshTokenCookieName());
    }
}
