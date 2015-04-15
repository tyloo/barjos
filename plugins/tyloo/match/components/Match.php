<?php namespace Tyloo\Match\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Tyloo\Match\Models\Match as MatchModel;

class Match extends ComponentBase
{
    /**
     * @var MatchModel The match model used for display.
     */
    public $match;

    public function componentDetails()
    {
        return [
            'name'        => 'tyloo.match::lang.settings.match_title',
            'description' => 'tyloo.match::lang.settings.match_description'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'tyloo.match::lang.settings.match_slug',
                'description' => 'tyloo.match::lang.settings.match_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
        ];
    }

    public function onRun()
    {
        $this->match = $this->page['match'] = $this->loadMatch();
    }

    protected function loadMatch()
    {
        return MatchModel::isPublished()->where('slug', $this->property('slug'))->first();
    }
}
