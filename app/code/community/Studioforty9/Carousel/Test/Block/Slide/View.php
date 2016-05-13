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
 * Studioforty9_Carousel_Test_Block_Slide_View
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Test
 */
class Studioforty9_Carousel_Test_Block_Slide_View extends EcomDev_PHPUnit_Test_Case
{
    /** @var Studioforty9_Carousel_Block_Slide_View */
    protected $block;

    public function setUp()
    {
        $this->block = new Studioforty9_Carousel_Block_Slide_View();
    }

    public function test_block_has_slider_model_after_html()
    {
        $this->block->setTemplate('studioforty9/carousel/slide/view.phtml')->toHtml();
        $this->assertTrue($this->block->hasData('slide'));
        $this->assertTrue($this->block->hasData('slider'));
    }
}
