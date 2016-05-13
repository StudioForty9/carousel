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
 * Studioforty9_Carousel_Block_Adminhtml_Slide_Edit_Tabs
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Set up the edit tabs.
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->_getHelper()->__('Slider'));
    }

    /**
     * _beforeToHtml()
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'   => $this->_getHelper()->__('Slider Content'),
            'title'   => $this->_getHelper()->__('Slider Content'),
            'content' => $this->getLayout()->createBlock(
                'studioforty9_carousel/adminhtml_slider_edit_tab_form'
            )->toHtml(),
        ));

        $this->addTab('grid_section', array(
            'label'   => $this->_getHelper()->__('Associated Slides'),
            'title'   => $this->_getHelper()->__('Associated Slides'),
            'url'       => $this->getUrl('*/*/slidetab', array('_current' => true)),
            'class'     => 'ajax'
        ));

        return parent::_beforeToHtml();
    }

    /**
     * _getHelper()
     *
     * @return Studioforty9_Carousel_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_carousel');
    }
}
