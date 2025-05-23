<?php

namespace Jonnysp;

use Contao\Module;
use Contao\System;
use Contao\BackendTemplate;
use Contao\Config;
use Contao\Input;
use Contao\StringUtil;
use Contao\FilesModel;
use Contao\CoreBundle\Exception\InternalServerErrorException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;

class ModuleJobReader extends Module
{

	protected $strTemplate = 'mod_jobreader';

	public function generate()
	{

		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->title = $this->name;
            return $objTemplate->parse();
        }


		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && isset($_GET['auto_item']) && Config::get('useAutoItem'))
		{
			Input::setGet('items', Input::get('auto_item'));
		}

		// Return an empty string if "items" is not set (to combine list and reader on same page)
		if (!Input::get('items'))
		{
			return '';
		}

		if (empty($this->jobcategorie) || \is_array($this->jobcategorie))
		{
			throw new InternalServerErrorException('The Job reader ID ' . $this->id . ' has no categories specified.');
		}

		return parent::generate();
    }


	protected function compile()
	{
		global $objPage;

		if ($this->overviewPage)
		{
			$this->Template->referer = PageModel::findById($this->overviewPage)->getFrontendUrl();
			$this->Template->back = $this->customLabel ?: $GLOBALS['TL_LANG']['MSC']['jobOverview'];
		}
		else
		{
			$this->Template->referer = 'javascript:history.go(-1)';
			$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		}

		$objJob  = JobModel::findByIdOrAlias(Input::get('items'));

		if ($objJob === null)
		{
			throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
		}

		$responseContext = System::getContainer()->get('contao.routing.response_context_accessor')->getResponseContext();

		if ($responseContext && $responseContext->has(HtmlHeadBag::class))
		{
			$htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
			$htmlDecoder = System::getContainer()->get('contao.string.html_decoder');
			
			if ($objJob->title)
			{
				$htmlHeadBag->setTitle($objJob->title); // Already stored decoded
			}

			if ($objJob->shortdescription)
			{
				$htmlHeadBag->setMetaDescription($htmlDecoder->inputEncodedToPlainText($objJob->shortdescription));
			}

		}

		$JobImage = FilesModel::findByPk($objJob->image);
		$JobDownload = FilesModel::findbyPk($objJob->download);
		$Organization_logo = FilesModel::findbyPk($objJob->Organization_logo);

		$Job = array(
				"id" => $objJob->id,
				"pid" => $objJob->pid,
				"title" => isset($objJob->title) ? $objJob->title : '',
				"alias" => isset($objJob->alias) ? $objJob->alias : '',
				"shortdescription" => isset($objJob->shortdescription) ? $objJob->shortdescription : '',
				"description" => isset($objJob->description) ? $objJob->description : '',
				"published" => isset($objJob->published) ? $objJob->published : '',
				"directApply" => isset($objJob->directApply) ? $objJob->directApply : '',
				"image" =>  array(
					"path" => isset($JobImage->path) ? $JobImage->path  : '',
					"name" => isset($JobImage->name) ? $JobImage->name  : '',
					"extension" => isset($JobImage->extension) ? $JobImage->extension  : ''
					),
			"download"=>  array(
				"path" => isset($JobDownload->path) ? $JobDownload->path  : '',
				"name" => isset($JobDownload->name) ? $JobDownload->name  : '',
				"extension" => isset($JobDownload->extension) ? $JobDownload->extension : ''
					),
				"datePosted" => isset($objJob->datePosted) ? $objJob->datePosted : '',
				"validThrough" => isset($objJob->validThrough) ? $objJob->validThrough : '',
				"jobLocationType" => isset($objJob->jobLocationType) ? $objJob->jobLocationType : '',
				"employmentType" => isset($objJob->employmentType) ? $objJob->employmentType : '',
				"Organization_name" => isset($objJob->Organization_name) ? $objJob->Organization_name : '',
				"Organization_sameAs" => isset($objJob->Organization_sameAs) ? $objJob->Organization_sameAs : '',			
				"Organization_logo" =>  array(
					"path" => isset($Organization_logo->path) ? $Organization_logo->path  : '',
					"name" => isset($Organization_logo->name) ? $Organization_logo->name  : '',
					"extension" => isset($Organization_logo->extension) ? $Organization_logo->extension  : '',
					),
			"street" => isset($objJob->street) ? $objJob->street : '',
			"postalCode" => isset($objJob->postalCode) ? $objJob->postalCode : '',
			"Locality" => isset($objJob->Locality) ? $objJob->Locality : '',
			"Region" => isset($objJob->Region) ? $objJob->Region : '',
			"Country" => isset($objJob->Country) ? $objJob->Country : ''
			

		);

		$this->Template->Job = $Job;
		$this->Template->JobSchema  = $objJob->JobSchema();
    }

}

class_alias(ModuleJobReader::class, 'ModuleJobReader');