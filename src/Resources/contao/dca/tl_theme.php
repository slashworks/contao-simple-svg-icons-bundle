<?php

$GLOBALS['TL_DCA']['tl_theme']['palettes']['default'] = str_replace
(
    '{vars_legend',
    '{simplesvgicons_legend},iconFiles;{vars_legend',
    $GLOBALS['TL_DCA']['tl_theme']['palettes']['default']
);

$GLOBALS['TL_DCA']['tl_theme']['fields']['iconFiles'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['iconFiles'],
    'inputType' => 'fileTree',
    'eval'      => array('multiple' => true, 'fieldType' => 'checkbox', 'files' => true, 'isGallery' => true),
    'sql'       => "blob NULL",
);
