<?php

/*tl_anfragen * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */


use Contao\Backend;
use Contao\BackendUser;
use Contao\Config;
use Contao\CoreBundle\EventListener\Widget\HttpUrlListener;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\FrontendUser;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;

$GLOBALS['TL_DCA']['tl_anfragen'] = array
(
	
	// Config
	'config' => array
	(
		'dataContainer'               => DC_Table::class,
		'enableVersioning'            => true,
		'onload_callback' => array
        (
            array('tl_anfragen', 'changePalette')
        ), 
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index',
				'name,published' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'flag'                    => 1, // ASC sorting by default
			'panelLayout'             => 'filter;sort,search,limit',
	
		),
		'label' => array
		(
			'fields'                  => array('name'),
			
		),
		'global_operations' => array
		(
			'all' => array
			(
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'delete' => array
			(
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
			),
			'cut' => array
			(
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg'
			),
			'toggle' => array
			(
				'href'                => 'act=toggle&amp;field=published',
				'icon'                => 'visible.svg',
				'reverse'             => true
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name, licensePriceType, licenseGP1, licenseGP2, licenseGP3, licenseGP4,',						

		
	),




	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment",
			'search'                  => true
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),

		'name' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w100'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'subname' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w100'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),


		'licensePriceType' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licensePriceType'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'select',
			'options'                   => ['single', 'using'],
			'reference'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['licensePriceType_options'],
			'eval'                      => ['submitOnChange'=>true, 'tl_class'=>'clr w50', 'includeBlankOption'],
			'sql'                       => "varchar(6) NOT NULL default ''",
		),
		'licensePriceTax'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licensePriceTax'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['mandatory'=>true, 'rgxp'=>'natural', 'tl_class'=>'w100 clr', 'maxlen'=>3],
			'sql'                       => "varchar(10) NOT NULL default ''",
		),
		
		'licensePriceSingle'	=> array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licensePriceSingle'],
			'filter'                    => true,
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'w50', 'rgxp'=>'alphanum', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "varchar(64) NOT NULL default ''",
		),
		'licensePriceSingleSupport'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licensePriceSingleSupport'],
			'filter'                    => true,
			'inputType'                 => 'inputUnit',
			'options'                   => ['value','percent'],
			'reference'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['licensePriceSingleSupport_options'],
			'eval'                      => ['tl_class'=>'w50', 'rgxp'=>'alphanum', 'mandatory'=>false, 'maxlen'=>8],
			'sql'                       => "varchar(64) NOT NULL default ''",
		),
		'licenseGP1'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGP1'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'checkbox',
			'eval'                      => ['submitOnChange'=>true, 'tl_class'=>'w50 m12'],
			'sql'                       => "varchar(1) NOT NULL default ''",
		),
		'licenseGP1Type'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'select',
			'options'                   => ['single', 'calc'],
			'reference'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType_options'],
			'eval'                      => ['tl_class'=>'clr w50','mandatory'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true],
			'sql'                       => "varchar(6) NOT NULL default ''",
		),
		'licenseGP1Unit'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPUnit'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'default'                   => 1,
			'eval'                      => ['mandatory'=>false, 'maxlen'=>12, 'rgxp'=>'natural', 'tl_class'=>'w50'],
			'sql'                       => "varchar(12) NOT NULL default '1'",
		),
		'licenseGP1MinValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMinValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'clr w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP1MaxValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMaxValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP1Price'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPPrice'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'eval'                      => ['mandatory'=>false, 'maxlen'=>8, 'rgxp'=>'alphanum', 'tl_class'=>'w50'],
			'sql'                       => "varchar(8) NOT NULL default ''",
		),
		'licenseGP2'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGP2'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'checkbox',
			'eval'                      => ['submitOnChange'=>true, 'tl_class'=>'clr w50 m12'],
			'sql'                       => "varchar(1) NOT NULL default ''",
		),
		'licenseGP2Type'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'select',
			'options'                   => ['single', 'calc'],
			'reference'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType_options'],
			'eval'                      => ['tl_class'=>'clr w50','mandatory'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true],
			'sql'                       => "varchar(6) NOT NULL default ''",
		),
		'licenseGP2Unit'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPUnit'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'default'                   => 1,
			'eval'                      => ['mandatory'=>false, 'maxlen'=>12, 'rgxp'=>'natural', 'tl_class'=>'w50'],
			'sql'                       => "varchar(12) NOT NULL default '1'",
		),
		'licenseGP2MinValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMinValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'clr w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP2MaxValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMaxValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP2Price'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPPrice'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'eval'                      => ['mandatory'=>false, 'maxlen'=>8, 'rgxp'=>'alphanum', 'tl_class'=>'w50'],
			'sql'                       => "varchar(8) NOT NULL default ''",
		),
		'licenseGP3'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGP3'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'checkbox',
			'eval'                      => ['submitOnChange'=>true, 'tl_class'=>'clr w50 m12'],
			'sql'                       => "varchar(1) NOT NULL default ''",
		),
		'licenseGP3Type'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'select',
			'options'                   => ['single', 'calc'],
			'reference'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType_options'],
			'eval'                      => ['tl_class'=>'clr w50','mandatory'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true],
			'sql'                       => "varchar(6) NOT NULL default ''",
		),
		'licenseGP3Unit'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPUnit'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'default'                   => 1,
			'eval'                      => ['mandatory'=>false, 'maxlen'=>12, 'rgxp'=>'natural', 'tl_class'=>'w50'],
			'sql'                       => "varchar(12) NOT NULL default '1'",
		),
		'licenseGP3MinValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMinValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'clr w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP3MaxValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMaxValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP3Price'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPPrice'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'eval'                      => ['mandatory'=>false, 'maxlen'=>8, 'rgxp'=>'alphanum', 'tl_class'=>'w50'],
			'sql'                       => "varchar(8) NOT NULL default ''",
		),
		'licenseGP4'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGP4'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'checkbox',
			'eval'                      => ['submitOnChange'=>true, 'tl_class'=>'clr w50 m12'],
			'sql'                       => "varchar(1) NOT NULL default ''",
		),
		'licenseGP4Type'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType'],
			'exclude'                   => true,
			'filter'                    => true,
			'inputType'                 => 'select',
			'options'                   => ['single', 'calc'],
			'reference'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPType_options'],
			'eval'                      => ['tl_class'=>'clr w50','mandatory'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true],
			'sql'                       => "varchar(6) NOT NULL default ''",
		),
		'licenseGP4Unit'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPUnit'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'default'                   => 1,
			'eval'                      => ['mandatory'=>false, 'maxlen'=>12, 'rgxp'=>'natural', 'tl_class'=>'w50'],
			'sql'                       => "varchar(12) NOT NULL default '1'",
		),
		'licenseGP4MinValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMinValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'clr w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP4MaxValue'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPMaxValue'],
			'exclude'                   => true,
			'inputType'                 => 'text',
			'eval'                      => ['tl_class'=>'w50', 'rgxp'=>'natural', 'mandatory'=>true, 'maxlen'=>8],
			'sql'                       => "int(8) NOT NULL default '0'",
		),
		'licenseGP4Price'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPPrice'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'text',
			'eval'                      => ['mandatory'=>false, 'maxlen'=>8, 'rgxp'=>'alphanum', 'tl_class'=>'w50'],
			'sql'                       => "varchar(8) NOT NULL default ''",
		),
		'licenseGPInfoText'=> array 
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_anfragen']['licenseGPInfoText'],
			'exclude'                   => true,
			'search'                    => true,
			'inputType'                 => 'textarea',
			'eval'                      => ['mandatory'=>false, 'tl_class'=>'clr long', 'rte'=>'tinyMCE'],
			'sql'                       => "varchar(255) NOT NULL default ''",
		),



		'image' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_anfragen']['image'],
			'inputType'  => 'fileTree',                   // Der Eingabetyp des Feldes ist Filetree
			'eval'       => array(
				
				'fieldType' => 'radio',                   // Der Feldtyp des Filetrees (kann auch 'checkbox' sein)
				'filesOnly' => true,                      // Nur Dateien, keine Ordner auswählen
				'extensions' => 'svg,jpg,jpeg,png',   // Erlaubte Datei-Erweiterungen
				'tl_class'  => 'w100 wizard',              // Contao-Backend CSS-Klasse für die Breite des Feldes und Wizard-Modus
			),
			'sql'        => "blob NULL",                  
		),


		'software_teast' => array
		(
			'inputType'  => 'textarea',                   // Der Eingabetyp des Feldes ist Textarea
			'eval'       => array(
				
				             // Aktiviert TinyMCE
				'tl_class'    => 'clr long',              // Contao-Backend CSS-Klasse für die Breite des Feldes
			),
			'sql'        => "text NULL", 
		),

		
		'software_description' => array
		(
			'inputType'  => 'textarea',                   // Der Eingabetyp des Feldes ist Textarea
			'eval'       => array(
				
				'rte'         => 'tinyMCE',                // Aktiviert TinyMCE
				'tl_class'    => 'clr long',              // Contao-Backend CSS-Klasse für die Breite des Feldes
			),
			'sql'        => "text NULL", 
		),


		'alias' => array
		(	
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>255, 'tl_class'=>'w100'),
			'save_callback' => array
			(
				array('tl_anfragen', 'generateAlias')
			),
			'sql'                     => "varchar(255) BINARY NOT NULL default ''"
		),
		'published' => array
        (	
			'label'					  => array('unveröffentlicht'),
            'exclude'                 => true,
            'filter'                  => true,
			'toggle'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        )
	)
);



class tl_anfragen extends Backend
{

	    /**
     * Import the back end user object
     */
    public function __construct()
    {
            parent::__construct();
            $this->import('BackendUser', 'User');
    }
    

/**
	 * Auto-generate the news alias if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$aliasExists = function (string $alias) use ($dc): bool
		{
			return $this->Database->prepare("SELECT id FROM tl_anfragen WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
		};

		// Generate alias if there is none
		if (!$varValue)
		{
			$varValue = System::getContainer()->get('contao.slug')->generate($dc->activeRecord->name);
		}
		elseif (preg_match('/^[1-9]\d*$/', $varValue))
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
		}
		elseif ($aliasExists($varValue))
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		return $varValue;
	}
	
	
	public function changePalette(DataContainer $dc)
    {
		
		
		$re = $dc->id;

		
		if (!empty($re)) 
		{
			$record = Database::getInstance()->prepare("SELECT id,licensePriceType FROM tl_anfragen WHERE id = $dc->id")->execute()->fetchAllAssoc();

			if ($record['0']['licensePriceType'] == 'using') {
				$GLOBALS['TL_DCA']['tl_anfragen']['palettes']['default'] = 'name,alias,image,software_teast,software_description,licensePriceTax,{licenseGP_legend},licenseGP1,licenseGP1Type,licenseGP1Unit,licenseGP1MinValue,licenseGP1MaxValue,licenseGP1PriceCalc,licenseGP1PriceBlock,licenseGP1Price,
				licenseGP2,licenseGP2Type,licenseGP2Unit,licenseGP2MinValue,licenseGP2MaxValue,licenseGP2PriceCalc,licenseGP2PriceBlock,licenseGP2Price,
				licenseGP3,licenseGP3Type,licenseGP3Unit,licenseGP3MinValue,licenseGP3MaxValue,licenseGP3PriceCalc,licenseGP3PriceBlock,licenseGP3Price,
				licenseGP4,licenseGP4Type,licenseGP4Unit,licenseGP4MinValue,licenseGP4MaxValue,licenseGP4PriceCalc,licenseGP4PriceBlock,licenseGP4Price,
				licenseGPInfoText;';
			}
			else {
				$GLOBALS['TL_DCA']['tl_anfragen']['palettes']['default'] = 'name,alias,image,software_teast,software_description,licensePriceType;{licensePriceSingle_legend},licensePriceTax,licensePriceSingle,licensePriceSingleSupport,licensePriceSingleTax';
			}
		}   
    }


	public function generateLabel($row, $label)
	{
		


		
	}


	
}
