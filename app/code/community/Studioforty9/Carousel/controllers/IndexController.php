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
 * Studioforty9_Carousel_IndexController
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Controller
 */
class Studioforty9_Carousel_IndexController extends Mage_Core_Controller_Front_Action
{
    /** @var Studioforty9_Carousel_Helper_Data */
    protected $_helper;

    /**
     * _construct runs before every action, consider preDispatch() here too.
     */
    protected function _construct()
    {
        $this->_helper = Mage::helper('studioforty9_carousel');
    }

    /**
     * The index action should be the starting point for viewing slide in
     * the carousel. You can configure the extension to use sliders as the
     * index page or a specific sliders slide.
     *
     * @return self
     */
    public function indexAction()
    {
        $this->_initCarouselLayout();
        $this->_initSeoContent();
        return $this->renderLayout();
    }

    /**
     * The slider controller action is responsible for displaying a
     * specific slider.
     *
     * @return Mage_Core_Controller_Varien_Action
     */
    public function sliderAction()
    {
        $urlKey = $this->getRequest()->get('url_key');
        if (!$urlKey) {
            return $this->norouteAction();
        }

        $slider = Mage::helper('studioforty9_carousel/slider')->getSliderByUrlKey($urlKey);
        Mage::getSingleton('core/session')->setSliderUrlKey($urlKey);
        Mage::register('current_slider', $slider);

        $this->_initCarouselLayout();
        $this->_initSeoContent(
            '' !== (string) $slider->getMetaTitle() ? $slider->getMetaTitle() : $slider->getName(),
            $slider->getMetaKeywords(),
            $slider->getMetaDescription()
        );

        if ($this->_helper->getUseBreadcrumbs()) {
            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbs->addCrumb('slider', array(
                    'label' => $this->_helper->__($slider->getName())
                ));
            }
        }

        return $this->renderLayout();
    }

    public function slideAction()
    {
        $urlKey = $this->getRequest()->get('url_key');
        if (! $urlKey) {
            return $this->norouteAction();
        }

        /** @var Studioforty9_Carousel_Helper_Slider $sliderHelper */
        $sliderHelper = Mage::helper('studioforty9_carousel/slider');
        $sliderUrlKey = Mage::getSingleton('core/session')->getData('slider_url_key', false);
        $slider = $sliderHelper->getSliderByUrlKey($sliderUrlKey);
        Mage::register('current_slider', $slider);

        /** @var Studioforty9_Carousel_Helper_Slide $slideHelper */
        $slideHelper = Mage::helper('studioforty9_carousel/slide');
        $slide = $slideHelper->getSlideByUrlKey($urlKey);
        Mage::register('current_slide', $slide);

        $this->_initCarouselLayout();
        $this->_initSeoContent(
            '' !== (string) $slide->getMetaTitle() ? $slide->getMetaTitle() : $slide->getName(),
            $slide->getMetaKeywords(),
            $slide->getMetaDescription()
        );

        if ($this->_helper->getUseBreadcrumbs()) {
            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbs->addCrumb('slider', array(
                    'label' => $this->_helper->__($slider->getName()),
                    'link'  => $sliderHelper->getSliderUrl()
                ));
                $breadcrumbs->addCrumb('slide', array(
                    'label' => $this->_helper->__($slide->getName())
                ));
            }
        }

        return $this->renderLayout();
    }

    protected function _initCarouselLayout()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
    }

    protected function _initSeoContent($title = null, $keywords = null, $description = null)
    {
        $title = is_null($title) ? $this->_helper->getSeoTitle() : $title;
        $keywords = is_null($keywords) ? $this->_helper->getSeoKeywords() : $keywords;
        $description = is_null($description) ? $this->_helper->getSeoDescription() : $description;

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($title);
            $headBlock->setKeywords($keywords);
            $headBlock->setDescription($description);
        }

        return $headBlock;
    }
}
