<?php
/**
 * Created by Bart Decorte
 * Date: 19/03/2022
 * Time: 11:41
 */

namespace BartDecorte\ImagickSvg\Tests;

class TransformTest extends TestCase
{
    /** @test */
    public function it_can_translate_element_horizontally()
    {
        $this->assertSvgEqualsPng('transform-translate-horizontally');
    }

    /** @test */
    public function it_can_translate_element_vertically()
    {
        $this->assertSvgEqualsPng('transform-translate-vertically');
    }

    /** @test */
    public function it_can_translate_element_bidirectionally()
    {
        $this->assertSvgEqualsPng('transform-translate-bidirectionally');
    }

    /** @test */
    public function it_can_scale_element_horizontally()
    {
        $this->assertSvgEqualsPng('transform-scale-horizontally');
    }

    /** @test */
    public function it_can_scale_element_vertically()
    {
        $this->assertSvgEqualsPng('transform-scale-vertically');
    }

    /** @test */
    public function it_can_scale_element_bidirectionally()
    {
        $this->assertSvgEqualsPng('transform-scale-bidirectionally');
    }

    /** @test */
    public function it_can_rotate_element_clockwise_from_origin()
    {
        $this->assertSvgEqualsPng('transform-rotate-origin-clockwise');
    }

    /** @test */
    public function it_can_rotate_element_counter_clockwise_from_origin()
    {
        $this->assertSvgEqualsPng('transform-rotate-origin-counter-clockwise');
    }

    /** @test */
    public function it_can_rotate_element_clockwise_from_center()
    {
        $this->assertSvgEqualsPng('transform-rotate-center-clockwise');
    }

    /** @test */
    public function it_can_rotate_element_counter_clockwise_from_center()
    {
        $this->assertSvgEqualsPng('transform-rotate-center-counter-clockwise');
    }

    /** @test */
    public function it_can_skew_element_horizontally()
    {
        $this->assertSvgEqualsPng('transform-skew-horizontally');
    }

    /** @test */
    public function it_can_skew_element_vertically()
    {
        $this->assertSvgEqualsPng('transform-skew-vertically');
    }

    /** @test */
    public function it_can_matrix_transform_element()
    {
        $this->assertSvgEqualsPng('transform-matrix-translation');
    }
}
