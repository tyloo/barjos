<?php namespace Tyloo\Roster\Components;

use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Tyloo\Roster\Models\Player as PlayerModel;

class Players extends ComponentBase
{
    /**
     * A collection of players to display
     * @var Collection
     */
    public $players;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noPlayersMessage;

    /**
     * Reference to the page name for linking to players.
     * @var string
     */
    public $playerPage;

    /**
     * If the player list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;

    public function componentDetails()
    {
        return [
            'name'        => 'tyloo.roster::lang.settings.players_title',
            'description' => 'tyloo.roster::lang.settings.players_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'tyloo.roster::lang.settings.players_pagination',
                'description' => 'tyloo.roster::lang.settings.players_pagination_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'playersPerPage' => [
                'title'             => 'tyloo.roster::lang.settings.players_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'tyloo.roster::lang.settings.players_per_page_validation',
                'default'           => '10',
            ],
            'noPlayersMessage' => [
                'title'        => 'tyloo.roster::lang.settings.matchs_no_players',
                'description'  => 'tyloo.roster::lang.settings.matchs_no_players_description',
                'type'         => 'string',
                'default'      => 'Aucun joueur trouvé.',
                'showExternalParam' => false
            ],
            'sortOrder' => [
                'title'       => 'tyloo.roster::lang.settings.players_order',
                'description' => 'tyloo.roster::lang.settings.players_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc'
            ],
            'playerPage' => [
                'title'       => 'tyloo.roster::lang.settings.players_player',
                'description' => 'tyloo.roster::lang.settings.players_player_description',
                'type'        => 'dropdown',
                'default'     => 'players/player',
                'group'       => 'Links',
            ],
        ];
    }

    public function getPlayerPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getSortOrderOptions()
    {
        return PlayerModel::$allowedSortingOptions;
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->players = $this->page['players'] = $this->listPlayers();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->players->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noPlayersMessage = $this->page['noPlayersMessage'] = $this->property('noPlayersMessage');

        /*
         * Page links
         */
        $this->playerPage = $this->page['playerPage'] = $this->property('playerPage');
    }

    protected function listPlayers()
    {
        /*
         * List all the players
         */
        $players = PlayerModel::listFrontEnd([
            'page'       => $this->property('pageNumber'),
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('playersPerPage')
        ]);

        /*
         * Add a "url" helper attribute for linking to each player
         */
        $players->each(function($player){
            $player->setUrl($this->playerPage, $this->controller);
        });

        return $players;
    }
}
