<?php
namespace Jonnysp;

class JobCategoriesModel extends \Model
{
    protected static $strTable = 'tl_job_categories';
}

class_alias(JobCategoriesModel::class, 'JobCategoriesModel');