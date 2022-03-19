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
    public function it_can_rotate_element_clockwise()
    {
        $this->assertSvgEqualsPng('transform-rotate-clockwise');
    }

    /** @test */
    public function it_can_rotate_element_counter_clockwise()
    {
        $this->assertSvgEqualsPng('transform-rotate-counter-clockwise');
    }
}
