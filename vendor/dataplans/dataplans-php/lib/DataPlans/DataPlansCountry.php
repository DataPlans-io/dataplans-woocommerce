<?php
class DPWC_DataPlansCountry extends DPWC_DataPlansApiResource
{
    const ENDPOINT = 'countries';

    /**
     * Retrieves a country.
     *
     * @param  string $slug
     * @param  string $token
     *
     * @return DataPlansCountry
     */
    public static function retrieve($slug = '')
    {
        return parent::doRetrieve(get_class(), self::getUrl($slug));
    }

    /**
     * Reload a country request
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
     * @param  string $slug
     * @return string
     */
    public static function getUrl($slug = '')
    {
        $suffix = self::ENDPOINT;
        if (!empty($slug)) {
            // Rewrite $suffic with DataPlansPlan
            $suffix = DataPlansPlan::ENDPOINT .'/'. self::ENDPOINT .'/'. $slug;
        }

        return parent::getApiUrl($suffix);
    }
}
