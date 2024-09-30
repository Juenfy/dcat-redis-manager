<?php

namespace Juenfy\DcatRedisManager;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class DcatRedisManagerServiceProvider extends ServiceProvider
{
    protected $js = [
        'js/index.js',
        'js/icheck.min.js',
        'js/jquery.slimscroll.min.js',
    ];
	protected $css = [
		'css/index.css',
        'css/skins/all.css',
	];

    protected $menu = [
        [
            'title' => 'Redis Manager',
            'uri' => 'redis',
            'icon' => 'fa-database'
        ],
    ];

	public function register()
	{
		//
	}

	public function init()
	{
		parent::init();
        Admin::requireAssets('@juenfy.dcat-redis-manager');
        Admin::requireAssets('select2');
	}
}
