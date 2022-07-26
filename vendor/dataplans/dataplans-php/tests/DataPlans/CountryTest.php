<?php
require_once dirname(__FILE__).'/TestConfig.php';

class DPWC_CountryTest extends TestConfig
{
    /**
     * @test
     * DataPlansCountry class must be contain some method below.
     */
    public function method_exists()
    {
        $this->assertTrue(method_exists('DPWC_DataPlansCountry', 'retrieve'), 'Method retrieve not exists');
        $this->assertTrue(method_exists('DPWC_DataPlansCountry', 'reload'), 'Method reload not exists');
        $this->assertTrue(method_exists('DPWC_DataPlansCountry', 'getUrl'), 'Method getUrl not exists');
    }

    /**
     * @test
     * Assert that a country object is returned after a successful retrieve.
     */
    public function retrieve_object()
    {
        $object = DPWC_DataPlansCountry::retrieve();

        $this->assertInstanceOf('DPWC_DataPlansCountry', $object, 'Retrieve data is invalid');
        if (!empty($object)) {
            $this->assertArrayHasKey('countryCode', $object[0], 'Key countryCode not exist');
        }
    }

    /**
     * @test
     * Assert that a country object is returned after a successful reload.
     */
    public function reload()
    {
        $object = DPWC_DataPlansCountry::retrieve();
        $object->reload();

        $this->assertInstanceOf('DPWC_DataPlansCountry', $object, 'Retrieve data is invalid');
        if (!empty($object)) {
            $this->assertArrayHasKey('countryCode', $object[0], 'Key countryCode not exist');
        }
    }

    /**
     * @test
     * Assert that a country object with slug is returned after a successful retrieve.
     */
    public function retrieve_object_by_slug()
    {
        $slug = 'th';
        $object = DPWC_DataPlansCountry::retrieve($slug);

        $this->assertInstanceOf('DPWC_DataPlansCountry', $object, 'Retrieve data is invalid');
        if (!empty($object)) {
            $this->assertArrayHasKey('slug', $object[0], 'Key slug not exist');
            $this->assertArrayHasKey('retailPrice', $object[0], 'Key retailPrice not exist');
        }
    }
}
