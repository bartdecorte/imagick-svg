<?php
/**
 * Created by Bart Decorte
 * Date: 18/03/2022
 * Time: 19:40
 */

namespace BartDecorte\ImagickSvg\Tests;

class ElementTest extends TestCase
{
    /** @test */
    public function it_can_render_circle()
    {
        $this->assertSvgEqualsPng('circle');
    }

    /** @test */
    public function it_can_render_rectangle()
    {
        $this->assertSvgEqualsPng('rectangle');
    }

    /** @test */
    public function it_can_render_ellipse()
    {
        $this->assertSvgEqualsPng('ellipse');
    }

    /** @test */
    public function it_can_render_polygon()
    {
        $this->assertSvgEqualsPng('polygon');
    }

    /** @test */
    public function it_can_render_path()
    {
        $this->assertSvgEqualsPng('path');
    }

    /** @test */
    public function it_can_render_group()
    {
        $this->assertSvgEqualsPng('path');
    }
}
