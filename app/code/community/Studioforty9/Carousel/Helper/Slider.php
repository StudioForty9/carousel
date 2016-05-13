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
 * Studioforty9_Carousel_Helper_Slider
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Helper
 */
class Studioforty9_Carousel_Helper_Slider extends Mage_Core_Helper_Abstract
{
    /**
     * Get the slider by id.
     *
     * @param string $sliderId
     * @return bool|Varien_Object
     */
    public function getSliderById($sliderId)
    {
        $slider = Mage::getModel('studioforty9_carousel/slider')->load($sliderId);

        if ($slider->getId() === 0) {
            return false;
        }

        return $slider;
    }
}
