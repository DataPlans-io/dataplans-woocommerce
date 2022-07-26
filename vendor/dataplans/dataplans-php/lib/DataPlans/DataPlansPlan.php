<?php
class DPWC_DataPlansPlan extends DPWC_DataPlansApiResource
{
    const ENDPOINT = 'plans';

    /**
     * Retrieves a plan.
     *
     * @param  string $slug
     * @param  string $token
     *
     * @return DataPlansPlan
     */
    public static function retrieve($slug = '')
    {
        return parent::doRetrieve(get_class(), self::getUrl($slug));
    }

    /**
     * Reload a plan request
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
        $endpoint = empty($slug) ? self::ENDPOINT : 'plan/'.$slug;
        return parent::getApiUrl($endpoint);
    }
}
