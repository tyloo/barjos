<?php namespace Tyloo\Match;

use Backend;
use Controller;
use System\Classes\PluginBase;
use Tyloo\Match\Classes\TagProcessor;
use Event;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'tyloo.match::lang.plugin.name',
            'description' => 'tyloo.match::lang.plugin.description',
            'author'      => 'Julien Bonvarlet (jbonva@gmail.com)',
            'icon'        => 'icon-pencil',
            'homepage'    => 'https://github.com/tyloo/match-plugin'
        ];
    }

    public function registerComponents()
    {
        return [
            'Tyloo\Match\Components\Match'  => 'match',
            'Tyloo\Match\Components\Matchs' => 'matchs'
        ];
    }

    public function registerPermissions()
    {
        return [
            'tyloo.match.access_matchs'       => ['tab' => 'Matchs', 'label' => 'tyloo.match::lang.match.access_matchs'],
            'tyloo.match.access_other_matchs' => ['tab' => 'Matchs', 'label' => 'tyloo.match::lang.match.access_other_matchs']
        ];
    }

    public function registerNavigation()
    {
        return [
            'match' => [
                'label'       => 'tyloo.match::lang.match.menu_label',
                'url'         => Backend::url('tyloo/match/matchs'),
                'icon'        => 'icon-calendar',
                'permissions' => ['tyloo.match.*'],
                'order'       => 500,

                'sideMenu'    => [
                    'matchs'      => [
                        'label'       => 'tyloo.match::lang.match.matchs',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('tyloo/match/matchs'),
                        'permissions' => ['tyloo.match.access_matchs']
                    ]
                ]
            ]
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Tyloo\Match\FormWidgets\Preview' => [
                'label' => 'Preview',
                'code'  => 'preview'
            ]
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register()
    {
        /*
         * Register the image tag processing callback
         */
        TagProcessor::instance()->registerCallback(function ($input, $preview) {
            if (!$preview) return $input;

            return preg_replace('|\<img src="image" alt="([0-9]+)"([^>]*)\/>|m',
                '<span class="image-placeholder" data-index="$1">
                    <span class="upload-dropzone">
                        <span class="label">Click or drop an image...</span>
                        <span class="indicator"></span>
                    </span>
                </span>',
                $input);
        });
    }
}
