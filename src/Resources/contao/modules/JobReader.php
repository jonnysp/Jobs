<?php

class ModuleJobReader extends Module
{

	protected $strTemplate = 'mod_jobreader';


	public function generate()
	{
        if (TL_MODE == 'BE')
		{
            $objTemplate = new BackendTemplate('be_wildcard');
            return $objTemplate->parse();
        }

		return parent::generate();
    }

	protected function compile()
	{

        $this->Template->jobs = '';


    }



}
