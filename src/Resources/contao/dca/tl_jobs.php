<?php
use Contao\DC_Table;
use Contao\Config;
use Contao\Backend;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\Database;


/**
 * Table tl_jobs
 */
$GLOBALS['TL_DCA']['tl_jobs'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               =>  DC_Table::class,
		'ptable'                      => 'tl_jobs_categories',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		 'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'headerFields'            => array('title'),
			'panelLayout'             => 'filter;search,limit'
		),
		
		'label' => array
		(
			'fields'                  => array('title', 'id'),
			'format'                  => '%s - [%s]'
		),

		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),

		'operations' => array
        (
        	'edit',
        	'copy',
        	'cut',
        	'delete',
        	'toggle' => array
        	(
        	        'href'                => 'act=toggle&field=published',
        	        'icon'                => 'visible.svg',
        	        'primary'             => true
        	),
        	'show'
        )

	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},published,inSitemap,title,alias,shortdescription,description,image,download,datePosted,validThrough,directApply,jobLocationType,employmentType;{organization_legend},Organization_logo,Organization_name,Organization_sameAs,street,postalCode,Locality,Region,Country;'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),

		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		'tstamp' => array
		(
			'sql'                     => ['type' => 'integer','notnull' => false, 'unsigned' => true,'default' => '0','fixed' => true]
		),

		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['toggle'],
			'toggle'                  => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true, 'tl_class'=>'w50'),
            'sql'                     => array('type'=>'boolean', 'default'=>false)
		),

		'inSitemap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['inSitemap'],
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array( 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default '1'",
		),

		'title' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['title'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),
		'alias' => array
		(
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'save_callback' 		  => array(	
				array('tl_jobs_callback', 'generateAlias')
			),
			'sql'                     => "varchar(255) BINARY NOT NULL default ''"
		),
		'shortdescription' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['shortdescription'],
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE','tl_class'=>'clr'),
			'sql'                     => ['type' => 'text','notnull' => false]
		),

		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['description'],
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE','tl_class'=>'clr'),
			'sql'                     => ['type' => 'text','notnull' => false]
		),

		'datePosted' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['datePosted'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true,'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),

		'validThrough' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['validThrough'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),

		'jobLocationType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['jobLocationType'],
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>false, 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),

		'employmentType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['employmentType'],
			'inputType'               => 'select',
			'options'                 => array('FULL_TIME','PART_TIME','TEMPORARY','INTERN','VOLUNTEER','OTHER'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_jobs'],
			'eval'                    => array( 'tl_class'=>'w100 clr', 'mandatory' => true),
			'sql'                     => "varchar(128) NOT NULL default 'FULL_TIME'"
		),

		'directApply' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['directApply'],
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>false, 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		
		'Organization_name' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['Organization_name'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),

		'Organization_sameAs' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['Organization_sameAs'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),

		'Organization_logo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['Organization_logo'],
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'%contao.image.valid_extensions%',  'tl_class'=>'w100 clr'),
			'sql'                     => ['type' => 'binary','notnull' => false,'length' => 16,'fixed' => true]
		),

		'street' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['street'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w100 clr'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),

		'Locality' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['Locality'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),

		'Region' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['Region'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),

		'postalCode' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['postalCode'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => '']
		),

		'Country' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['Country'],
			'search'              	=> true,
			'inputType'          	=> 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                   => ['type' => 'string', 'length' => 128, 'default' => 'DE']
		),

		'download' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_jobs']['download'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>false, 'tl_class'=>'w100 clr', 'extensions' => Config::get('allowedDownload')),
			'sql'                     => ['type' => 'binary','notnull' => false,'length' => 16,'fixed' => true]
		),

		'image' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jobs']['image'],
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'%contao.image.valid_extensions%',  'tl_class'=>'w100 clr'),
			'sql'                     => ['type' => 'binary','notnull' => false,'length' => 16,'fixed' => true]
		)

	)
);




class tl_jobs_callback extends Backend {

	public function generateAlias($value, DataContainer $dc): string
	{
		if (!$value) {
			$value = StringUtil::generateAlias($dc->activeRecord->title);
		}

		$exists = Database::getInstance()
			->prepare('SELECT id FROM tl_jobs WHERE alias=? AND id!=?')
			->execute($value, $dc->id);

		if ($exists->numRows > 0) {
			$value .= '-' . $dc->id;
		}

		return $value;
	}

}
