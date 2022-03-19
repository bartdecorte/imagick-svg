<?php
/**
 * Created by Bart Decorte
 * Date: 19/03/2022
 * Time: 11:32
 */

namespace BartDecorte\ImagickSvg\Tests;

class FillTest extends TestCase
{
    /** @test */
    public function it_can_render_element_fill()
    {
        $this->assertSvgEqualsPng('fill');
    }

    /** @test */
    public function it_can_render_group_fill()
    {
        $this->assertSvgEqualsPng('fill-group');
    }
}
