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

use Zephia\MercadoLibre\Entity\User;

/**
 * Class MercadoLibreUser
 *
 * @package Zephia\LaravelMercadoLibre\Socialite
 * @author  Mauro Moreno<moreno.mauro.emanuel@gmail.com>
 */
class MercadoLibreUser extends User
{
    /**
     * @var string
     */
    protected $expired_in = '';

    /**
     * @var string
     */
    public $refresh_token = '';

    /**
     * @var int
     */
    public $expires_at;

    /**
     * Set refresh_token;
     *
     * @param $refresh_token
     *
     * @return $this
     */
    public function setRefreshToken($refresh_token)
    {
        $this->refresh_token = $refresh_token;
        return $this;
    }

    /**
     * Set expires_in
     *
     * @param string $expires_in
     *
     * @return $this
     */
    public function setExpiresIn($expires_in)
    {
        $this->expired_in = $expires_in;
        $this->expires_at = time() + (int)$expires_in;
        return $this;
    }
}
