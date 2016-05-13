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
 * Studioforty9_Carousel_Block_Adminhtml_Slider_Edit_Tab_Grid
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Adminhtml_Slider_Edit_Tab_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set the default grid configuration in the constructor.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sliderSlideGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setDefaultFilter(array('selected_slide' => '1'));
        //$this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
    }

    /**
     * Prepare the collection.
     *
     * @return Studioforty9_Carousel_Block_Adminhtml_Slider_Edit_Tab_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->_getCollection());
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() != 'selected_slide') {
            return parent::_addColumnFilterToCollection($column);
        }

        $slideIds = $this->_getSelectedSlide();

        if (empty($slideIds)) {
            return $this;
        }

        if ($column->getFilter()->getValue() == 1) {
            $this->getCollection()->addFieldToFilter('main_table.entity_id', array('in' => $slideIds));
        }
        else {
            if ($slideIds) {
                $this->getCollection()->addFieldToFilter('main_table.entity_id', array('nin' => $slideIds));
            }
        }

        return $this;
    }

    protected function _getSelectedSlide()
    {
        return array_keys($this->getSelectedSlide());
    }

    /**
     *
     *
     * @return array
     * @throws Exception
     */
    public function getSelectedSlide()
    {
        $data = array();
        $slide = $this->getData('selected_slide');
        $sliderId = $this->getRequest()->getParam('id', 0);

        if ($sliderId) {
            if (is_null($slide)) {
                $slide = $this->_getSlider()->getSelectedSlideIds();
            }
            $collection = $this->_getCollection()
                ->addFieldToFilter('main_table.entity_id', array('in' => $slide));

            foreach ($collection as $medium) {
                $data[$medium->getId()] = array('grid_position' => $medium->getPosition());
            }
        }

        return $data;
    }

    /**
     * Prepare the grid columns.
     *
     * @return Studioforty9_Carousel_Block_Adminhtml_Slide_Grid
     */
    protected function _prepareColumns()
    {
        $selected = $this->_getSelectedSlide();
        $this->addColumn('selected_slide', array(
            'header_css_class' => 'a-center',
            'type'       => 'checkbox',
            'name'       => 'selected_slide',
            'field_name' => 'selected_slide',
            'values'     => empty($selected) ? array() : $selected,
            'align'      => 'center',
            'index'      => 'entity_id'
        ));

        $this->addColumn('grid_entity_id', array(
            'header'    => $this->_getHelper()->__('Id'),
            'index'     => 'entity_id',
            'width'    => '50px'
        ));
        $this->addColumn('grid_image_desktop', array(
            'header'   => $this->_getHelper()->__('Desktop Image'),
            'index'    => 'image_desktop',
            'renderer' => 'Studioforty9_Carousel_Block_Adminhtml_Renderer_Image',
            'width'    => '100px'
        ));
        $this->addColumn('grid_image_tablet', array(
            'header'   => $this->_getHelper()->__('Tablet Image'),
            'index'    => 'image_tablet',
            'renderer' => 'Studioforty9_Carousel_Block_Adminhtml_Renderer_Image',
            'width'    => '100px'
        ));
        $this->addColumn('grid_image_phone', array(
            'header'   => $this->_getHelper()->__('Phone Image'),
            'index'    => 'image_phone',
            'renderer' => 'Studioforty9_Carousel_Block_Adminhtml_Renderer_Image',
            'width'    => '100px'
        ));
        $this->addColumn('grid_title', array(
            'header' => $this->_getHelper()->__('Title'),
            'index'  => 'title',
        ));
        $this->addColumn('grid_status', array(
            'header'    => $this->_getHelper()->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'width'     => '100px',
            'options'   => array(
                '1' => $this->_getHelper()->__('Enabled'),
                '0' => $this->_getHelper()->__('Disabled'),
            )
        ));
        $this->addColumn('grid_position', array(
            'header'    => $this->_getHelper()->__('Position'),
            'align'     => 'left',
            'index'     => 'position',
            'field_name' => 'grid_position',
            'width'     => '55px',
            'type'      => 'input',
            'editable'  => true,
            'required'  => true
        ));
    }

    /**
     * Prepare the mass action, specifically, since we're extending the slider
     * grid, we need to disable mass actions.
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Get the row url.
     *
     * @access public
     * @param Studioforty9_Carousel_Model_Slider
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * Get the grid url.
     *
     * @return string
     */
    public function getGridUrl()
    {
        $url = $this->getUrl('*/carousel_slider/slideGrid', array(
            '_current' => true
        ));

        return $this->_getData('grid_url') ? $this->_getData('grid_url'): $url;
    }

    /**
     * Return the module helper.
     *
     * @return Studioforty9_Carousel_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_carousel');
    }

    /**
     * @return Studioforty9_Carousel_Model_Slider
     * @throws Exception
     */
    protected function _getSlider()
    {
        if (! Mage::registry('current_slider')) {
            $sliderId = $this->getRequest()->getParam('id');
            $slider = Mage::getModel('studioforty9_carousel/slider')->load($sliderId);
            Mage::register('current_slider', $slider);
        }

        return Mage::registry('current_slider');
    }

    /**
     * After collection load.
     *
     * @return Studioforty9_Carousel_Block_Adminhtml_Slide_Grid
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store column.
     *
     * @param Studioforty9_Carousel_Model_Resource_Slide_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Studioforty9_Carousel_Block_Adminhtml_Slide_Grid
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }

    protected function _getCollection()
    {
        /** @var Studioforty9_Carousel_Model_Resource_Slide_Collection $collection */
        $collection = Mage::getModel('studioforty9_carousel/slide')->getCollection();
        $sliderCount = Mage::getModel('studioforty9_carousel/slider')->getCollection()->count();

        if ($sliderCount > 0) {
            $entityId = $this->_getSlider()->getId();
            $collection->getSelect()->joinLeft(
                array('pivot' => $collection->getTable('studioforty9_carousel/carousel_slider_slide')),
                'pivot.slide_id=main_table.entity_id AND pivot.slider_id=' . (!empty($entityId) ? $entityId : 0),
                'pivot.position'
            );
            $collection->addOrder('pivot.position', 'ASC');
            $this->setDefaultSort('grid_position');
            $this->setDefaultFilter(array('selected_slide' => '1'));
        }

        return $collection;
    }
}
