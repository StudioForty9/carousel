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
 * Studioforty9_Carousel_Helper_Slide
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Helper
 */
class Studioforty9_Carousel_Helper_Slide extends Mage_Core_Helper_Abstract
{
    /**
     * Get the slide by url key.
     *
     * @param string $sliderId
     * @return bool|Varien_Object
     */
    public function getCollectionBySliderId($sliderId)
    {
        /** @var Studioforty9_Carousel_Model_Resource_Slide_Collection $collection */
        $collection = Mage::getModel('studioforty9_carousel/slide')->getCollection()
            ->addFieldToFilter('main_table.status', array(
                'eq' => Studioforty9_Carousel_Model_Slide::ENABLED
            )
        );

        $collection->getSelect()->joinLeft(
            array('pivot' => $collection->getTable('studioforty9_carousel/carousel_slider_slide')),
            'pivot.slide_id=main_table.entity_id AND pivot.slider_id=' . (!empty($sliderId) ? $sliderId : 0),
            'pivot.position'
        );
        $collection->addOrder('pivot.position', 'ASC');

        return $collection;
    }
}
