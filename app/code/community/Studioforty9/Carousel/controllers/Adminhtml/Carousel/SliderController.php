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
 * Studioforty9_Carousel_Adminhtml_Carousel_SliderController
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Controller
 */
class Studioforty9_Carousel_Adminhtml_Carousel_SliderController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Set some defaults on preDispatch
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_title($this->_getHelper()->__('Carousel'))
             ->_title($this->_getHelper()->__('Sliders'));
    }

    /**
     * The indexAction should display a grid.
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * The gridAction is actually more for ajax requests from the index grid.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * The sortAction is run via AJAX and saves the position order.
     */
    public function sortAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return false;
        }

        $params = array_filter($this->getRequest()->getParams(), function($param) {
            return is_numeric($param);
        });

        $sliders = Mage::getModel('studioforty9_carousel/slider')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => array_keys($params)));

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($sliders as $slider) {
                $slider->setData('position', $params[$slider->getId()]);
                $transaction->addObject($slider);
            }

            $transaction->save();
            $message = 'Order updated';
            $error = false;
        }
        catch (Exception $e) {
            $message = $e->getMessage();
            $error = true;
        }

        echo Mage::helper('core')->jsonEncode(array(
            'error' => $error,
            'message' => $message
        ));
    }

    /**
     * The newAction simply forwards to editAction.
     */
    public function newAction()
    {
        return $this->_forward('edit');
    }

    /**
     * The slideTabAction loads in the slideGrid via AJAX when you click
     * on the tab via the editForm.
     */
    public function slideTabAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * The slideGridAction sets some data on the slideGrid and enables AJAX
     * searching etc.
     */
    public function slideGridAction()
    {
        $selectedSlide = $this->getRequest()->getPost('slide_selected', null);

        $this->loadLayout();
        $this->getLayout()->getBlock('slider.slidegrid')->setSelectedSlide($selectedSlide);
        $this->renderLayout();
    }

    /**
     * The editAction displays the edit form.
     */
    public function editAction()
    {
        /* @var Studioforty9_Carousel_Model_Slider $slider */
        $slider = Mage::getModel('studioforty9_carousel/slider');

        // Guard against the slider Id not existing
        if ($sliderId = $this->getRequest()->getParam('id', false)) {
            $slider->load($sliderId);
            if ($slider->getId() == 0) {
                $this->_getSession()->addError(
                    $this->__('The slider you referenced no longer exists.')
                );
                return $this->_redirect('*/*/');
            }
        }

        // process $_POST data if the form was submitted
        if ($this->getRequest()->isPost()) {
            $this->_saveAction($slider, $this->getRequest()->getPost());
        }

        // make the current slider object available to blocks
        Mage::register('current_slider', $slider);

        // add the form container and tags
        $this->loadLayout();

        $this->_setActiveMenu('studioforty9/carousel');
        $this->_addBreadcrumb($this->__('Carousel'), $this->__('Slider'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->renderLayout();
    }

    /**
     * The _saveAction saves the data from the editAction.
     */
    protected function _saveAction(Studioforty9_Carousel_Model_Slider $slider, $postData)
    {
        if ($slideIds = $this->getRequest()->getParam('slide_ids', null)) {
            $slideIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($postData['slide_ids']);
        }

        foreach ($postData['content'] as $key => $value) {
            $postData[$key] = $value;
        }

        // Unset the useless fields
        $ignored = array(
            'page', 'limit', 'form_key', 'selected_slide', 'slide_ids', 'thumbnail',
            'grid_entity_id', 'grid_title', 'grid_status', 'grid_position',
            'grid_image_destop', 'grid_image_tablet', 'grid_image_phone', 'content'
        );
        
        foreach ($ignored as $ignore) {
            unset($postData[$ignore]);
        };

        try {
            $slider->addData($postData);
            $slider->save();
            $slider->syncSlide($slideIds);
            $this->_getSession()->addSuccess(
                $this->__('The slider content was saved successfully.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            return $this->_redirect('*/*');
        }

        return $this->_redirect('*/*/index', array('id' => $slider->getId()));
    }

    /**
     * The deleteAction deletes an slider.
     */
    public function deleteAction()
    {
        $sliderId = $this->getRequest()->getParam('id');
        $slider = Mage::getModel('studioforty9_carousel/slider')->load($sliderId);

        try {
            $slider->delete();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__('Slider was deleted successfully.')
        );

        return $this->_redirect('*/*');
    }

    /**
     * The massDeleteAction deletes lots of slide at once.
     */
    public function massDeleteAction()
    {
        $sliderIds = $this->getRequest()->getParam('sliders');
        $sliders = Mage::getModel('studioforty9_carousel/slider')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $sliderIds));

        if (empty($sliders)) {
            $this->_getSession()->addError($this->_getHelper()->__('No sliders selected for deletion.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($sliders as $slider) {
                $transaction->addObject($slider);
            }

            $transaction->delete();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s sliders were deleted.',
                $sliders->count(),
                count($sliderIds)
            )
        );
        return $this->_redirect('*/*');
    }

    /**
     * The massStatusAction updates the status of lots of slide at once.
     */
    public function massStatusAction()
    {
        $status = ($this->getRequest()->getParams('status') == 1)
                ? Studioforty9_Carousel_Model_Slider::ENABLED
                : Studioforty9_Carousel_Model_Slider::DISABLED;

        $sliderIds = $this->getRequest()->getParam('sliders');
        $sliders = Mage::getModel('studioforty9_carousel/slider')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $sliderIds));

        if (empty($sliders)) {
            $this->_getSession()->addError($this->_getHelper()->__('No sliders selected for status update.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($sliders as $slider) {
                $slider->setStatus($status);
                $transaction->addObject($slider);
            }

            $transaction->save();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s sliders were updated.',
                $sliders->count(),
                count($sliderIds)
            )
        );
        return $this->_redirect('*/*');
    }

    /**
     * Get a helper.
     *
     * @param string $name
     * @return Studioforty9_Carousel_Helper_Data
     */
    protected function _getHelper($name = 'data')
    {
        return Mage::helper('studioforty9_carousel/' . $name);
    }
}
