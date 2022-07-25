<?php
class DPWC_DataPlansRegion extends DPWC_DataPlansApiResource
{
    const ENDPOINT = 'regions';

    /**
     * Retrieves a region.
     *
     * @param  string $token
     *
     * @return DataPlansRegion
     */
    public static function retrieve()
    {
        return parent::doRetrieve(get_class(), self::getUrl());
    }

    /**
     * Reload a region request
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
