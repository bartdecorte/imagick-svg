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
}
