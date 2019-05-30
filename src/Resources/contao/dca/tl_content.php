<?php

use \Slashworks\ContaoSimpleSvgIconsBundle\DataContainer\General;

$GLOBALS['TL_DCA']['tl_content']['palettes']['text'] = str_replace
(
    ',text',
    ',text,icon',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['text']
);

$GLOBALS['TL_DCA']['tl_content']['fields']['icon'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_content']['icon'],
    'inputType'        => 'svgiconselect',
    'options_callback' => array(General::class, 'getIcons'),
    'reference'        => &$GLOBALS['TL_LANG']['MSC']['icons'],
    'eval'             => array('includeBlankOption' => true),
    'sql'              => "varchar(64) NOT NULL default ''",
);
