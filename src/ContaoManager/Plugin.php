<?php

/*
 * This file is part of Contao Simple SVG Icons Bundle.
 *
 * (c) slashworks
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Slashworks\ContaoSimpleSvgIconsBundle\ContaoSimpleSvgIconsBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoSimpleSvgIconsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
