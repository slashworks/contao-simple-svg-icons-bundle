<?php

/*
 * This file is part of Contao Simple SVG Icons Bundle.
 *
 * (c) slashworks
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle\Tests;

use PHPUnit\Framework\TestCase;
use Slashworks\ContaoSimpleSvgIconsBundle\ContaoSimpleSvgIconsBundle;

class ContaoSimpleSvgIconsBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoSimpleSvgIconsBundle();

        $this->assertInstanceOf('Slashworks\ContaoSimpleSvgIconsBundle\ContaoSimpleSvgIconsBundle', $bundle);
    }
}
