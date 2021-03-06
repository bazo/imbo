<?php
/**
 * This file is part of the Imbo package
 *
 * (c) Christer Edvartsen <cogo@starzinger.net>
 *
 * For the full copyright and license information, please view the LICENSE file that was
 * distributed with this source code.
 */

namespace ImboIntegrationTest\Image\Transformation;

use Imbo\Image\Transformation\Transverse,
    Imagick;

/**
 * @covers Imbo\Image\Transformation\Transverse
 * @group integration
 * @group transformations
 */
class TransverseTest extends TransformationTests {
    /**
     * {@inheritdoc}
     */
    protected function getTransformation() {
        return new Transverse();
    }

    /**
     * @covers Imbo\Image\Transformation\Transverse::transform
     */
    public function testCanTransformImage() {
        $image = $this->createMock('Imbo\Model\Image');
        $image->expects($this->once())->method('hasBeenTransformed')->with(true)->will($this->returnValue($image));

        $imagick = new Imagick();
        $imagick->readImageBlob(file_get_contents(FIXTURES_DIR . '/image.png'));

        $expectedWidth = $imagick->getImageHeight();
        $expectedHeight = $imagick->getImageWidth();

        $image->expects($this->once())->method('setWidth')->with($expectedWidth)->will($this->returnValue($image));
        $image->expects($this->once())->method('setHeight')->with($expectedHeight)->will($this->returnValue($image));

        $event = $this->createMock('Imbo\EventManager\Event');
        $event->expects($this->once())->method('getArgument')->with('image')->will($this->returnValue($image));

        $this->getTransformation()->setImagick($imagick)->transform($event);
    }
}
