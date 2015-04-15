<?php namespace Tyloo\Match\Controllers;

use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use ApplicationException;
use Tyloo\Match\Models\Match;

class Matchs extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = ['tyloo.match.access_other_matchs', 'tyloo.match.access_matchs'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tyloo.Match', 'match', 'matchs');
        $this->addCss('/plugins/tyloo/match/assets/css/tyloo.match-preview.css');
        $this->addCss('/plugins/tyloo/match/assets/css/tyloo.match-preview-theme-default.css');

        $this->addCss('/plugins/tyloo/match/assets/vendor/prettify/prettify.css');
        $this->addCss('/plugins/tyloo/match/assets/vendor/prettify/theme-desert.css');

        $this->addJs('/plugins/tyloo/match/assets/js/match-form.js');
        $this->addJs('/plugins/tyloo/match/assets/vendor/prettify/prettify.js');
    }

    public function index()
    {
        $this->vars['matchsTotal'] = Match::count();
        $this->vars['matchsPublished'] = Match::isPublished()->count();
        $this->vars['matchsDrafts'] = $this->vars['matchsTotal'] - $this->vars['matchsPublished'];

        $this->asExtension('ListController')->index();
    }

    public function listExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['tyloo.match.access_other_matchs'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    public function formExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['tyloo.match.access_other_matchs'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $matchId) {
                if ((!$match = Match::find($matchId)) || !$match->canEdit($this->user))
                    continue;

                $match->delete();
            }

            Flash::success('Successfully deleted those matchs.');
        }

        return $this->listRefresh();
    }

    /**
     * {@inheritDoc}
     */
    public function listInjectRowClass($record, $definition = null)
    {
        if (!$record->published)
            return 'safe disabled';
    }

    public function formBeforeCreate($model)
    {
        $model->user_id = $this->user->id;
    }

    public function onRefreshPreview()
    {
        $data = post('Match');

        $previewHtml = Match::formatHtml($data['content'], true);

        return [
            'preview' => $previewHtml
        ];
    }

}