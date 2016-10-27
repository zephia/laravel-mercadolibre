<?php

namespace Zephia\LaravelMercadoLibre\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use GuzzleHttp\ClientInterface;

class MercadoLibreSocialite extends AbstractProvider implements ProviderInterface
{
    /**
     * @var string Refresh Token
     */
    protected $refresh_token;

    /**
     * @var string Access Token Expires in
     */
    protected $expires_in;

    /**
     * @var string With Access Token, Refresh Token and Expires In.
     */
    protected $parsed_response;

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(MeliManager::$AUTH_URL, $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        $token_url = MeliManager::$API_ROOT_URL . MeliManager::$OAUTH_URL;
        return $token_url;
    }

    /**
     * @param string $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'client_id' => $this->clientId, 'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code, 'redirect_uri' => $this->redirectUrl,
        ];
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(MeliManager::$API_ROOT_URL . '/users/me?' . http_build_query(['access_token' => $token]));
        $output = json_decode($response->getBody(), true);
        return $output;
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new MeliUser)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['nickname'],
            'name' => trim($user['first_name'] . ' ' . $user['last_name']),
            'email' => $user['email'],
        ]);
    }

    public function getAccessToken($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            $postKey => $this->getTokenFields($code),
        ]);
        return $this->parseResponse($response->getBody())->parsedAccessToken();
    }

    protected function parseResponse($body)
    {
        $this->parsed_response = json_decode($body, true);
        return $this;
    }

    protected function parsedAccessToken()
    {
        return $this->parsed_response['access_token'];
    }

    protected function getRefreshToken()
    {
        return $this->parsed_response['refresh_token'];
    }

    protected function getExpiresIn()
    {
        return $this->parsed_response['expires_in'];
    }

    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }
        $user = $this->mapUserToObject($this->getUserByToken(
            $token = $this->getAccessToken($this->getCode())
        ));
        return $user->setToken($token)->setRefreshToken($this->getRefreshToken())->setExpiresIn($this->getExpiresIn());;
    }
}