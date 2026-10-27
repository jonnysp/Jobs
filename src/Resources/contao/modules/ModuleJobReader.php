<?php

namespace Jonnysp\Jobs\Module;

use Contao\Module;
use Contao\System;
use Contao\BackendTemplate;
use Contao\Config;
use Contao\Input;
use Contao\StringUtil;
use Contao\FilesModel;
use Contao\CoreBundle\Exception\InternalServerErrorException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Environment;

use Jonnysp\Jobs\Model\JobModel;
use Jonnysp\Jobs\Model\JobCategoriesModel;


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

		if (Input::get('auto_item') === null)
        {
            return;
        }
		
		return parent::generate();
    }


	protected function compile()
	{
		global $objPage;

		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$request = System::getContainer()->get('request_stack')->getCurrentRequest();
		$item = $request->attributes->get('auto_item') ?? $request->query->get('items');

		$objJob = JobModel::findByIdOrAlias($item);
					
		if ($objJob === null || $objJob->published  <>  1  )
		{
			throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
		}

		$responseContext = System::getContainer()
			->get('contao.routing.response_context_accessor')
			->getResponseContext();

		if (null !== $responseContext && $responseContext->has(HtmlHeadBag::class))
		{
			$htmlHeadBag = $responseContext->get(HtmlHeadBag::class);

			if ($objJob->title)
			{
				$htmlHeadBag->setTitle($objJob->title);
			}

			if ($objJob->shortdescription)
			{
				$htmlHeadBag->setMetaDescription(
					strip_tags($objJob->shortdescription)
				);
			}
		}

		$JobImage = null;
		if (!empty($objJob->image)) {
			$JobImage = FilesModel::findByPk($objJob->image);
		}
		

		$JobDownload = null;
		if (!empty($objJob->download)) {
			$JobDownload = FilesModel::findByPk($objJob->download);
		}
	
		$Organization_logo = null;
		if (!empty($objJob->Organization_logo)) {
			$Organization_logo = FilesModel::findByPk($objJob->Organization_logo);
		}


		$Job = array(
				"id" => $objJob->id,
				"pid" => $objJob->pid,
				"title" => $objJob->title ?? null,
				"alias" => $objJob->alias ?? null,
				"shortdescription" => $objJob->shortdescription ?? null,
				"description" => $objJob->description ?? null,
				"published" => $objJob->published ?? null,
				"directApply" => $objJob->directApply ?? null,
				"image" =>  array(
					"path" => $JobImage->path  ?? null,
					"name" => $JobImage->name  ?? null,
					"extension" => $JobImage->extension ?? null
					),
			"download"=>  array(
				"path" => $JobDownload->path ?? null,
				"name" => $JobDownload->name ?? null,
				"extension" => $JobDownload->extension ?? null
					),
				"datePosted" => $objJob->datePosted ?? null,
				"validThrough" => $objJob->validThrough?? null,
				"jobLocationType" => $objJob->jobLocationType ?? null,
				"employmentType" => $objJob->employmentType ?? null,
				"Organization_name" => $objJob->Organization_name ?? null,
				"Organization_sameAs" => $objJob->Organization_sameAs ?? null,			
				"Organization_logo" =>  array(
					"path" => $Organization_logo->path  ?? null,
					"name" => $Organization_logo->name  ?? null,
					"extension" => $Organization_logo->extension  ?? null,
					),
			"street" => $objJob->street ?? null,
			"postalCode" => $objJob->postalCode ?? null,
			"Locality" => $objJob->Locality ?? null,
			"Region" => $objJob->Region ?? null,
			"Country" => $objJob->Country ?? null
			

		);

		$this->Template->Job = $Job;
		$this->Template->JobSchema = $objJob->JobSchema();

		return;
    }

}

