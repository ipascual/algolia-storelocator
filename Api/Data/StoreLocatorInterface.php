<?php

namespace BigHippo\StoreLocator\Api\Data;

/**
 * Interface StoreLocatorInterface
 *
 * @api
 * @package BigHippo\StoreLocator\Api\Data
 */
interface StoreLocatorInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getStreet();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @return string
     */
    public function getPostcode();

    /**
     * @return string
     */
    public function getCountry();
}
