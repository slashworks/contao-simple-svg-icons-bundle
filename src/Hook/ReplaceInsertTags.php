<?php

/*
 * This file is part of Contao Simple SVG Icons Bundle.
 *
 * (c) slashworks
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle\Hook;

use Slashworks\ContaoSimpleSvgIconsBundle\SimpleSvgIcons;

class ReplaceInsertTags
{
    /**
     * Custom replaceInsertTags Hook to recognize insert tag for SVG icons.
     *
     * @param mixed $tag
     *
     * @return bool|string
     */
    public function replaceSvgInsertTags($tag)
    {
        $tagParts = explode('::', $tag);

        // Not our inserttag.
        if ('svg' !== $tagParts[0]) {
            return false;
        }

        // The icon id is missing.
        if (!isset($tagParts[1])) {
            return false;
        }

        $iconId = $tagParts[1];
        $cssClass = 'svg-icon';
        $customClass = '';

        if (isset($tagParts[2])) {
            $customClass = $tagParts[2];
        }

        // Get all selected SVG files.
        $svgFiles = SimpleSvgIcons::getSvgIconFiles();

        // Return if there are no selected SVG files.
        if (empty($svgFiles)) {
            return false;
        }

        foreach ($svgFiles as $svgFile) {
            // Icon id could not be found in the selected svg icon file.
            if (!\in_array($iconId, $svgFile['symbols'], true)) {
                continue;
            }

            $path = $svgFile['path'];

            // Add svg4everybody library
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/slashworkssimplesvgicons/svg4everybody.min.js';
            // Place svg4everybody-call in the footer.
            $GLOBALS['TL_BODY'][] = '<script>svg4everybody();</script>';

            // Include symbol id as CSS class to make targeting specific icons easier.
            $cssClass .= ' '.$iconId;

            if ($customClass) {
                $cssClass .= ' '.$customClass;
            }

            $viewbox = SimpleSvgIcons::getViewboxForFileAndSymbol($path, $iconId);

            // The file hash is included in the href attribute of the use element to prevent caching errors after modifications in the SVG file.
            $filehash = hash_file('md5', $svgFile['path']);

            $svg = sprintf('<svg class="%s" viewbox="%s"><use xlink:href="/%s?v=%s#%s"></use></svg>',
                $cssClass,
                $viewbox,
                $path,
                $filehash,
                $iconId
            );

            return $svg;
        }

        return false;
    }
}
