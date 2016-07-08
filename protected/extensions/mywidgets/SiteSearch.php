<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.03.2016
 * Time: 19:31
 */
class SiteSearch extends CWidget
{
    public function run()
    {
        $form = new SiteSearchForm();
        $this->render('sitesearcher', array('form'=>$form));
    }
}