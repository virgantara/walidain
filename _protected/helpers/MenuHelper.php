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


	    if (Yii::$app->user->can('ortu'))
	    {


	    	$menuItems[] = [
		        'label' => '<i class="menu-icon fa fa-users"></i><span class="menu-text"> Data Ananda </span>', 
		        'url' => ['customer/list']
		    ];

	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-university"></i><span class="menu-text"> Perkuliahan </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('ortu'),
	        'items'=>[	           
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Nilai Per Semester',  
	                'url' => ['/akademik/riwayat-khs'],	           
	            ],
     			[
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Transkrip',  
	                'url' => ['/akademik/transkrip'],	           
	            ],
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Jadwal Kuliah',  
	                'url' => ['/akademik/jadwal'],	        
	            ],
	            
	           
	            
	        ]];

	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-money"></i><span class="menu-text"> Pembayaran </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('ortu'),
	        'items'=>[	           
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Tagihan',  
	                'url' => ['/tagihan/index'],	           
	            ],
     			[
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Riwayat',  
	                'url' => ['/tagihan/riwayat'],	           
	            ],
	            
	           
	            
	        ]];

	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-home"></i><span class="menu-text"> Asrama </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('ortu'),
	        'items'=>[	           
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Kegiatan',  
	                'url' => ['/asrama/kegiatan'],	           
	            ],
     			[
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Asrama',  
	                'url' => ['/asrama/kesantrian'],	           
	            ],
	            
	            // [
	            // 	'label' => '<i class="menu-icon fa fa-caret-right"></i>Pelanggaran',  
	            //     'url' => ['/asrama/pelanggaran'],	        
	            // ],
	            
	           
	            
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