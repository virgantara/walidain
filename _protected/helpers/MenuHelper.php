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


	    if (Yii::$app->user->can('admin'))
	    {



	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-money"></i><span class="menu-text"> Tagihan </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[	           
	            
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Buat Tagihan <b class="arrow fa fa-angle-down"></b>',  
	                'url' => '#',
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         		'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',	
	         		'items' => [
	         			[
			            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Per Semester',  
			                'url' => ['/tagihan/du'],	           
			            ],
	         			[
			            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Per Angkatan',  
			                'url' => ['/tagihan/du-nonaktif'],	           
			            ],
			            [
			            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Instant',  
			                'url' => ['/tagihan/instant'],	        
			            ],
	         		]           
	            ],
	            
	            
	            
	            ['label' => '<hr style="padding:0px;margin:0px">'],
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Daftar Tagihan <b class="arrow fa fa-angle-down"></b>',  
	                'url' => '#',
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         		'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',	
	         		'items' => [
	         			[
			            	'label' => '<i class="menu-icon fa fa-caret-right"></i> Tahun Berjalan',  
			                'url' => ['/tagihan/index'],	        
			            ],
			            [
			            	'label' => '<i class="menu-icon fa fa-caret-right"></i>&nbsp;Riwayat',  
			                'url' => ['/tagihan/riwayat'],	        
			            ],
	         		]           
	            ],
	            
	          
	            
	        ]];

	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-book"></i><span class="menu-text"> Transaksi </span>', 'url' => ['/transaksi'],
	       ];

	       $menuItems[] = ['label' => '<i class="menu-icon fa fa-book"></i><span class="menu-text"> Pencekalan </span>', 'url' => ['/simak-pencekalan'],
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

	  
	   
	    // display Users to admin+ roles
	    if (Yii::$app->user->can('admin')){

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
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Bulan <b class="arrow fa fa-angle-down"></b>',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['#'],
	                 'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	                'items' => [

	                     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['bulan/index']],
	                     [
	                        'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),
	                        'visible' => Yii::$app->user->can('admin'),
	                        'url' => ['bulan/create']]
	                ],
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Mahasiswa ',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['customer/index'],
	               
	            ],
	           	[
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Tahun <b class="arrow fa fa-angle-down"></b>',  
	                'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['#'],
	                 'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	                'items' => [

	                    ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['tahun/index']],
	                    [
	                        'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tambah'),
	                      
	                        'url' => ['tahun/create']
	                    ]
	                ],
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>Batas Pembayaran',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['batas-pembayaran/index'],
	                
	            ],
	            [
	                'label' => '<i class="menu-icon fa fa-caret-right"></i>VA Generator',  
	                'visible' => Yii::$app->user->can('admin'),
	                'url' => ['customer/generate-va'],
	                
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