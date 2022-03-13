<?php


namespace ElQuchiri\SocialLogin\Api;


interface SocialNetworkCustomerRepositoryInterface
{

    /**
     * @param $id
     * @return \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer
     */
    public function getById($id);

    /**
     * @param \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer
     * @return mixed
     */
    public function save(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer);

    /**
     * @param \ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer
     * @return mixed
     */
    public function delete(\ElQuchiri\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer);

}