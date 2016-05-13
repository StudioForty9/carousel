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
 * Studioforty9_Carousel_Helper_Adminhtml
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Helper
 */
class Studioforty9_Carousel_Helper_Adminhtml extends Mage_Core_Helper_Abstract
{
    /**
     * Prepare an adminhtml form.
     *
     * @param string           $formId
     * @param Varien_Data_Form $form
     * @param callable         $mapperCallback
     *
     * @return mixed
     */
    public function prepareForm($formId, $form, $mapperCallback)
    {
        $model = $this->_getModel($formId);
        $request = new Varien_Object(Mage::app()->getRequest()->getPost());
        $config = Mage::getModel('studioforty9_carousel/config');
        $config->load($formId);

        foreach ($config->getFieldsets() as $set) {
            // Set up the fieldset
            $fieldsetId = $set->getAttribute('id');
            $legend = $this->__((string) $set->legend);
            $fieldset = $form->addFieldset($fieldsetId, array(
                'legend' => $legend
            ));

            $fieldset->addType('image', 'Studioforty9_Carousel_Block_Adminhtml_Slide_Helper_Image');

            // Set up the fields
            foreach ($set->fields->field as $field) {
                $name = (string) $field->name;
                $input = (string) $field->input;
                // Parse the data for the field
                $data = get_object_vars($field);
                $data = $this->_normalizeFieldData($data);
                $data['name'] = sprintf('%s[%s]', $fieldsetId, $name);
                $data = $this->_runHelpers($data);
                $data = $this->_translateStrings($data, $config->getTranslatableNodes($field));
                if (isset($data['options']) && 'select' === $input) {
                    $data = $this->_parseOptionsForSelect($data);
                }
                if (isset($data['options']) && 'multiselect' === $input) {
                    $data = $this->_parseOptionsForMultiselect($data);
                }

                $data = call_user_func_array($mapperCallback, array($data, $model, $request));

                unset($data['@attributes'], $data['input']);

                $fieldset->addField($name, $input, $data);
            }
        }

        return $fieldset;
    }

    /**
     * Extract and normalize field data.
     *
     * @param array $data
     *
     * @return array
     */
    protected function _normalizeFieldData($data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value) && $key !== 'value') {
                if ($value === 'false') {
                    $data[$key] = false;
                }
                if ($value === 'true') {
                    $data[$key] = true;
                }
                if (is_numeric($value)) {
                    if (false !== strpos($value, '.')) {
                        $data[$key] = (float) $value;
                    }
                    if (false === strpos($value, '.')) {
                        $data[$key] = (int) $value;
                    }
                }
            }
        }

        if (
            ('checkbox' === $data['input'] || 'radio' === $data['input']) &&
            (int) $data['value'] == 1
        ) {
            $data['checked'] = true;
        }

        $data['id'] = $data['name'];
        $data['title'] = $data['label'];

        return $data;
    }

    /**
     * Translate all nodes marked with the translate attribute.
     *
     * @param array $data
     * @param array $translatable
     *
     * @return array
     */
    protected function _translateStrings($data, $translatable)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $translatable)) {
                $data[$key] = $this->__($value);
            }
        }

        return $data;
    }

    /**
     * Parse options for a select input.
     *
     * @param array $data
     * @return array
     */
    protected function _parseOptionsForSelect($data)
    {
        $options = array();
        foreach ($data['options'] as $index => $option) {
            $options[(string) $option->value] = (string) $option->label;
        }
        $data['options'] = $options;
        return $data;
    }

    /**
     * Multiselect inputs require a different array structure.
     *
     * @param array $data
     * @return array
     */
    protected function _parseOptionsForMultiselect($data)
    {
        $options = array();
        foreach ($data['options'] as $index => $option) {
            $data['options'][] = array(
                'label' => (string) $option->label,
                'value' => (string) $option->value,
            );
        }
        $data['options'] = $options;

        return $data;
    }

    /**
     * Look for any helper attributes on field nodes and run the method.
     *
     * @param array $data
     * @return array
     */
    protected function _runHelpers($data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $child) {
                if (!$child instanceof Varien_Simplexml_Element) continue;
                if (empty($helper = $child->getAttribute('helper'))) continue;
                $data[$key] = $this->_runHelperMethod($helper);
            }
        }

        return $data;
    }

    /**
     * Convert the helper string to a class and method, create the instance
     * object and run the method.
     *
     * @param string $helper
     * @return mixed
     */
    private function _runHelperMethod($helper)
    {
        list($class, $method) = explode('::', $helper);

        try {
            $helperClass = Mage::helper($class);
            $value = $helperClass->$method();
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $value;
    }



    /**
     * Retrieve the existing model for pre-populating the form fields.
     * For a new entry this will return an null model object.
     *
     * @return Studioforty9_Carousel_Model_Slider|Studioforty9_Carousel_Model_Slide
     */
    protected function _getModel($type)
    {
        $model = Mage::registry('current_' . $type);
        if (!$model) {
            $model = Mage::getModel('studioforty9_carousel/' . $type);
        }

        return $model;
    }
}
