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
 * Studioforty9_Carousel_Adminhtml_Carousel_SlideController
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Controller
 * @layoutXml app/design/adminhtml/default/default/layout/studioforty9_carousel.xml
 */
class Studioforty9_Carousel_Adminhtml_Carousel_SlideController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @var array $_uploadFields
     */
    protected $_uploadFields = array(
        'image_desktop' => array('missing' => false, 'delete' => false),
        'image_tablet'  => array('missing' => false, 'delete' => false),
        'image_phone'   => array('missing' => false, 'delete' => false)
    );
    
    /**
     * Set some defaults on preDispatch
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_title($this->_getHelper()->__('Carousel'))
            ->_title($this->_getHelper()->__('Slide'));
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
     * The editAction displays the edit form.
     */
    public function editAction()
    {
        /* @var Studioforty9_Carousel_Model_Slide $slide */
        $slide = Mage::getModel('studioforty9_carousel/slide');

        // Guard against the slide Id not existing
        if ($slideId = $this->getRequest()->getParam('id', false)) {
            $slide->load($slideId);
            if ($slide->getId() == 0) {
                $this->_getSession()->addError(
                    $this->__('The slide you referenced no longer exists.')
                );
                return $this->_redirect('*/*/');
            }
        }

        // process $_POST data if the form was submitted
        if ($this->getRequest()->isPost()) {
            $this->_saveAction($slide, $this->getRequest()->getPost());
        }

        // make the current slide object available to blocks
        Mage::register('current_slide', $slide);

        // add the form container and tags
        $this->loadLayout();

        $this->_setActiveMenu('studioforty9/carousel');
        $this->_addBreadcrumb($this->__('Carousel'), $this->__('Slide'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->renderLayout();
    }

    /**
     * The _saveAction saves the data from the editAction.
     */
    protected function _saveAction($slide, $postData)
    {
        $sliderIds = array();
        if (array_key_exists('slider_ids', $postData['content'])) {
            foreach ($postData['content']['slider_ids'] as $sliderId) {
                $sliderIds[$sliderId] = array();
            }
        }

        foreach ($postData['content'] as $key => $value) {
            $postData[$key] = $value;
        }

        // Unset the image upload fields and any data not stored directly on the slide table
        unset(
            $postData['image_desktop'],
            $postData['image_tablet'],
            $postData['image_phone'],
            $postData['form_key'],
            $postData['slider_ids'],
            $postData['content']
        );

        $this->_uploadImages($slide, $postData);

        try {
            // Add the post data to the slide model
            $slide->addData($postData);
            $slide->save();
            $slide->syncSliders($sliderIds);
            $this->_getSession()->addSuccess(
                $this->__('The slide content was saved successfully.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            return $this->_redirect('*/*');
        }

        //$route = ($deleteFile || $missingFile) ? '*/*/edit' : '*/*/index';
        
        $route = '*/*/index';

        return $this->_redirect($route, array('id' => $slide->getId()));
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

        $slide = Mage::getModel('studioforty9_carousel/slide')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => array_keys($params)));

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($slide as $medium) {
                $medium->setData('position', $params[$medium->getId()]);
                $transaction->addObject($medium);
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
     * The massDeleteAction deletes lots of slide at once.
     */
    public function massDeleteAction()
    {
        $slideIds = $this->getRequest()->getParam('slide');
        $slide = Mage::getModel('studioforty9_carousel/slide')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $slideIds));

        if (empty($slide)) {
            $this->_getSession()->addError($this->_getHelper()->__('No slide selected for deletion.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($slide as $medium) {
                $transaction->addObject($medium);
            }
            $transaction->delete();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s slide items were deleted.',
                $slide->count(),
                count($slideIds)
            )
        );

        return $this->_redirect('*/*');
    }

    /**
     * The massStatusAction updates the status of lots of slide at once.
     */
    public function massStatusAction()
    {
        $status = ($this->getRequest()->getParam('status') == 1)
                ? Studioforty9_Carousel_Model_Slide::ENABLED
                : Studioforty9_Carousel_Model_Slide::DISABLED;
        $slideIds = $this->getRequest()->getParam('slide');
        $slide = Mage::getModel('studioforty9_carousel/slide')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $slideIds));

        if (empty($slide)) {
            $this->_getSession()->addError($this->_getHelper()->__('No slide selected for status update.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($slide as $medium) {
                $medium->setStatus($status);
                $transaction->addObject($medium);
            }

            $transaction->save();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s slide items were updated.',
                $slide->count(),
                count($slideIds)
            )
        );
        return $this->_redirect('*/*');
    }

    /**
     * The massRelationAction relates lots of slide to an slider at once.
     */
    public function massRelationAction()
    {
        $sliderId = (int) $this->getRequest()->getParam('slider');
        $slider = Mage::getModel('studioforty9_carousel/slider')->load($sliderId);

        if ($slider->getId() == 0) {
            return $this->_redirect('*/*');
        }

        $slideIds = array();
        $slideIdsRaw = $this->getRequest()->getParam('slide');
        if (!empty($slideIdsRaw) && is_array($slideIdsRaw)) {
            foreach ($slideIdsRaw as $slideId) {
                $slideIds[$slideId] = array();
            }
        }

        try {
            $slider->syncSlide($slideIds);
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s slide items were related to Slider ID %s.',
                count($slideIds),
                $sliderId
            )
        );
        return $this->_redirect('*/*');
    }

    /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    /**
     * Upload the images from the $_FILES array.
     *
     * @param Studioforty9_Carousel_Model_Slide $slide
     * @param array                             $postData
     *
     * @throws Exception
     */
    protected function _uploadImages($slide, $postData)
    {
        foreach ($this->_uploadFields as $_field => $_info) {
            // check if the field exists in the post data array
            if (array_key_exists($_field, $postData)) {
                $field = $postData[$_field];
                // if it does, it probably has a delete key - e.g. $data['image_desktop']['delete'] = 1|0 
                if (array_key_exists('delete', $field) && (int) $field['delete'] == 1) {
                    $this->_uploadFields[$_field]['delete'] = true;
                    $slide->setData($_field, '');
                }
            } else {
                // if the field doesnt exist in the post data array, we're good to upload the image
                if (!empty($_FILES) && isset($_FILES['content']['tmp_name'][$_field])) {
                    try {
                        $uploader = new Varien_File_Uploader(sprintf('content[%s]', $_field));

                        // TODO: Make this a configuration option
                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                        // TODO: Make this a configuration option
                        $uploader->setAllowRenameFiles(true);
                        // TODO: Make this a configuration option
                        $uploader->setFilesDispersion(false);
                        // TODO: Make this a configuration option

                        $path = $this->_getHelper()->getImagePath($_field);
                        $name = $this->_getHelper()->formatUrlKey($postData['title']);
                        $filename = $name .'.'. $uploader->getFileExtension();
                        $uploader->save($path, $filename);
                        $slide->setData($_field, $uploader->getUploadedFileName());
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $this->_getSession()->addError($e->getMessage());
                    }
                } else {
                    $current = $slide->getData($_field);
                    if (!$this->_uploadFields[$_field]['delete'] && empty($current)) {
                        $this->_getSession()->addError('The slide file is required.');
                        $this->_uploadFields[$_field]['missing'] = true;
                    }
                }
            }
        }
    }

    /**
     * Get a Carousel helper.
     *
     * @param string $name
     * @return Studioforty9_Carousel_Helper_Data
     */
    protected function _getHelper($name = 'data')
    {
        return Mage::helper('studioforty9_carousel/' . $name);
    }
}
