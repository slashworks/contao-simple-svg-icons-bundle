<?php

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
        $svgSymbols = array();

        // Get layout for current page
        $layoutId = $objPage->layout;
        $layout = LayoutModel::findById($layoutId);

        if ($layout === null) {
            return $svgSymbols;
        }

        // Get the theme, that the current layout is using.
        $theme = ThemeModel::findById($layout->pid);

        if ($theme === null) {
            return $svgSymbols;
        }

        // Get SVG files selected in the theme.
        $files = StringUtil::deserialize($theme->iconFiles);

        if ($files === null) {
            return $svgSymbols;
        }

        if (!isset($files[0])) {
            return $svgSymbols;
        }

        foreach ($files as $fileHash) {
            // Get object file.
            $fileModel = FilesModel::findByUuid($fileHash);

            if (!file_exists($fileModel->path)) {
                continue;
            }

            if ($fileModel->extension !== 'svg') {
                continue;
            }

            $svgSymbols[] = self::getSvgSymbolsFromFile($fileModel);
        }

        return $svgSymbols;
    }

    /**
     * Generate an array of symbols that are used in the SVG file.
     *
     * @param $oFile
     *
     * @return array
     */
    public static function getSvgSymbolsFromFile($fileModel)
    {
        $filePath = $fileModel->path;
        $symbolIds = array();

        // Iterate over each <symbol>-Tag in the SVG file an get the value of the id-attribute.
        $xml = simplexml_load_file($filePath);
        foreach ($xml->symbol as $symbol) {
            $symbolIds[] = (string)$symbol->attributes()->id;
        }

        return array
        (
            'path'    => $filePath,
            'symbols' => $symbolIds,
        );
    }

    public static function getViewboxForFileAndSymbol($path, $symbol)
    {
        $viewbox = '';

        $xml = simplexml_load_file($path);
        foreach ($xml->symbol as $xmlSymbol) {
            $symbolId = (string)$xmlSymbol->attributes()->id;

            if ($symbolId !== $symbol) {
                continue;
            }

            $viewbox = (string)$xmlSymbol->attributes()->viewBox;
            break;
        }

        return $viewbox;
    }
}
