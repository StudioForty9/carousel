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
 * Studioforty9_Carousel_Block_Adminhtml_Slide_Helper_Image
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Block
 */
class Studioforty9_Carousel_Block_Adminhtml_Slide_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * _getUrl()
     *
     * @return bool|string
     */
    protected function _getUrl()
    {
        $type = $this->getId();
        $value = $this->getValue();

        if (is_array($value)) {
            $value = $value['value'];
        }

        if (empty($value)) {
            return false;
        }

        $path = 'carousel/' . $type . '/'. $value;
        $file = Mage::getBaseDir('media') . '/'. $path;
        
        if (!file_exists($file)) {
            return false;
        }

        return Mage::getBaseUrl('media') . $path;
    }
}
