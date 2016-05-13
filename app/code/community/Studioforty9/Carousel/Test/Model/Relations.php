<?php
/**
 * Studioforty9 Carousel
 *
 * @category  Studioforty9
 * @package   Studioforty9_Carousel
 * @author    StudioForty9 <info@studioforty9.com>
 * @copyright 2016 StudioForty9 (http://www.studioforty9.com)
 * @license   https://github.com/studioforty9/carousel/blob/master/LICENCE BSD
 * @version   1.0.0
 * @link      https://github.com/studioforty9/carousel
 */

/**
 * Studioforty9_Carousel_Test_Model_Relations
 *
 * @category   Studioforty9
 * @package    Studioforty9_Carousel
 * @subpackage Test
 */
class Studioforty9_Carousel_Test_Model_Relations extends EcomDev_PHPUnit_Test_Case
{
    /** @var Studioforty9_Carousel_Model_Relations */
    protected $model;

    public function setUp()
    {
        $this->model = new Studioforty9_Carousel_Model_Relations();
    }

    /** @test */
    public function it_can_format_an_sql_update()
    {
        $this->model->init('slider_id', '1', 'slide_id', array('position'));
        $update = $this->model->formatUpdate(5, array('position' => 1));
        $this->assertEquals(
            "UPDATE studioforty9_carousel_slider_slide SET position=1 WHERE `slide_id`=5 AND `slider_id`=1",
            $update
        );
    }

    /** @test */
    public function it_can_format_an_sql_insert()
    {
        $this->model->init('slider_id', '1', 'slide_id', array('position'));
        $update = $this->model->formatInsert(5, array('position' => 1));
        $this->assertEquals(
            "INSERT INTO studioforty9_carousel_slider_slide SET position=1, `slide_id`=5, `slider_id`=1",
            $update
        );
    }

    /** @test */
    public function it_can_format_an_sql_delete()
    {
        $this->model->init('slider_id', '1', 'slide_id', array('position'));
        $update = $this->model->formatDelete(5, array('position' => 1));
        $this->assertEquals(
            "DELETE FROM studioforty9_carousel_slider_slide WHERE `slide_id`=5 AND `slider_id`=1",
            $update
        );
    }

    /** @test */
    public function it_can_retrieve_existing_relations()
    {
        $this->model->init('slider_id', 1, 'slide_id', array('position'));
        $existing = $this->model->getExistingRelations();

        foreach ($existing as $row) {
            $this->assertArrayHasKey('slide_id', $row);
            $this->assertArrayHasKey('slider_id', $row);
            $this->assertArrayHasKey('position', $row);
        }
    }

    /** @test */
    public function it_can_find_older_data_for_query_formatting()
    {
        $this->model->init('slider_id', 1, 'slide_id', array('position'));
        $existing = array(
            array('slide_id' => 1, 'slider_id' => 1, 'position' => 1),
            array('slide_id' => 2, 'slider_id' => 1, 'position' => 2),
        );
        $old = $this->model->formatOld($existing);

        foreach ($old as $id => $fields) {
            $this->assertArrayHasKey('position', $fields);
        }
    }

    /** @test */
    public function it_can_find_newer_data_for_query_formatting()
    {
        $entityIds = array(
            1 => array('position' => 2),
            2 => array('position' => 1)
        );
        $existing = array(
            array('slide_id' => 1, 'slider_id' => 1, 'position' => 1),
            array('slide_id' => 2, 'slider_id' => 1, 'position' => 2),
        );

        $this->model->init('slider_id', 1, 'slide_id', array('position'));

        $old = $this->model->formatOld($existing);
        $new = $this->model->formatNew($old, $entityIds);

        $this->assertArrayHasKey(1, $old);
        $this->assertArrayHasKey(1, $new);
        $this->assertArrayHasKey(1, $old);
        $this->assertArrayHasKey(2, $new);

        $this->assertArrayHasKey('position', $new[1]);
        $this->assertArrayHasKey('position', $new[2]);

        $this->assertEquals(2, $new[1]['position']);
        $this->assertEquals(1, $new[2]['position']);
    }
}
