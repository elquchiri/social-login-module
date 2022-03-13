<?php


namespace ElQuchiri\SocialLogin\Model\ResourceModel;


class SocialLoginCustomer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init(
            \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::TABLE_NAME,
            \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::ENTITY_ID
        );

    }
}