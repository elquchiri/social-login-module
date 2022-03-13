<?php


namespace ElQuchiri\SocialLogin\Model\Repository;


class SocialLoginCustomerRepository implements \ElQuchiri\SocialLogin\Api\SocialNetworkCustomerRepositoryInterface
{


    private $collectionFactory;
    private $socialLoginCustomerFactory;

    public function __construct(
        \ElQuchiri\SocialLogin\Model\ResourceModel\SocialLoginCustomer\CollectionFactory $collectionFactory,
        \ElQuchiri\SocialLogin\Model\SocialLoginCustomerFactory $socialLoginCustomerFactory
    )
    {

        $this->collectionFactory = $collectionFactory;
        $this->socialLoginCustomerFactory = $socialLoginCustomerFactory;
    }

    public function getById($id)
    {
        $socialLoginCustomer = $this->socialLoginCustomerFactory->create();
        $socialLoginCustomer->getResource()->load($socialLoginCustomer, $id);

        if(!$socialLoginCustomer->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__("Unable to find Social Login Customer with ID %1", $id));
        }

        return $socialLoginCustomer;
    }

    public function socialNetworkCustomerExists($userProfile, $type)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('social_id', $userProfile['identifier'])
            ->addFieldToFilter('social_type', $type);

        return $collection->count();
    }

    public function save(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer)
    {
        $socialNetworkCustomer->getResource()->save($socialNetworkCustomer);

        return $socialNetworkCustomer;
    }

    public function delete(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer)
    {
        $socialNetworkCustomer->getResource()->delete($socialNetworkCustomer);

        return $socialNetworkCustomer;
    }
}