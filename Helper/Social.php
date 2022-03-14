<?php
/*
 * MIT License
 *
 * Copyright (c) 2022 Mohamed EL QUCHIRI
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace ElQuchiri\SocialLogin\Helper;


use Magento\Framework\App\Helper\Context;

class Social extends \Magento\Framework\App\Helper\AbstractHelper
{

    const CONFIG_ROOT_PATH = 'elq_social_network';
    const CONFIG_ADAPTERS = 'adapters';
    const CONFIG_APP_ID = 'app_id';
    const CONFIG_APP_SECRET = 'app_secret';

    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    public function isSocialNetworkEnable() {
        return $this->scopeConfig->getValue(self::CONFIG_ROOT_PATH . '/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getAdapterConfigValue($adapterId, $key) {
        return $this->scopeConfig->getValue(self::CONFIG_ROOT_PATH . '/' . self::CONFIG_ADAPTERS . '/' . $adapterId . '/' . $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isAdapterEnable($adapterId) {
        return $this->getAdapterConfigValue($adapterId, 'enable');
    }

    public function getSocialNetworksList() {
        return [
            'facebook' => 'Facebook',
            'google' => 'Google',
            'instagram' => 'Instagram',
            'twitter' => 'Twitter',
            'amazon' => 'Amazon',
            'yahoo' => 'Yahoo',
            'linkedin' => 'LinkedIn',
            'github' => 'GitHub'
        ];
    }

    public function getActiveSocialNetworksList() {
        $enabledSocialNetworks = [];

        foreach($this->getSocialNetworksList() as $key => $socialNetwork) {
            if($this->isAdapterEnable($key, 'enable')) {
                $enabledSocialNetworks[$key] = $socialNetwork;
            }
        }

        return $enabledSocialNetworks;
    }

    public function getSocialNetwork($adapterId) {
        return $this->getSocialNetworksList()[$adapterId];
    }

    public function getHybridauthConfig($adapterId) {
        $providers = [];

        foreach($this->getActiveSocialNetworksList() as $socialNetwork) {
            $providers[$socialNetwork] = [
                'enabled' => true,
                'keys' => [
                    'id' => $this->getAdapterConfigValue($adapterId, self::CONFIG_APP_ID),
                    'secret' => $this->getAdapterConfigValue($adapterId, self::CONFIG_APP_SECRET)
                ]
            ];
        }

        return [
            'callback' => $this->getSocialRedirectUrl($adapterId),
            'providers' => $providers
        ];
    }

    public function getSocialRedirectUrl($adapterId) {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $redirectUrl = $baseUrl . 'elquchiri_redirect/social/login/provider/' . $adapterId;

        return $redirectUrl;
    }

}