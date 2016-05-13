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
 * Studioforty9_Carousel_Block_Slide_View
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Slide_View extends Mage_Core_Block_Template
{
    /**
     * Set the collection on the block before rendering the html.
     */
    public function _beforeToHtml()
    {
        if ($this->hasSliderId()) {
            $this->setSlider($this->getSliderById($this->getSliderId()));
            $this->setSlides($this->getSlidesBySliderId($this->getSliderId()));
        }
    }

    /**
     * Get a slider by it's id.
     *
     * @param  int $sliderId
     * @return Studioforty9_Carousel_Model_Slider
     */
    protected function getSliderById($sliderId)
    {
        return Mage::helper('studioforty9_carousel/slider')->getSliderById($sliderId);
    }

    /**
     * Get the associated slides for a slider by it's id.
     *
     * @param int $sliderId
     * @return Studioforty9_Carousel_Model_Resource_Slide_Collection
     */
    protected function getSlidesBySliderId($sliderId)
    {
        return Mage::helper('studioforty9_carousel/slide')->getCollectionBySliderId($sliderId);
    }
}
