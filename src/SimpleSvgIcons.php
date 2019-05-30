<?php

/*
 * This file is part of Contao Simple SVG Icons Bundle.
 *
 * (c) slashworks
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle;

use Contao\FilesModel;
use Contao\LayoutModel;
use Contao\StringUtil;
use Contao\ThemeModel;

class SimpleSvgIcons
{
    /**
     * Get all SVG files that were selected in the active theme.
     *
     * @return array
     */
    public static function getSvgIconFiles()
    {
        global $objPage;
        $svgSymbols = [];

        // Get layout for current page
        $layoutId = $objPage->layout;
        $layout = LayoutModel::findById($layoutId);

        if (null === $layout) {
            return $svgSymbols;
        }

        // Get the theme, that the current layout is using.
        $theme = ThemeModel::findById($layout->pid);

        if (null === $theme) {
            return $svgSymbols;
        }

        // Get SVG files selected in the theme.
        $files = StringUtil::deserialize($theme->iconFiles);

        if (null === $files) {
            return $svgSymbols;
        }

        if (!isset($files[0])) {
            return $svgSymbols;
        }

        foreach ($files as $fileHash) {
            // Get object file.
            $fileModel = FilesModel::findByUuid($fileHash);

            if (!file_exists(TL_ROOT . '/' . $fileModel->path)) {
                continue;
            }

            if ('svg' !== $fileModel->extension) {
                continue;
            }

            $svgSymbols[] = self::getSvgSymbolsFromFile($fileModel);
        }

        return $svgSymbols;
    }

    /**
     * Generate an array of symbols that are used in the SVG file.
     *
     * @param FilesModel $fileModel
     *
     * @return array
     */
    public static function getSvgSymbolsFromFile($fileModel)
    {
        $filePath = TL_ROOT . '/' . $fileModel->path;
        $symbolIds = [];

        // Iterate over each <symbol>-Tag in the SVG file an get the value of the id-attribute.
        $xml = simplexml_load_file($filePath);
        foreach ($xml->symbol as $symbol) {
            $symbolIds[] = (string) $symbol->attributes()->id;
        }

        return [
            'path' => $filePath,
            'symbols' => $symbolIds,
        ];
    }

    /**
     * Get the viewbox attribute from an svg icon sprite file for a given symbol id.
     *
     * @param string $path
     * @param string $symbol
     *
     * @return string
     */
    public static function getViewboxForFileAndSymbol($path, $symbol)
    {
        $viewbox = '';

        $xml = simplexml_load_file($path);
        foreach ($xml->symbol as $xmlSymbol) {
            $symbolId = (string) $xmlSymbol->attributes()->id;

            if ($symbolId !== $symbol) {
                continue;
            }

            $viewbox = (string) $xmlSymbol->attributes()->viewBox;
            break;
        }

        return $viewbox;
    }
}
