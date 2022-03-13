<?php


namespace ElQuchiri\SocialLogin\Block;


use Magento\Framework\View\Element\Template;

class SocialBlock extends \Magento\Framework\View\Element\Template
{

    private \ElQuchiri\SocialLogin\Helper\Social $socialHelper;

    public function __construct(
        Template\Context $context,
        \ElQuchiri\SocialLogin\Helper\Social $socialHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->socialHelper = $socialHelper;
    }

    public function IsSocialLoginActive() {
        return $this->socialHelper->isSocialNetworkEnable();
    }

    public function getEnabledSocialNetworks() {
        return $this->socialHelper->getActiveSocialNetworksList();
    }
}