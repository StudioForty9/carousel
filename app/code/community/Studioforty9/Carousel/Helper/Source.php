<?php
/**
 * Studioforty9 Carousel
 *
 * @category  Studioforty9
 * @package   Studioforty9_Carousel
 * @author    StudioForty9 <info@studioforty9.com>
 * @copyright 2015 StudioForty9 (http://www.studioforty9.com)
 * @license   https://github.com/studioforty9/carousel/blob/master/LICENCE BSD
 * @version   1.0.0
 * @link      https://github.com/studioforty9/carousel
 */

/**
 * Studioforty9_Carousel_Helper_Source
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Helper
 */
class Studioforty9_Carousel_Helper_Source extends Mage_Core_Helper_Abstract
{
    /**
     * Get the slide statuses as dropdown options.
     *
     * @return array
     */
    public function getSlideStatusOptions()
    {
        $statuses = Mage::getModel('studioforty9_carousel/source_slide_statuses');

        return $statuses->toOptionArray();
    }

    /**
     * Get the slider statuses as dropdown options.
     *
     * @return array
     */
    public function getSliderStatusOptions()
    {
        $statuses = Mage::getModel('studioforty9_carousel/source_slider_statuses');

        return $statuses->toOptionArray();
    }

    public function getSliderOptions()
    {
        $model = Mage::getModel('studioforty9_carousel/slider')->getCollection();

        return $model->toOptionArray();
    }

    /**
     * Get the slider names as dropdown options.
     *
     * @return array
     */
    public function getSliderMultiselectOptions()
    {
        $source = Mage::getModel('studioforty9_carousel/source_slider_multiselect');

        return $source->toOptionsArray();
    }
}
