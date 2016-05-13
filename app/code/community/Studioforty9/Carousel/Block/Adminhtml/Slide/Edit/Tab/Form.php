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
 * Studioforty9_Carousel_Block_Adminhtml_Slide_Edit_Tab_Form
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Adminhtml_Slide_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * _prepareForm()
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $this->_getHelper('adminhtml')->prepareForm('slide', $form, function($data, $model, $request)
        {
            $key = $data['id'];

            if ($request->hasData($key)) {
                $data['value'] = $model->getData($key);
            }
            else if ($request->hasData('design')) {
                // TODO: Remember data for design fields on POST
                var_dump($request->getData('design'));
            }
            else if ($model->hasData($key)) {
                $data['value'] = $model->getData($key);
            }
            else if ($model->getDesignObject()->hasData($key)) {
                $data['value'] = $model->getDesignObject()->getData($key);
            }

            if ('slider_ids' === $key) {
                $data['value'] = $model->getSelectedSliderIds();
            }

            return $data;
        });

        return parent::_prepareForm();
    }

    /**
     * _getHelper()
     *
     * @param string $helper
     * @return Studioforty9_Carousel_Helper_Data
     */
    protected function _getHelper($helper = 'data')
    {
        if (!empty($helper)) {
            return Mage::helper(sprintf('studioforty9_carousel/%s', $helper));
        }
        return Mage::helper('studioforty9_carousel');
    }

    /**
     * _getSession()
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}
