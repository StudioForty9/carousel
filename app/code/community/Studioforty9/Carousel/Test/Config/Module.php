<?php
/**
 * Studioforty9 Carousel
 *
 * @category  Studioforty9
 * @package   Studioforty9_Carousel
 * @author    StudioForty9 <info@studioforty9.com>
 * @copyright 2016 StudioForty9 (http://www.studioforty9.com)
 * @license   https://github.com/studioforty9/carousel/blob/master/LICENCE BSD
 * @version   1.0.0
 * @link      https://github.com/studioforty9/carousel
 */

/**
 * Studioforty9_Carousel_Test_Config_Module
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Test
 */
class Studioforty9_Carousel_Test_Config_Module extends EcomDev_PHPUnit_Test_Case_Config
{
    public function test_module_is_in_correct_code_pool()
    {
        $this->assertModuleIsActive();
        $this->assertModuleCodePool('community');
    }

    public function test_module_version_is_correct()
    {
        $this->assertModuleVersion('1.0.0');
    }

    public function test_blocks_are_configured()
    {
        $this->assertBlockAlias(
            'studioforty9_carousel/slider_list', 'Studioforty9_Carousel_Block_Slider_List'
        );
    }

    public function test_models_are_configured()
    {
        $this->assertModelAlias('studioforty9_carousel/slider', 'Studioforty9_Carousel_Model_Slider');
        $this->assertModelAlias('studioforty9_carousel/slide', 'Studioforty9_Carousel_Model_Slide');
    }

    public function test_helpers_are_configured()
    {
        $this->assertHelperAlias('studioforty9_carousel/data', 'Studioforty9_Carousel_Helper_Data');
    }

    public function test_tables_are_configured()
    {
        $this->assertTableAlias('studioforty9_carousel/carousel_slider', 'studioforty9_carousel_slider');
        $this->assertTableAlias('studioforty9_carousel/carousel_slider_store', 'studioforty9_carousel_slider_store');
        $this->assertTableAlias('studioforty9_carousel/carousel_slide', 'studioforty9_carousel_slide');
        $this->assertTableAlias('studioforty9_carousel/carousel_slide_store', 'studioforty9_carousel_slide_store');
        $this->assertTableAlias('studioforty9_carousel/carousel_slide_slider', 'studioforty9_carousel_slide_slider');
    }

    /*public function test_access_granted_for_config_acl()
    {
        $this->assertConfigNodeValue(
            'config/adminhtml/acl/resources/admin/children/system/children/config/children/studioforty9/children/studioforty9_carousel/title',
            'StudioForty9 Carousel Configuration'
        );
    }*/

    public function test_layout_updates_are_correct()
    {
        $this->assertLayoutFileDefined('adminhtml', 'studioforty9_carousel.xml', 'studioforty9_carousel');
        $this->assertLayoutFileExists('adminhtml', 'studioforty9_carousel.xml', 'default', 'default');
        $this->assertLayoutFileDefined('frontend', 'studioforty9_carousel.xml', 'studioforty9_carousel');
        $this->assertLayoutFileExists('frontend', 'studioforty9_carousel.xml', 'default', 'base');
    }

    public function test_translate_nodes_are_correct()
    {
        $this->assertConfigNodeValue(
            'frontend/translate/modules/studioforty9_carousel/files/default',
            'Studioforty9_Carousel.csv'
        );
    }

    public function test_install_script_exists()
    {
        $this->assertSchemeSetupExists('Studioforty9_Carousel', 'studioforty9_carousel_setup');
        $this->assertSetupResourceDefined('Studioforty9_Carousel', 'studioforty9_carousel_setup');
        $this->assertSchemeSetupScriptVersions(
            '1.0.0',
            '1.0.0',
            'Studioforty9_Carousel',
            'studioforty9_carousel_setup'
        );
    }

    public function test_routes_are_configured()
    {
        $this->assertRouteFrontName('studioforty9_carousel', 'carousel', 'frontend');
    }
}
