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
 * Studioforty9_Carousel_Model_Config
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Model
 */
class Studioforty9_Carousel_Model_Config extends Varien_Simplexml_Config
{
    /**
     * The directory which stores carousel form fields.
     *
     * @var string
     */
    protected $_formDir = 'carousel_forms';

    /**
     * Class construct
     *
     * @param mixed $sourceData
     */
    public function __construct($sourceData=null)
    {
        $this->_prototype = new Varien_Simplexml_Config();
        parent::__construct($sourceData);
    }

    /**
     * Load the configuration file.
     *
     * @param string $file
     * @return self
     */
    public function load($file)
    {
        $this->loadFile($this->getConfigFile($file));
        $merge = clone $this->_prototype;
        $merge->loadFile($file);
        $this->extend($merge);

        return $this;
    }

    /**
     * Get the file path to the config file.
     *
     * @param string $file
     *
     * @return string
     */
    public function getConfigFile($file)
    {
        $dir = Mage::getModuleDir('etc', 'Studioforty9_Carousel');

        return $dir . DS . $this->_formDir . DS . $file . '.xml';
    }

    /**
     * Get all fieldsets in the config file.
     *
     * @return array
     */
    public function getFieldsets()
    {
        return $this->getXpath('//form/fieldset');
    }

    /**
     * Get a fieldset in the config file by it's ID attribute.
     *
     * @param string $fieldsetId
     *
     * @return array
     */
    public function getFieldset($fieldsetId)
    {
        $nodes = $this->getXpath(sprintf('//form/fieldset[@id="%s"]', $fieldsetId));
        return $nodes[0];
    }

    /**
     * Get the translatable nodes for a given element.
     *
     * @param Varien_Simplexml_Element $element
     *
     * @return array
     */
    public function getTranslatableNodes(Varien_Simplexml_Element $element)
    {
        if ($translatable = $element->getAttribute('translate')) {
            return explode(' ', $translatable);
        }

        return array();
    }
}
