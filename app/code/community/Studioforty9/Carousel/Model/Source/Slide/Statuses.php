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
 * Studioforty9_Carousel_Model_Source_Slide_Statuses
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Model
 */
class Studioforty9_Carousel_Model_Source_Slide_Statuses
{
    /**
     * The dropdown for statuses
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Studioforty9_Carousel_Model_Slide::ENABLED,
                'label' => Mage::helper('studioforty9_carousel')->__('Enabled')
            ),
            array(
                'value' => Studioforty9_Carousel_Model_Slide::DISABLED,
                'label' => Mage::helper('studioforty9_carousel')->__('Disabled')
            ),
        );
    }
}
