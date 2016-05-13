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
 * Studioforty9_Carousel_Model_Source_Slider_Multiselect
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Model
 */
class Studioforty9_Carousel_Model_Source_Slider_Multiselect
{
    /**
     * Create a list of multiselect options for selecting sliders.
     *
     * @return array
     */
    public function toOptionsArray()
    {
        $sliders = Mage::getModel('studioforty9_carousel/slider')->getCollection()
            ->addFieldToSelect(array('name', 'entity_id'))
            ->addOrder('name', 'ASC');

        $options = array();
        if ($sliders->count() > 0) {
            foreach ($sliders as $slider) {
                $options[] = array(
                    'label' => $slider->getName(),
                    'value' => (int) $slider->getId()
                );
            }
        }

        return $options;
    }
}
