<?php

/*
 * This file is part of Contao Simple SVG Icons Bundle.
 *
 * (c) slashworks
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoSimpleSvgIconsBundle\Hook;

use Contao\FilesModel;
use Contao\ImagineSvg\Imagine;
use Contao\ImagineSvg\RelativeBoxInterface;
use Contao\ImagineSvg\UndefinedBoxInterface;
use Contao\Validator;
use Imagine\Image\Box;
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
        $insertTag = false;

        $tagParts = explode('::', $tag);

        // Not our insert tag.
        if ('svg' !== $tagParts[0]) {
            return false;
        }

        $svgId = ''; // Can contain a symbol id from an svg sprite or a uuid of an svg file.
        $params = array();

        // Process arguments, e. g. width, height, custom CSS class
        if (strpos($tagParts[1], '?') !== false) {
            $chunks = explode('?', urldecode($tagParts[1]), 2);
            $svgId = $chunks[0];
            $strSource = \StringUtil::decodeEntities($chunks[1]);
            $strSource = str_replace('[&]', '&', $strSource);
            $tempParams = explode('&', $strSource);

            foreach ($tempParams as $param) {
                list($key, $value) = explode('=', $param);
                $params[$key] = $value;
            }
        } else {
            $svgId = $tagParts[1];
        }

        // Differentiate between svg icon from svg sprite and inline svg from a file.
        if (Validator::isUuid($svgId)) {
            $insertTag = $this->replaceInline($svgId, $params);
        } else {
            $insertTag = $this->replaceDefault($svgId, $params);
        }

        return $insertTag;
    }

    /**
     * @param string $iconId
     * @param array  $params
     *
     * @return bool|string
     */
    protected function replaceDefault($iconId, $params = array())
    {
        $cssClass = 'svg-icon';
        $customClass = $params['class'] ?? NULL;
        $customId = $params['id'] ?? NULL;

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
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaosimplesvgicons/svg4everybody.min.js';
            // Place svg4everybody-call in the footer.
            $GLOBALS['TL_BODY'][] = '<script>svg4everybody();</script>';

            // Include symbol id as CSS class to make targeting specific icons easier.
            $cssClass .= ' ' . $iconId;

            if ($customClass) {
                $cssClass .= ' ' . $customClass;
            }

            $viewBox = SimpleSvgIcons::getViewboxForFileAndSymbol($path, $iconId);

            // The file hash is included in the href attribute of the use element to prevent caching errors after modifications in the SVG file.
            $fileHash = hash_file('md5', $svgFile['path']);

            $svg = sprintf('<svg %s class="%s" viewBox="%s"><use xlink:href="/%s?v=%s#%s"></use></svg>',
                ($customId) ? 'id="' . $customId . '"' : '',
                $cssClass,
                $viewBox,
                $path,
                $fileHash,
                $iconId
            );

            return $svg;
        }

        return false;
    }

    /**
     * @param string $uuid
     * @param array  $params
     *
     * @return bool|string
     */
    protected function replaceInline($uuid, $params = array())
    {
        $svgFile = FilesModel::findByUuid($uuid);
        // The file model could not be found.
        if ($svgFile === null) {
            return false;
        }

        // The file does not exist.
        if (!file_exists(TL_ROOT . '/' . $svgFile->path)) {
            return false;
        }

        // Only consider svg files.
        if ($svgFile->extension !== 'svg') {
            return false;
        }

        $customId = $params['id'];
        $cssClass = 'svg-inline';
        if ($params['class']) {
            $cssClass .= ' ' . $params['class'];
        }

        $isResizable = true;
        $width = $params['width'];
        $height = $params['height'];
        $ratio = null;

        $imagine = new Imagine();
        $imagineSvg = $imagine->open($svgFile->path);
        $size = $imagineSvg->getSize();

        // We cannot resize an svg with an undefined size.
        if ($size instanceof UndefinedBoxInterface) {
            $isResizable = false;
        }

        if ($isResizable && ($width || $height)) {
            $ratio = $size->getHeight() / $size->getWidth();
            $resizeBox = null;

            if ($width && $height) {
                $resizeBox = new Box($width, $height);
            } else if ($width && !$height) {
                $resizeBox = new Box($width, $width * $ratio);
            } else if (!$width && $height) {
                $resizeBox = new Box($height / $ratio, $height);
            }

            $imagineSvg->resize($resizeBox);
        }

        $svgContent = $imagineSvg->get('svg');

        $svgXml = simplexml_load_string($svgContent);
        $xmlAttributes = $svgXml->attributes();

        if ($customId) {
            if (isset($xmlAttributes['id'])) {
                $xmlAttributes->id = $customId;
            } else {
                $xmlAttributes->addAttribute('id', $customId);
            }
        }

        if (isset($xmlAttributes['class'])) {
            $xmlAttributes->class = $cssClass;
        } else {
            $xmlAttributes->addAttribute('class', $cssClass);
        }

        $svgContent = preg_replace("/<\\?xml.*\\?>/",'',$svgXml->asXML(),1);

        return $svgContent;
    }

}
