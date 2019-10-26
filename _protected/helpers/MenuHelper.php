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
    	
    	$userRole = Yii::$app->user->identity->access_role;
        $menuItems = [];
		if(!Yii::$app->user->isGuest){

		     $menuItems[] = [
		        'label' => '<i class="menu-icon fa fa-tachometer"></i><span class="menu-text"> Beranda </span>', 
		        'url' => ['site/index']];
		}



	    // we do not need to display About and Contact pages to employ
	    $is_non_gudang = [
	    	Yii::$app->user->can('operatorCabang'),
	    	Yii::$app->user->can('operatorUnit'),
	    	Yii::$app->user->can('gudang')
	    ];
	    


	    if (Yii::$app->user->can('admin'))
	    {
	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-book"></i><span class="menu-text"> Tagihan </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[	           
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Bulanan',  
	                'url' => ['/tagihan/bulanan'],	           
	            ],
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Instant Tagihan',  
	                'url' => ['/tagihan/instant'],	        
	            ],
	            ['label' => '<hr style="padding:0px;margin:0px">'],
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i><i class="fa fa-search"></i>&nbsp;Cari Tagihan',  
	                'url' => ['/tagihan/index'],	        
	            ],
	        ]];

	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-book"></i><span class="menu-text"> Transaksi </span>', 'url' => ['/transaksi'],
	       ];
	       
	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-book"></i><span class="menu-text"> Laporan </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[
	           	[
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i> Laporan Pembayaran',  
	                'url' => ['/laporan/pembayaran'],	         
	            ],

	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i> Laporan Tunggakan',  
	                'url' => ['/laporan/tunggakan'],	         
	            ],
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i> Laporan Rekapitulasi Tunggakan',  
	                'url' => ['/laporan/rekap-tunggakan'],	         
	            ],
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i> Laporan Transaksi',  
	                'url' => ['/laporan/transaksi'],	         
	            ],
	           
	            
	        ]];
	    }

	     $acl = [
			Yii::$app->user->can('gudang'),
			Yii::$app->user->can('distributor'),
			Yii::$app->user->can('operatorCabang'),
			Yii::$app->user->can('operatorUnit')
		];

	   
	    // display Users to admin+ roles
	    if (Yii::$app->user->can('admin') || Yii::$app->user->can('admSalesCab') || Yii::$app->user->can('adminSpbu') || Yii::$app->user->can('gudang')){

	        $menuItems[] = ['label' =>'<i class="menu-icon fa fa-book"></i><span class="menu-text"> Master </span><i class="caret"></i>', 'url' => '#',
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        'items'=>[
	        	[
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Kategori Komponen <b class="arrow fa fa-angle-down"></b>',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['#'],
	                 'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	                'items' => [

	                     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['kategori/index']],
	                     [
	                        'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),
	                        'visible' => Yii::$app->user->can('admin'),
	                        'url' => ['kategori/create']]
	                ],
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Komponen Biaya <b class="arrow fa fa-angle-down"></b>',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['#'],
	                 'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	                'items' => [

	                     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['komponen-biaya/index']],
	                     [
	                        'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),
	                        'visible' => Yii::$app->user->can('admin'),
	                        'url' => ['komponen-biaya/create']]
	                ],
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Customer <b class="arrow fa fa-angle-down"></b>',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['#'],
	                 'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	                'items' => [

	                     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['customer/index']],
	                     [
	                        'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),
	                        'visible' => Yii::$app->user->can('theCreator'),
	                        'url' => ['customer/create']]
	                ],
	            ],
	           	[
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Tahun <b class="arrow fa fa-angle-down"></b>',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('theCreator'),
	                'url' => ['#'],
	                 'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	                'items' => [

	                     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['tahun/index']],
	                     [
	                        'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),
	                      
	                        'url' => ['tahun/create']]
	                ],
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Batas Pembayaran',  
	                'visible' => Yii::$app->user->can('theCreator'),
	                'url' => ['batas-pembayaran/index'],
	                
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Sinkronisasi',  
	                'visible' => Yii::$app->user->can('theCreator'),
	                'url' => ['customer/sinkronisasi'],
	                
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


		return $menuItems;
    }
}