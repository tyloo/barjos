<?php namespace Tyloo\Roster\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Tyloo\Roster\Models\Player as PlayerModel;

class Player extends ComponentBase
{
    /**
     * @var PlayerModel The player model used for display.
     */
    public $player;

    public function componentDetails()
    {
        return [
            'name'        => 'tyloo.roster::lang.settings.player_title',
            'description' => 'tyloo.roster::lang.settings.player_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'tyloo.roster::lang.settings.player_slug',
                'description' => 'tyloo.roster::lang.settings.player_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ]
        ];
    }

    public function getSlotOptions()
    {
        return [
            'Coach' => 'Coach',
            'Première ligne' => 'Première ligne',
            'Deuxième ligne' => 'Deuxième ligne',
            'Troisième ligne' => 'Troisième ligne',
            'Charnière' => 'Charnière',
            'Trois-quarts' => 'Trois-quarts',
            'Arrière' => 'Arrière',
        ];
    }

    public function onRun()
    {
        $this->player = $this->page['player'] = $this->loadPlayer();
    }

    protected function loadPlayer()
    {
        return PlayerModel::isPublished()->where('slug', $this->property('slug'))->first();
    }
}
