<?php

namespace Jonnysp;


use Contao\Model;


class JobModel extends Model
{
    protected static $strTable = 'tl_job';


    public function JobSchema(){

        global $objPage;

        $out = '<script type="application/ld+json">{';	
        $out .='    "@context": "http://schema.org/",';
	    $out .='    "@type": "JobPosting",';

    if (isset($this->title)){
        $out .='    "title" : "'. $this->title .'",';
    };

    if (isset($this->shortdescription)){
        $out .='    "description" : "'. $this->shortdescription .'",';
    };

    if (isset($this->datePosted ) && (int)$this->datePosted > 0){
        $out .='    "datePosted" : "'.  date('Y-m-d', $this->datePosted)  .'",';
    };

    if (isset($this->validThrough) && (int)$this->validThrough > 0){
        $out .='    "validThrough" : "'. date('Y-m-d\TH:i:s', $this->validThrough).'",';
    };

    if (isset($this->employmentType)){
        $out .='    "employmentType" : "'.  $this->employmentType .'",';
    };


        $out .= '	"hiringOrganization": {';

            $out .= '	"@type": "Organization",';
            if (isset($this->Organization_name)){
                $out .= '	"name": "'. $this->Organization_name .'",';
            };

            if (isset($this->Organization_sameAs)){
                $out .= '	"sameAs": "'. $this->Organization_sameAs .'",';
            };

            $logo = \FilesModel::findbyPk($this->Organization_logo); 
            if (isset($logo->path)){
                $out .= '  "logo": "'. \Environment::get('base') . $logo->path. '"';
            };

        $out .= '	},';


        $out .= '	"jobLocation": {';
            $out .= '	"@type": "Place",';  
            $out .= '		"address": {';

            if (isset($this->street)){
                $out .= '	"streetAddress": "'. $this->street .'",';
            };
            if (isset($this->Locality)){
                $out .= '	"addressLocality": "'. $this->Locality .'",';
            };
            if (isset($this->Region)){
                $out .= '	"addressRegion": "'. $this->Region .'",';
            };

            if (isset($this->postalCode)){
                $out .= '	"postalCode": "'. $this->postalCode .'",';
            };
            if (isset($this->Country)){
                $out .= '	"addressCountry": "'. $this->Country .'"';
            };

            $out .= '	}';
        $out .= '	}';


        $out .= '}</script>';
        return $out;
    }




}


class_alias(JobModel::class, 'JobModel');