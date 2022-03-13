<?php


namespace ElQuchiri\SocialLogin\Api\Data;


interface SocialNetworkCustomer
{
    const TABLE_NAME = 'elq_sociallogin_customer';

    const ENTITY_ID = 'entity_id';
    const SOCIAL_ID = 'social_id';
    const CUSTOMER_ID = 'customer_id';
    const SOCIAL_TYPE = 'social_type';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @return mixed
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return mixed
     */
    public function setEntityId($entityId);

    /**
     * @return mixed
     */
    public function getSocialId();

    /**
     * @param $socialId
     * @return mixed
     */
    public function setSocialId($socialId);

    /**
     * @return mixed
     */
    public function getCustomerId();

    /**
     * @param $customerId
     * @return mixed
     */
    public function setCustomerId($customerId);

    /**
     * @return mixed
     */
    public function getSocialType();

    /**
     * @param $socialType
     * @return mixed
     */
    public function setSocialType($socialType);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt);

}