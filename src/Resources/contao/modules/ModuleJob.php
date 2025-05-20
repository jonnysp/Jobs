<?php

namespace Jonnysp;

use Contao\Frontend;
use Jonnysp\JobCategoriesModel;
use Jonnysp\JobModel;
use Contao\PageModel;
use Contao\Config;


class ModuleJob extends Frontend
{

	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{

		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();
		$time = time();

		// Get all categories
		$objJobCategorie = JobCategoriesModel::findAll();


		// Walk through each category
		if ($objJobCategorie !== null)
		{
			while ($objJobCategorie->next())
			{
				// Skip Jobs without target page
				if (!$objJobCategorie->jumpTo)
				{
					continue;
				}

				// Skip Jobs outside the root nodes
				if (!empty($arrRoot) && !\in_array($objJobCategorie->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objJobCategorie->jumpTo]))
				{
					$objParent = PageModel::findWithDetails($objJobCategorie->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}
                    
					// The target page has not been published (see #5520)
					if (!$objParent->published || ($objParent->start && $objParent->start > $time) || ($objParent->stop && $objParent->stop <= $time))
					{
						continue;
					}

	                if ($blnIsSitemap)
					{

						if ($objParent->protected)
						{
							continue;
						}



						if ($objParent->robots == 'noindex,nofollow')
						{
							continue;
						}



					}

					// Generate the URL
					$arrProcessed[$objJobCategorie->jumpTo] = $objParent->getAbsoluteUrl(Config::get('useAutoItem') ? '/%s' : '/items/%s');

				}

				$strUrl = $arrProcessed[$objJobCategorie->jumpTo];

				// Get the items
                $objItems = JobModel::findAll(
                    array('column' => array('pid=?','published=?'),
                        'value' => array($objJobCategorie->id , 1)
                    )
                );

				if ($objItems !== null)
				{
					while ($objItems->next())
					{
						if ($blnIsSitemap && $objItems->inSitemap != 1)
						{
							continue;
						}

						$arrPages[] = sprintf(preg_replace('/%(?!s)/', '%%', $strUrl), ($objItems->alias ?: $objItems->id));
					}
				}
			}
		}

		return $arrPages;
	}



}

class_alias(ModuleJob::class, 'ModuleJob');