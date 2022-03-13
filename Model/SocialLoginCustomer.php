<?php


namespace ElQuchiri\SocialLogin\Model;


class SocialLoginCustomer extends \Magento\Framework\Model\AbstractModel implements \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer
{

    protected function _construct() {
        $this->_init(\ElQuchiri\SocialLogin\Model\ResourceModel\SocialLoginCustomer::class);
    }

    public function getSocialId()
    {
        return $this->getData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_ID);
    }

    public function setSocialId($socialId)
    {
        $this->setData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_ID, $socialId);

        return $this;
    }

    public function getCustomerId()
    {
        return $this->getData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        $this->setData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::CUSTOMER_ID, $customerId);

        return $this;
    }

    public function getSocialType()
    {
        return $this->getData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_TYPE);
    }

    public function setSocialType($socialType)
    {
        $this->setData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_TYPE, $socialType);

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->getData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        $this->setData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::CREATED_AT, $createdAt);

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->getData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->setData(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer::UPDATED_AT, $updatedAt);

        return $this;
    }
}