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
 * Studioforty9_Carousel_Helper_Data
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Helper
 */
class Studioforty9_Carousel_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * getImagePath
     *
     * @param string $type
     * @return string
     */
    public function getImagePath($type)
    {
        return Mage::getBaseDir('media') . DS . 'carousel' . DS . $type . DS;
    }

    /**
     * getImageUrl
     *
     * @param string $type
     * @return string
     */
    public function getImageUrl($type)
    {
        return Mage::getBaseUrl('media') . sprintf('carousel/%s/', $type);
    }

    /**
     * Format a string into a valid URL key.
     *
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        $str = Mage::helper('core')->removeAccents($str);
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $str);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }

    /**
     * Turn a comma separated string into an array.
     *
     * @param string $string
     * @return array
     */
    public function getCommaSeparatedAsArray($string)
    {
        if (empty($string)) {
            return array();
        }

        $limits = array();
        foreach (explode(',', $string) as $num) {
            $limits[$num] = $num;
        }

        return $limits;
    }

    /**
     * Get the global wysiwyg configuration for the extension.
     *
     * @return string
     */
    public function getWysiwygConfig()
    {
        return <<<WYSIWYG
if(window.tinyMceWysiwygSetup) {
    tinyMceWysiwygSetup.prototype.originalGetSettings = tinyMceWysiwygSetup.prototype.getSettings;
    tinyMceWysiwygSetup.prototype.getSettings = function(mode) {
        var settings = this.originalGetSettings(mode);
        settings.extended_valid_elements = 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]';
        settings.theme_advanced_buttons1 = 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,blockquote,outdent,indent,|,formatselect,|,pasteword,|,bullist,numlist,|,link,unlink,anchor,image,|,fontselect,fontsizeselect,fullscreen';
        settings.theme_advanced_buttons2 = '';
        settings.theme_advanced_buttons3 = '';
        settings.theme_advanced_buttons4 = '';
        settings.forced_root_block = false;
        return settings;
    };
}
WYSIWYG;
    }
}
