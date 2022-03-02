<?php
namespace app\helpers;

use Yii;

/**
 * Css helper class.
 */
class MenuHelper
{
    public static function getMenuItems()
    {
    	
    	
    	$menuItems = [];
		// if(!Yii::$app->user->isGuest){

		     $menuItems[] = [
		        'label' => '<i class="menu-icon fa fa-tachometer"></i><span class="menu-text"> Beranda </span>', 
		        'url' => ['site/index']];
		// }



	    // we do not need to display About and Contact pages to employ


	    if (Yii::$app->user->can('admin'))
	    {



	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-users"></i><span class="menu-text"> Perkuliahan </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[	           
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Nilai Per Semester',  
	                'url' => ['/tagihan/du'],	           
	            ],
     			[
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Transkrip',  
	                'url' => ['/tagihan/du-nonaktif'],	           
	            ],
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Jadwal Kuliah',  
	                'url' => ['/tagihan/instant'],	        
	            ],
	            
	           
	            
	        ]];

	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-users"></i><span class="menu-text"> Asrama </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[	           
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Kegiatan',  
	                'url' => ['/tagihan/du'],	           
	            ],
     			[
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Asrama',  
	                'url' => ['/tagihan/du-nonaktif'],	           
	            ],
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Pelanggaran',  
	                'url' => ['/tagihan/instant'],	        
	            ],
	            
	           
	            
	        ]];

	     }

	    if (Yii::$app->user->can('theCreator')){
	        //  $menuItems[] = ['label' => '<i class="menu-icon fa fa-building"></i><span class="menu-text"> Perusahaan </span><i class="caret"></i>', 'url' => '#',
	        //   'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //    'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //  'items'=>[
	        //     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['perusahaan/index']],
	        //     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),'url' => ['perusahaan/create']]
	        // ]];


	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-users"></i><span class="menu-text"> Users </span>', 'url' => ['/user/index']];
	    }

	    if(Yii::$app->user->isGuest){
	    	$menuItems[] = [
		        'label' => '<i class="menu-icon fa fa-key"></i><span class="menu-text"> Login </span>', 
		        'url' => ['site/login']];
			// $menuItems[] = ['label' => 'Login', 'url' => ['site/login']];   
		}
		return $menuItems;
    }
}