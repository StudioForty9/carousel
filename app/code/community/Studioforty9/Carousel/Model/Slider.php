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
 * Studioforty9_Carousel_Model_Slider
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Model
 */
class Studioforty9_Carousel_Model_Slider extends Mage_Core_Model_Abstract
{
    /**
     * Entity code - can be used as part of method name for entity processing.
     * @const string
     */
    const ENTITY    = 'studioforty9_carousel_slider';

    /**
     * The config mapping to the studioforty9_carousel_slider_slide table.
     * @const string
     */
    const PIVOT_TABLE = 'studioforty9_carousel/carousel_slider_slide';

    /**
     * @const string
     */
    const CACHE_TAG = 'studioforty9_carousel_slider';

    /**
     * @const string
     */
    const ENABLED = 1;

    /**
     * @const string
     */
    const DISABLED = 0;

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'studioforty9_carousel_slider';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'slider';

    /**
     * The constructor will set the resource model
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('studioforty9_carousel/slider');
    }

    /**
     * Addition processing before save.
     */
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

        if (! $this->hasDesign() || empty($design = $this->getDesign())) {
            return $html;
        }

        if (is_string($design)) {
            $design = json_decode($design, true);
        }

        foreach ($design as $key => $value) {
            $html .= sprintf('%s-%s="%s" ', $prefix, $key, $value);
        }

        return $html;
    }

    /**
     * Fetch the full path to the resized image.
     *
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getResizedImage($width, $height)
    {
        $source = Mage::helper('studioforty9_carousel')->getImagePath('slider') . $this->getThumbnail();
        $image = Mage::helper('studioforty9_carousel/image')
            ->init($source, 'slider')
            ->resize($width, $height);

        return $image->__toString();
    }

    /**
     * Get the URL of the slider.
     *
     * @return string
     */
    public function getUrl()
    {
        return Mage::getUrl('carousel/slider/'.$this->getUrlKey());
    }

    /**
     * Get the selected slide Ids.
     *
     * @return array
     */
    public function getSelectedSlideIds()
    {
        return $this->getRelatedSlide()->getAllIds();
    }

    /**
     * Get the related slide.
     *
     * @return Studioforty9_Carousel_Model_Resource_Slide_Collection
     */
    public function getRelatedSlide()
    {
        $entityId = $this->getId();
        /** @var Studioforty9_Carousel_Model_Resource_Slide_Collection $collection */
        $collection = Mage::getModel('studioforty9_carousel/slide')->getCollection();
        $collection->join(
            array('pivot' => self::PIVOT_TABLE),
            'pivot.slide_id=main_table.entity_id AND pivot.slider_id=' . (!empty($entityId) ? $entityId : 0),
            'position'
        );
        $collection->addOrder('pivot.position', 'ASC');

        return $collection;
    }

    /**
     * Sync the pivot table slider/Slide ids and update the positions passed in.
     *
     * @param array $slideIds
     * @return bool
     */
    public function syncSlide($slideIds)
    {
        $relations = Mage::getModel('studioforty9_carousel/relations');
        $relations->init('slider_id', $this->getId(), 'slide_id', array('position'));
        $relations->sync($slideIds);
    }
}
