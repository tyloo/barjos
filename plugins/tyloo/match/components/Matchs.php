<?php namespace Tyloo\Match\Components;

use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Tyloo\Match\Models\Match as MatchModel;

class Matchs extends ComponentBase
{
    /**
     * A collection of matchs to display
     * @var Collection
     */
    public $matchs;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noMatchsMessage;

    /**
     * Reference to the page name for linking to matchs.
     * @var string
     */
    public $matchPage;

    /**
     * If the match list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;

    public function componentDetails()
    {
        return [
            'name'        => 'tyloo.match::lang.settings.matchs_title',
            'description' => 'tyloo.match::lang.settings.matchs_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'tyloo.match::lang.settings.matchs_pagination',
                'description' => 'tyloo.match::lang.settings.matchs_pagination_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'matchsPerPage' => [
                'title'             => 'tyloo.match::lang.settings.matchs_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'tyloo.match::lang.settings.matchs_per_page_validation',
                'default'           => '10',
            ],
            'noMatchsMessage' => [
                'title'        => 'tyloo.match::lang.settings.matchs_no_matchs',
                'description'  => 'tyloo.match::lang.settings.matchs_no_matchs_description',
                'type'         => 'string',
                'default'      => 'Aucun match trouvé.',
                'showExternalParam' => false
            ],
            'sortOrder' => [
                'title'       => 'tyloo.match::lang.settings.matchs_order',
                'description' => 'tyloo.match::lang.settings.matchs_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc'
            ],
            'matchPage' => [
                'title'       => 'tyloo.match::lang.settings.matchs_match',
                'description' => 'tyloo.match::lang.settings.matchs_match_description',
                'type'        => 'dropdown',
                'default'     => 'matchs/match',
                'group'       => 'Links',
            ],
        ];
    }

    public function getMatchPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getSortOrderOptions()
    {
        return MatchModel::$allowedSortingOptions;
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->matchs = $this->page['matchs'] = $this->listMatchs();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->matchs->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->noMatchsMessage = $this->page['noMatchsMessage'] = $this->property('noMatchsMessage');

        /*
         * Page links
         */
        $this->matchPage = $this->page['matchPage'] = $this->property('matchPage');
    }

    protected function listMatchs()
    {
        /*
         * List all the matchs
         */
        $matchs = MatchModel::listFrontEnd([
            'page'       => $this->property('pageNumber'),
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('matchsPerPage')
        ]);

        /*
         * Add a "url" helper attribute for linking to each match
         */
        $matchs->each(function($match){
            $match->setUrl($this->matchPage, $this->controller);
        });

        return $matchs;
    }
}
