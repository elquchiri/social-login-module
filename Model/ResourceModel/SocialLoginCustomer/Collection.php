<?php


namespace ElQuchiri\SocialLogin\Model\ResourceModel\SocialLoginCustomer;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \ElQuchiri\SocialLogin\Model\SocialLoginCustomer::class,
            \ElQuchiri\SocialLogin\Model\ResourceModel\SocialLoginCustomer::class
        );
    }

}