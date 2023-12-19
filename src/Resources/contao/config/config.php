<?php


use Contao\Config;
use felixxxxx\drachenfelscrm\Resources\contao\models\SoftwareModel;
use felixxxxx\drachenfelscrm\Resources\contao\Module\ModuleSoftwareListe;
use felixxxxx\drachenfelscrm\Resources\contao\Module\ModuleSoftwareDetails;


$GLOBALS['BE_MOD']['softplan_software_module'] = array(
    'tl_anfragen' => array(
        'tables' => array('tl_anfragen'),
    ),
);


// $GLOBALS['TL_HOOKS']['getFrontendModule'][] = array('felixxxxx\drachenfelscrm\Controller\FrontendModule\SoftwareDetailsController', 'getFrontendModule');



$GLOBALS['FE_MOD']['softwaremodule'] = array 
(
    'software_list'  => ModuleSoftwareListe::class,
    'software_details'  => ModuleSoftwareDetails::class,
);



$GLOBALS['TL_MODELS']['tl_tl_anfragen'] = SoftwareModel::class;
