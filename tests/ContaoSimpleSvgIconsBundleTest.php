<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle\Tests;

use Slashworks\ContaoSimpleSvgIconsBundle\ContaoSimpleSvgIconsBundle;
use PHPUnit\Framework\TestCase;

class ContaoSimpleSvgIconsBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoSimpleSvgIconsBundle();

        $this->assertInstanceOf('Slashworks\ContaoSimpleSvgIconsBundle\ContaoSimpleSvgIconsBundle', $bundle);
    }
}
