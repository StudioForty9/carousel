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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_carousel/carousel_slider'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Slider ID')
    // ALBUM NAME
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Slider Name')
    // DESCRIPTION
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
    ), 'Slider Description')
    // DESIGN
    ->addColumn('design', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
    ), 'Slider Design')
    // STATUS
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Enabled')
    // ORDER
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Order Position')
    // UPDATED AT
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Slider Modification Time')
    // CREATED AT
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Slider Creation Time')
    ->setComment('Carousel Slider Table');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_carousel/carousel_slide'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Slide ID')
    // TITLE
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Slide Title')
    // DESKTOP IMAGE
    ->addColumn('image_desktop', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Slide Desktop Image')
    // TABLET IMAGE
    ->addColumn('image_tablet', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Slide Tablet Image')
    // MOBILE IMAGE
    ->addColumn('image_phone', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Slide Phone Image')
    // DESIGN
    ->addColumn('design', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
    ), 'Slide Design')
    // LINK
    ->addColumn('link', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Slide Link')
    // LINK TEXT
    ->addColumn('link_text', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Slide Link Text')
    // SUMMARY
    ->addColumn('summary', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
    ), 'Slide Summary')
    // Status
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        'nullable' => false,
        'default'=> 0
    ), 'Slide Status')
    // UPDATED AT
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        
    ), 'Slide Modification Time')
    // CREATED AT
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        
    ), 'Slide Creation Time')
    ->setComment('Carousel Slide Table');
$installer->getConnection()->createTable($table);

/**
 * Slide - Slider Join Table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_carousel/carousel_slider_slide'))
    ->addColumn('slide_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Slide ID')
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Slider ID')
    ->addIndex(
        $installer->getIdxName('studioforty9_carousel/carousel_slide', array('slide_id')),
        array('slide_id')
    )
    ->addIndex(
        $installer->getIdxName('studioforty9_carousel/carousel_slider', array('slider_id')),
        array('slider_id')
    )
    // ORDER
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Order Position')
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_carousel/carousel_slider_slide',
            'slide_id',
            'studioforty9_carousel/carousel_slide',
            'entity_id'
        ),
        'slide_id',
        $installer->getTable('studioforty9_carousel/carousel_slide'),
        'entity_id',
        null,
        null
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_carousel/carousel_slider_slide',
            'slider_id',
            'studioforty9_carousel/carousel_slider',
            'entity_id'
        ),
        'slider_id',
        $installer->getTable('studioforty9_carousel/carousel_slider'),
        'entity_id',
        null,
        null
    )
    ->setComment('Slide To Slider Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Slider Store Table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_carousel/carousel_slider_store'))
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Slider ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('studioforty9_carousel/carousel_slider_store', array('slider_id')),
        array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_carousel/carousel_slider_store',
            'slider_id',
            'studioforty9_carousel/carousel_slider',
            'entity_id'
        ),
        'slider_id',
        $installer->getTable('studioforty9_carousel/carousel_slider'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_carousel/carousel_slider_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Slider To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Slide Store Table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_carousel/carousel_slide_store'))
    ->addColumn('slide_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Slide ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('studioforty9_carousel/carousel_slide_store', array('slide_id')),
        array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_carousel/carousel_slide_store',
            'slide_id',
            'studioforty9_carousel/carousel_slide',
            'entity_id'
        ),
        'slide_id',
        $installer->getTable('studioforty9_carousel/carousel_slide'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_carousel/carousel_slide_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Slide To Store Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
