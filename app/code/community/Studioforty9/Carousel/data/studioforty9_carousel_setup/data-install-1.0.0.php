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

// -----

try {
    /**
     * Add Demo slider
     */
    $slider = array(
        'name' => 'Demo',
        'description' => 'Just a demo...',
        'status' => 1,
        'position' => 1,
        'design' => array(
            'fullscreen' => '0',
            'arrows' => 'true',
            'click' => '1',
            'swipe' => '1',
            'trackpad' => '1',
            'keyboard' => '1',
            'autoplay' => '4000',
            'stopautoplayontouch' => '1',
            'nav' => 'dots',
            'thumbfit' => 'cover',
            'navposition' => 'none',
            'width' => '100%',
            'minwidth' => '',
            'maxwidth' => '1200',
            'minheight' => '400',
            'maxheight' => '400',
            'transition' => 'slide',
            'clicktransition' => 'slide',
            'transitionduration' => '400'
        ),
    );

    $demo = Mage::getModel('studioforty9_carousel/slider')
        ->setData($slider)
        ->save();

    /**
     * Add slides to Demo slider.
     */
    $slides = array(
        array(
            'title' => 'Run with a hat!',
            'image_desktop' => 'girl-right.jpg',
            'image_tablet' => 'girl-right.jpg',
            'image_phone' => 'girl-right.jpg',
            'design' => array(
                'color' => '#ffffff',
                'theme' => 'dark',
                'position' => 'mid_left'
            ),
            'link' => '/great-craic-altogether',
            'link_text' => 'Shop Now',
            'status' => 1,
            'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a luctus enim. Phasellus eget porttitor ipsum. Mauris tristique est vel consectetur tincidunt.',
        ),
        array(
            'title' => "We've got loads of shoes!",
            'image_desktop' => 'shoes-center.jpg',
            'image_tablet' => 'shoes-center.jpg',
            'image_phone' => 'shoes-center.jpg',
            'design' => array(
                'color' => '#ffffff',
                'theme' => 'dark',
                'position' => 'mid_center'
            ),
            'link' => '/all-shoes',
            'link_text' => 'Shop Now',
            'status' => 1,
            'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a luctus enim. Phasellus eget porttitor ipsum. Mauris tristique est vel consectetur tincidunt.',
        ),
        array(
            'title' => 'Run with Friends',
            'image_desktop' => 'running-left.jpg',
            'image_tablet' => 'running-left.jpg',
            'image_phone' => 'running-left.jpg',
            'design' => array(
                'color' => '#ffffff',
                'theme' => 'light',
                'position' => 'mid_right'
            ),
            'link' => '/run-with-friends',
            'link_text' => 'Shop Now',
            'status' => 1,
            'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a luctus enim. Phasellus eget porttitor ipsum. Mauris tristique est vel consectetur tincidunt.',
        ),
        array(
            'title' => 'Buy stretchy shoes, stretch!!!',
            'image_desktop' => 'shoe-right.jpg',
            'image_tablet' => 'shoe-right.jpg',
            'image_phone' => 'shoe-right.jpg',
            'design' => array(
                'color' => '#ffffff',
                'theme' => 'dark',
                'position' => 'mid_left'
            ),
            'link' => '/stretchy',
            'link_text' => 'Shop Now',
            'status' => 1,
            'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a luctus enim. Phasellus eget porttitor ipsum. Mauris tristique est vel consectetur tincidunt.',
        ),
    );

    foreach ($slides as $index => $slide) {
        $model = Mage::getModel('studioforty9_carousel/slide')
            ->setData($slide)
            ->save();
        $model->syncSliders(array(
            $demo->getId() => array('position' => $index + 1)
        ));
    }

} catch (Exception $e) {
    Mage::logException($e);
}
