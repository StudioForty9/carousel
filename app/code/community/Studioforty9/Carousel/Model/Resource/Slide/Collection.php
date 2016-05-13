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
 * Studioforty9_Carousel_Model_Resource_Slide_Collection
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Model
 */
class Studioforty9_Carousel_Model_Resource_Slide_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize the collection model.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('studioforty9_carousel/slide');
    }
}
