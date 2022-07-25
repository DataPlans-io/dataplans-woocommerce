<?php
class DPWC_DataPlansBalance extends DPWC_DataPlansApiResource
{
    const ENDPOINT = 'accountBalance';

    /**
     * Retrieves an account balance.
     *
     * @param  string $token
     *
     * @return DataPlansBalance
     */
    public static function retrieve()
    {
        return parent::doRetrieve(get_class(), self::getUrl());
    }

    /**
     * Reload an account balance request
     *
     * @see DPWC_DataPlansApiResource::doReload()
     */
    public function reload()
    {
        parent::doReload(self::getUrl());
    }

    /**
     * Returns endpoint url
     *
     * @return string
     */
    public static function getUrl()
    {
        return parent::getApiUrl(self::ENDPOINT);
    }
}
