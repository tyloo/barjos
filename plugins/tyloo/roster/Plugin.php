<?php namespace Tyloo\Roster;

use Backend;
use Controller;
use System\Classes\PluginBase;
use Tyloo\Roster\Classes\TagProcessor;
use Event;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'tyloo.roster::lang.plugin.name',
            'description' => 'tyloo.roster::lang.plugin.description',
            'author'      => 'Julien Bonvarlet (jbonva@gmail.com)',
            'icon'        => 'icon-pencil',
            'homepage'    => 'https://github.com/tyloo/roster-plugin'
        ];
    }

    public function registerComponents()
    {
        return [
            'Tyloo\Roster\Components\Player' => 'player',
            'Tyloo\Roster\Components\Players' => 'players'
        ];
    }

    public function registerPermissions()
    {
        return [
            'tyloo.roster.access_players'       => ['tab' => 'Players', 'label' => 'tyloo.roster::lang.roster.access_players'],
            'tyloo.roster.access_other_players' => ['tab' => 'Players', 'label' => 'tyloo.roster::lang.roster.access_other_players']
        ];
    }

    public function registerNavigation()
    {
        return [
            'roster' => [
                'label'       => 'tyloo.roster::lang.roster.menu_label',
                'url'         => Backend::url('tyloo/roster/players'),
                'icon'        => 'icon-user',
                'permissions' => ['tyloo.roster.*'],
                'order'       => 500,

                'sideMenu'    => [
                    'players' => [
                        'label'       => 'tyloo.roster::lang.roster.players',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('tyloo/roster/players'),
                        'permissions' => ['tyloo.roster.access_players']
                    ]
                ]
            ]
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Tyloo\Roster\FormWidgets\Preview' => [
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
