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

		$acl = [
			Yii::$app->user->can('admSalesCab'),
			Yii::$app->user->can('gudang'),
			Yii::$app->user->can('adminSpbu'),
			Yii::$app->user->can('operatorCabang')
		];
		if (in_array($userRole, $acl))
	    {

	        $submenuPenjualan = [];

	        // if(Yii::$app->user->can('adminSpbu'))
	        // {
	        //     $submenuPenjualan = [
	        //          ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Rekap'),'url' => ['bbm-jual/index']],
	        //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['bbm-jual/create']],  
	        //     ];
	        // }

	        // else if(Yii::$app->user->can('admSalesCab'))
	        // {
	        //     $submenuPenjualan = [
	        //          ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['sales-income/index']],
	        //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['sales-income/create']],  
	        //     ];
	        // }

	        // else if(Yii::$app->user->can('operatorCabang'))
	        // {   
	        //     // $submenuPenjualan = [
	        //     //      ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['departemen-jual/index']],
	        //     //     ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['departemen-jual/create']],  
	        //     // ];
	        // }

	        // $menuItems[] = [
	        //     'label' => '<i class="menu-icon fa fa-bar-chart-o"></i><span class="menu-text"> Penjualan </span><i class="caret"></i>', 
	        //     'url' => '#',
	        //     'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //     'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //     'visible' => Yii::$app->user->can('admSalesCab') || Yii::$app->user->can('adminSpbu'),
	        //     'items'=>$submenuPenjualan
	        // ];

	        // $menuItems[] = [
	        //     'label' => '<i class="menu-icon fa fa-shopping-cart"></i><span class="menu-text"> Pembelian </span><i class="caret"></i>', 
	        //     'url' => '#',
	        //     'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //      'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //     'visible' => Yii::$app->user->can('adminSpbu'),
	        //     'items' => [
	        //        	['label' => '<i class="menu-icon fa fa-caret-right"></i>Dropping<b class="arrow fa fa-angle-down"></b>',  
	        //             'url' => ['#'],
	        //             'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //             'visible' => !Yii::$app->user->can('operatorCabang') || Yii::$app->user->can('theCreator'),
	        //             'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //             'items' => [

	        //                 ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['barang-datang/index']],
	        //                 ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['barang-datang/create']],
	        //                 // ['label' => ( 'Harga'),'url' => ['barang-harga/index']],
	        //             ],
	        //         ],
	        //          ['label' => '<hr style="padding:0px;margin:0px">'],
	        //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['bbm-faktur/index']],
	        //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['bbm-faktur/create']],
	               
	                   
	        //     ],
	        // ];



	        
	    }

	    $acl = [
			Yii::$app->user->can('admSalesCab'),
			Yii::$app->user->can('gudang'),
			Yii::$app->user->can('distributor'),
			Yii::$app->user->can('adminSpbu'),
			Yii::$app->user->can('operatorCabang'),
			Yii::$app->user->can('kepalaGudang')
		];
		if (in_array($userRole, $acl))
	    
	    {

	    	// $menuItems[] = [
	     //        'label' => '<i class="menu-icon fa fa-shopping-cart"></i><span class="menu-text"> Pembelian </span><i class="caret"></i>', 
	     //        'url' => '#',
	     //        'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //        'visible' => Yii::$app->user->can('gudang'),
	     //        'items' => [

	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['sales-faktur/index']],
	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['sales-faktur/create']],
	               
	                   
	     //        ],
	     //    ];

	     //    $menuItems[] = [
	     //        'label' => '<i class="menu-icon fa fa-shopping-cart"></i><span class="menu-text"> Retur </span><i class="caret"></i>', 
	     //        'url' => ['retur/index'],
	     //        'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //        'visible' => Yii::$app->user->can('gudang'),
	     //        'items' => [

	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['retur/index']],
	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['retur/create']],
	               
	                   
	     //        ],
	            
	     //    ];
	     //    $menuItems[] = [
	     //        'label' => '<i class="menu-icon fa fa-shopping-cart"></i><span class="menu-text"> Penjualan </span><i class="caret"></i>', 
	     //        'url' => '#',
	     //        'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //        'visible' => Yii::$app->user->can('operatorCabang'),
	     //        'items' => [

	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['penjualan/index']],
	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['penjualan/create']],
	               
	                   
	     //        ],
	     //    ];
	     //    $menuItems[] = ['label' => '<i class="menu-icon fa fa-tasks"></i><span class="menu-text"> Permintaan </span><i class="caret"></i>', 'url' => '#',
	     //     'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //   'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //    'items'=>[
	     //        ['label' => '<i class="menu-icon fa fa-caret-right"></i>Masuk<b class="arrow fa fa-angle-down"></b>',  
	     //            'url' => ['#'],
	     //            // 'visible' => !Yii::$app->user->can('operatorCabang'),
	     //            'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //            'items' => [

	     //                ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['request-order-in/index']],
	     //                // ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['request-order-in/create']],
	     //                // ['label' => ( 'Harga'),'url' => ['barang-harga/index']],
	     //            ],
	     //        ],
	     //        ['label' => '<i class="menu-icon fa fa-caret-right"></i>Keluar<b class="arrow fa fa-angle-down"></b>',  
	     //            'url' => ['#'],
	     //            // 'visible' => !Yii::$app->user->can('operatorCabang'),
	     //            'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //            'items' => [

	     //                ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['request-order/index']],
	     //                ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['request-order/create']],
	     //                // ['label' => ( 'Harga'),'url' => ['barang-harga/index']],
	     //            ],
	     //        ],
	           
	     //    ]];

	     //     $menuItems[] = [
	     //        'label' => '<i class="menu-icon fa fa-shopping-cart"></i><span class="menu-text"> Mutasi </span><i class="caret"></i>', 
	     //        'url' => ['retur/index'],
	     //        'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	     //         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	     //        'visible' => Yii::$app->user->can('operatorCabang') || Yii::$app->user->can('gudang'),
	     //        'items' => [

	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['distribusi-barang/index']],
	     //            ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['distribusi-barang/create']],
	               
	                   
	     //        ],
	            
	     //    ];

	       	// $menuItems[] = [
	        //     'label' => '<i class="menu-icon fa fa-shopping-cart"></i><span class="menu-text"> Produksi </span><i class="caret"></i>', 
	        //     'url' => '#',
	        //     'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //      'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //     'visible' => Yii::$app->user->can('operatorCabang'),
	        //     'items' => [

	        //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['produksi/index']],
	        //         // ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['distribusi-barang/create']],
	               
	                   
	        //     ],
	            
	        // ];
	    }
	    // we do not need to display About and Contact pages to employee+ roles
	    
	    // $menuItems[] = ['label' => ( 'About'), 'url' => ['/site/about']];
	    $is_non_gudang = [
	    	Yii::$app->user->can('operatorCabang'),
	    	Yii::$app->user->can('operatorUnit'),
	    	Yii::$app->user->can('gudang')
	    ];
	    

	    if (Yii::$app->user->can('theCreator') ||
	    	Yii::$app->user->can('admin') 
	    	|| Yii::$app->user->can('gudang') 
	        || Yii::$app->user->can('admSalesCab') 
	        || Yii::$app->user->can('adminSpbu')
	        || Yii::$app->user->can('operatorCabang')
	        || Yii::$app->user->can('kepalaGudang')
	        || Yii::$app->user->can('operatorUnit')

	    )
	    {
	        // $menuItems[] = ['label' => '<i class="menu-icon fa fa-archive"></i><span class="menu-text"> Gudang </span><i class="caret"></i>', 'url' => '#',
	        //  'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //  'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        // 'items'=>[
	        //     ['label' => '<i class="menu-icon fa fa-caret-right"></i>Stok Gudang<b class="arrow fa fa-angle-down"></b>',  
	        //         'url' => ['#'],
	        //         'visible' => Yii::$app->user->can('gudang'),
	        //         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //      'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //         'items' => [

	        //             ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['sales-stok-gudang/index']],
	        //             ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['sales-stok-gudang/create']],
	        //             // ['label' => '<hr style="padding:0px;margin:0px">'],
	        //             // ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Status'),'url' => ['sales-stok-gudang/status']],
	        //             // ['label' => ('<i class="menu-icon fa fa-caret-right"></i>Kartu Stok'),'url' => ['sales-stok-gudang/kartu']],
	        //             // ['label' => ( 'Harga'),'url' => ['barang-harga/index']],
	        //         ],
	        //     ],
	           
	        //     ['label' => '<i class="menu-icon fa fa-caret-right"></i>Rekap Barang Gudang',  
	        //         'url' => ['barang-stok/rekap'],
	        //         'visible' => !Yii::$app->user->can('operatorCabang') && !Yii::$app->user->can('gudang') && !Yii::$app->user->can('operatorUnit'),
	                
	        //     ],
	        //     ['label' => '<i class="menu-icon fa fa-caret-right"></i>Stok Awal Gudang',  
	        //         'url' => ['stok-awal/index'],
	        //         'visible' => !Yii::$app->user->can('operatorCabang') && !Yii::$app->user->can('gudang') && !Yii::$app->user->can('operatorUnit'),
	                
	        //     ],
	        //     ['label' => '<i class="menu-icon fa fa-caret-right"></i>Sisa DO',  
	        //         'url' => ['sisa-do/index'],
	        //         'visible' => !Yii::$app->user->can('operatorCabang') && !Yii::$app->user->can('gudang') && !Yii::$app->user->can('operatorUnit'),
	                
	        //     ],
	        //      ['label' => '<i class="menu-icon fa fa-caret-right"></i>Stok Opname<b class="arrow fa fa-angle-down"></b>',  
	        //         'url' => ['#'],
	        //         'visible' => Yii::$app->user->can('operatorCabang') || Yii::$app->user->can('gudang'),
	        //         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	        //      'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	        //         'items' => [

	        //             // ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Manage'),'url' => ['barang-opname/index']],
	        //             ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Baru'),'url' => ['barang-opname/create']],
	        //             // '<li class="divider"></li>',
	        //             // ['label' => ( 'Rekap'),'url' => ['barang-stok/rekap']],
	        //         ],
	        //     ],
	        //      ['label' => '<i class="menu-icon fa fa-caret-right"></i>Loss',  
	        //         'url' => ['barang-loss/index'],
	        //         'visible' => !Yii::$app->user->can('operatorCabang') && !Yii::$app->user->can('gudang') && !Yii::$app->user->can('operatorUnit'),
	               
	        //     ],
	        //     [
	        //         'label' => '<i class="menu-icon fa fa-caret-right"></i>Stok Unit',  
	        //         'visible' => Yii::$app->user->can('operatorCabang') || Yii::$app->user->can('operatorUnit') || Yii::$app->user->can('operatorUnit') || Yii::$app->user->can('gudang'),
	        //         'url' => ['departemen-stok/index'],
	              
	        //     ],

         //        [
         //        	'label' => ( '<i class="menu-icon fa fa-caret-right"></i>Status'),
         //        	'url' => ['sales-stok-gudang/status'],
         //        	'visible' => Yii::$app->user->can('gudang'),
         //        ],
               
         //        [
         //        	'label' => ('<i class="menu-icon fa fa-caret-right"></i>Kartu Stok'),
         //        	'url' => ['sales-stok-gudang/kartu'],
         //        	'visible' => Yii::$app->user->can('operatorCabang') || Yii::$app->user->can('operatorUnit') || Yii::$app->user->can('operatorUnit') || Yii::$app->user->can('gudang'),
         //        ],
	            
	        // ]];
	    }

	    if (Yii::$app->user->can('admin'))
	    {
	        $menuItems[] = ['label' => '<i class="menu-icon fa fa-book"></i><span class="menu-text"> Tagihan </span><i class="caret"></i>', 'url' => '#',
	         'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	         'template' => '<a href="{url}" class="dropdown-toggle">{label}</a>',
	         'visible' => Yii::$app->user->can('admin'),
	        'items'=>[
	           
	            [
	            	'label' => '<i class="menu-icon fa fa-caret-right"></i>Bulk Tagihan',  
	                'url' => ['/tagihan/bulk'],	           
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
	            
	            // [
	            // 	'label' => '<i class="menu-icon fa fa-caret-right"></i>Mutasi Barang',  
	            //     'url' => '#',	        
	            //     'visible' => Yii::$app->user->can('gudang'),
	            //     'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	            //     'items' => [

	            //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Keluar'),'url' => ['laporan/mutasi-keluar']],
	            //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Masuk'),'url' => ['laporan/mutasi-masuk']],
	            //         // '<li class="divider"></li>',
	            //         // ['label' => ( 'Rekap'),'url' => ['barang-stok/rekap']],
	            //     ],        
	            // ],
	            // [
	            // 	'label' => '<i class="menu-icon fa fa-caret-right"></i>ED',  
	            //     'url' => '#',	        
	            //     'visible' => Yii::$app->user->can('gudang'),
	            //     'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	            //     'items' => [

	            //         // ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Bulanan'),'url' => ['laporan/ed-bulanan']],
	            //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Tahunan'),'url' => ['laporan/ed-tahunan']],
	            //         // '<li class="divider"></li>',
	            //         // ['label' => ( 'Rekap'),'url' => ['barang-stok/rekap']],
	            //     ],        
	            // ],
	            // [
	            // 	'label' => '<i class="menu-icon fa fa-caret-right"></i>Stok Opname',  
	            //     'url' => '#',	        
	            //     // 'visible' => Yii::$app->user->can('gudang'),
	            //     'submenuTemplate' => "\n<ul class='submenu'>\n{items}\n</ul>\n",
	            //     'items' => [

	            //         ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Bulanan'),'url' => ['laporan/opname-bulanan']],
	            //         // ['label' => ( '<i class="menu-icon fa fa-caret-right"></i>Triwulan'),'url' => ['laporan/opname-triwulan']],
	            //         // '<li class="divider"></li>',
	            //         // ['label' => ( 'Rekap'),'url' => ['barang-stok/rekap']],
	            //     ],        
	            // ],
	            // [
	            // 	'label' => '<i class="menu-icon fa fa-caret-right"></i>Penjualan',  
	            //     'url' => ['laporan/penjualan'],
	            //     'visible' => Yii::$app->user->can('operatorCabang'),	                
	            // ],
	            
	            // ['label' => '<hr style="padding:0px;margin:0px">'],
	            
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
	                        'visible' => Yii::$app->user->can('admin'),
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