<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Filter\Basic;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Point;

class Background implements FilterInterface
{
    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var null|int[]|BoxInterface
     */
    protected $size;

    /**
     * @var ColorInterface
     */
    protected $color;

    /**
     * Constructor
     *
     * @param ImagineInterface        $imagine
     * @param null|int[]|BoxInterface $size
     * @param ColorInterface          $color
     */
    public function __construct(ImagineInterface $imagine, ColorInterface $color, $size = null)
    {
        $this->imagine  = $imagine;
        $this->size     = $size;
        $this->color    = $color;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(ImageInterface $image)
    {
        if ($this->size) {
            if (!$this->size instanceof BoxInterface) {
                list($width, $height) = $this->size;
                $this->size = new Box($width, $height);
            }
            $topLeft = new Point(
                ($width - $image->getSize()->getWidth()) / 2,
                ($height - $image->getSize()->getHeight()) / 2
            );
        } else {
            $topLeft = new Point(0, 0);
            $this->size = $image->getSize();
        }

        $canvas = $this->imagine->create($this->size, $this->color);

        return $canvas->paste($image, $topLeft);
    }
}
