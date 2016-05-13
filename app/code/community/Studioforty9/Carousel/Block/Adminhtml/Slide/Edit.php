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
 * Studioforty9_Carousel_Block_Adminhtml_Slide_Edit
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Adminhtml_Slide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * The mode tells Magento which folder to use to locate the
     * related form blocks to be displayed within this form container.
     * @var string $_mode
     */
    protected $_mode = 'edit';

    /**
     * The controller tells Magento which controller to use.
     * @var string $_controller
     */
    protected $_controller = 'slide';

    /**
     * The blockGroup tells magento where to find adminhtml blocks.
     * @var string $_blockGroup
     */
    protected $_blockGroup = 'studioforty9_carousel_adminhtml';

    /**
     * The constructor will just dynamically set the heading for the
     * type of action we want to make. New or Edit.
     */
    protected function _construct()
    {
        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('Edit') 
            : $this->__('New');
        $this->_headerText =  $newOrEdit . ' ' . $this->__('Slide');

        $this->_formScripts[] = Mage::helper('studioforty9_carousel')->getWysiwygConfig();
    }
}
