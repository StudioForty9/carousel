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
 * Studioforty9_Carousel_Block_Adminhtml_Slider
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /** @var string */
    protected $_controller = 'adminhtml_slider';

    /** @var string */
    protected $_blockGroup = 'studioforty9_carousel';

    /**
     * Set up the Grid Page.
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_headerText = $this->_getHelper()->__('Sliders');

        // Add the ass slider button
        $this->_updateButton('add', 'label', $this->_getHelper()->__('Add Slider'));

        // Add the sort order save button
        $this->_addButton('position', array(
            'label'     => $this->_getHelper()->__('Save Sort Order'),
            'class'     => 'sort',
            'id'        => 'sort-position'
        ), -1);
    }

    /**
     * Fetch the module helper.
     *
     * @return Studioforty9_Carousel_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_carousel');
    }
}
