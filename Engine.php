<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\authclient;
use \yii\authclient\OAuth2;
use \yii\authclient\OAuthToken;
/**
 * 
 */
class Engine extends OAuth2
{

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://sso.datasciencegroup.pl/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://sso.datasciencegroup.pl/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://sso.datasciencegroup.pl/';

    // public logoutUrl = '' todo

    public $baseURLHH  = 'http://iron.engine.kdm.wcss.pl:40080';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->scope = 'openid';
        $this->clientId = 'humhub';
        $this->clientSecret = 'humhub1';
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $attributes = $this->api('user', 'GET');
        return $attributes;
    }

    public function getBasicAuthData()
    {
        return 'Authorization: Basic ' . base64_encode($this->clientId . ":" . $this->clientSecret);

    }

    public function defaultReturnUrl()
    {
       return $this->baseURLHH . '/user/auth/external?authclient=engine'; 
    }


    public function fetchAccessToken($authCode, array $params = [])
    {
        $defaultParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $authCode,
            'redirect_uri' => $this->defaultReturnUrl(),
            'grant_type' => 'authorization_code',
        ];
        unset($params['redirect_uri']);
        $auth_header = array($this->getBasicAuthData());
        $response = $this->sendRequest('POST', $this->tokenUrl, array_merge($defaultParams, $params), $auth_header);
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }

    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $params['access_token'] = $accessToken->getToken();
        $auth_header = array('Authorization: Bearer '.$params['access_token']);
        return $this->sendRequest($method, $url, $params, array_merge($headers, $auth_header));
    }


    public function refreshAccessToken(OAuthToken $token)
    {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token'
        ];
        $params = array_merge($token->getParams(), $params);
        $auth_header = array($this->getBasicAuthData());
        $response = $this->sendRequest('POST', $this->tokenUrl, $params, $auth_header);
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'cssIcon' => 'fa fa-sign-in',
            'buttonBackgroundColor' => '#4078C0',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'username' => function ($attributes) {
                return $attributes['principal']['username'];
            },
            'firstname' => function ($attributes) {
                return $attributes['principal']['name'];
            },
            'id' => function ($attributes) {
                return $attributes['principal']['id'];
            },
            'lastname' => function ($attributes) {
                return $attributes['principal']['surname'];
            },
            'email' => function ($attributes) {
                return $attributes['principal']['email'];
            },
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'engine';
    }
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Engine';
    }
}
