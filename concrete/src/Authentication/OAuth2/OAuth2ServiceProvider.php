<?php

namespace Concrete\Core\Authentication\OAuth2;

use Concrete\Core\Application\Application;
use Concrete\Core\Authentication\OAuth2\Storage\Concrete;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Routing\Router;
use Concrete\Core\Database\Connection\Connection;
class OAuth2ServiceProvider extends Provider
{

    public function register()
    {
        if ($this->app->isInstalled()) {
            $this->db = $this->app->make(Connection::class);
        }
        $router = $this->app->make(Router::class);

        $router->buildGroup()
            ->setPrefix('/oauth/2.0')
            ->setNamespace('Concrete\Controller')
            ->routes(function(Router $groupRouter) {
                $groupRouter->all('token', 'Oauth2::token');
            });

        $this->app->singleton('oauth2/server', function() {
            $storage = new Concrete($this->db->getWrappedConnection(), [
                'client_table' => 'OAuthServerClients',
                'access_token_table' => 'OAuthServerAccessTokens',
                'refresh_token_table' => 'OAuthServerRefreshTokens',
                'code_table' => 'OAuthServerAuthorizationCodes',
                'user_table' => 'OAuthServerUsers',
                'jwt_table'  => 'OAuthServerJwt',
                'scope_table'  => 'OAuthServerScopes',
                'public_key_table'  => 'OAuthServerPublicKeys',
                'jti_table' => 'OAuthServerJti'
            ]);
            $server = new \OAuth2\Server($storage);
            //$server->addGrantType(new \OAuth2\GrantType\UserCredentials($storage));
            $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));
            return $server;
        });

        $this->app->singleton('oauth2/request', function() {
            $request = \OAuth2\Request::createFromGlobals();
            return $request;
        });
    }
}