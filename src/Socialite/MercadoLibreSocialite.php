<?php
/*
 * This file is part of the Laravel Mercado Libre API client package.
 *
 * (c) Zephia <info@zephia.com.ar>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zephia\LaravelMercadoLibre\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Zephia\MercadoLibre\Client\MercadoLibreClient;

/**
 * Class MercadoLibreSocialite
 *
 * @package Zephia\LaravelMercadoLibre\Socialite
 * @author  Mauro Moreno<moreno.mauro.emanuel@gmail.com>
 */
class MercadoLibreSocialite extends AbstractProvider
    implements ProviderInterface
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

    protected $access_token;

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            MercadoLibreClient::AUTH_URI,
            $state
        );
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return MercadoLibreClient::BASE_URI . MercadoLibreClient::OAUTH_URI;
    }

    /**
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $user = $this->getHttpClient()->userShowMe($token);
        return json_decode(json_encode($user), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new MercadoLibreUser)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['nickname'],
            'name' => trim($user['first_name'] . ' ' . $user['last_name']),
            'email' => $user['email'],
        ]);
    }

    /**
     * Get Access Token
     *
     * @param $code
     *
     * @return mixed
     */
    public function getAccessToken($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'body' : 'form_params';
        $response = $this->getHttpClient()->getGuzzleClient()
            ->post($this->getTokenUrl(), [
                'headers' => ['Accept' => 'application/json'],
                $postKey => $this->getTokenFields($code),
            ]);

        $data = $this->generateData($response->getBody());

        $this->access_token = $data['access_token'];
        $this->refresh_token = $data['refresh_token'];
        $this->expires_in = $data['expires_in'];

        return $this->access_token;
    }

    /**
     * Generate Data
     *
     * @param $body
     *
     * @return $this
     */
    protected function generateData($body)
    {
        $this->parsed_response = json_decode($body, true);
        return $this;
    }

    /**
     * Get HTTP application
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getHttpClient()
    {
        return app('meli_api');
    }
}
