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
 * Studioforty9_Carousel_Model_Slide
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Model
 */
class Studioforty9_Carousel_Model_Slide extends Mage_Core_Model_Abstract
{
    /**
     * Entity code - can be used as part of method name for entity processing.
     * @const string
     */
    const ENTITY    = 'studioforty9_carousel_slide';

    /**
     * The config mapping to the studioforty9_carousel_slide_slider table.
     * @const string
     */
    const PIVOT_TABLE = 'studioforty9_carousel/carousel_slider_slide';

    /**
     * TODO: figure out how this works with the caching system
     * @const string
     */
    const CACHE_TAG = 'studioforty9_carousel_slide';

    /**
     * @const int
     */
    const ENABLED = 1;

    /**
     * @const int
     */
    const DISABLED = 0;

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'studioforty9_carousel_slide';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'slide';

    /**
     * constructor will set the resource model
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('studioforty9_carousel/slide');
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        $this->setDesign(
            Mage::helper('core')->jsonEncode($this->getData('design'))
        );

        // Auto save dates
        $now = Varien_Date::now();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
            $this->setUpdatedAt($now);
        } else {
            $this->setUpdatedAt($now);
        }
    }

    /**
     * Addition processing after load.
     */
    protected function _afterLoad()
    {
        $this->_setDesign();
    }

    protected function _setDesign()
    {
        $design = $this->getData('design');
        if (is_string($design)) {
            $design = Mage::helper('core')->jsonDecode($this->getData('design'));
            $this->setDesign($design);
        }

        return $design;
    }

    public function getDesignObject()
    {
        $obj = new Varien_Object();
        $obj->setData($this->_setDesign());
        return $obj;
    }

    /**
     * Get design options as a html string.
     *
     * @param string $prefix
     *
     * @return string
     */
    public function getDesignOptions($prefix = 'data')
    {
        $html = '';

        if (! $this->hasData('design') || empty($design = $this->getData('design'))) {
            return $html;
        }

        foreach ($design as $key => $value) {
            $html .= sprintf('%s-%s="%s" ', $prefix, $key, $value);
        }

        return $html;
    }

    /**
     * Fetch the full path to the resized image.
     *
     * @param string $type
     * @param int    $width
     * @param int    $height
     * @param int    $quality
     * @param bool   $keepAspectRatio
     * @param bool   $keepFrame
     *
     * @return string
     */
    public function getResizedImage($type, $width, $height, $quality = 80, $keepAspectRatio = true, $keepFrame = true)
    {
        $source = Mage::helper('studioforty9_carousel')->getImagePath($type) . $this->getData($type);
        $image = Mage::helper('studioforty9_carousel/image')
            ->init($source, $type)
            ->setQuality($quality)
            ->setKeepAspectRatio($keepAspectRatio)
            ->setKeepFrame($keepFrame)
            ->resize($width, $height);

        return $image->__toString();
    }

    /**
     * Get the URL of the slide.
     *
     * @return string
     */
    public function getUrl($type)
    {
        return Mage::getUrl('media/carousel/' . $type . '/' . $this->getData($type));
    }

    /**
     * Get the URL of the desktop image.
     *
     * @return string
     */
    public function getDesktopImageUrl()
    {
        $key = 'image_desktop';
        $dir = Mage::helper('studioforty9_carousel')->getImageUrl($key);
        $img = $this->getData($key);
        return $dir . $img;
    }

    /**
     * Get the URL of the tablet image.
     *
     * @return string
     */
    public function getTabletImageUrl()
    {
        $key = 'image_tablet';
        $dir = Mage::helper('studioforty9_carousel')->getImageUrl($key);
        $img = $this->getData($key);
        return $dir . $img;
    }

    /**
     * Get the URL of the phone image.
     *
     * @return string
     */
    public function getPhoneImageUrl()
    {
        $key = 'image_phone';
        $dir = Mage::helper('studioforty9_carousel')->getImageUrl($key);
        $img = $this->getData($key);
        return $dir . $img;
    }
    
    public function isCaptionEnabled()
    {
        return true;
    }

    /**
     * TODO: Add a store filter
     * @return array
     */
    public function getSelectedSliderIds()
    {
        return $this->getRelatedSliders()->getAllIds();
    }

    /**
     * TODO: Add a store filter
     * @return mixed
     */
    public function getRelatedSliders()
    {
        $entityId = $this->getId();
        /** @var Studioforty9_Carousel_Model_Resource_Slide_Collection $collection */
        $collection = Mage::getModel('studioforty9_carousel/slider')->getCollection();
        $collection->join(
            array('pivot' => self::PIVOT_TABLE),
            'pivot.slider_id=main_table.entity_id AND pivot.slide_id=' . (!empty($entityId) ? $entityId : 0),
            'position'
        );
        $collection->addOrder('main_table.position', 'ASC');

        return $collection;
    }

    /**
     * Sync the pivot table slider/slide ids and update the positions passed in.
     *
     * @param array $sliderIds
     * @return bool
     */
    public function syncSliders($sliderIds)
    {
        $relations = Mage::getModel('studioforty9_carousel/relations');
        $relations->init('slide_id', $this->getId(), 'slider_id', array('position'));
        $relations->sync($sliderIds);
    }
}
