<?php namespace Tyloo\Roster\Controllers;

use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use ApplicationException;
use Tyloo\Roster\Models\Player;

class Players extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = ['tyloo.roster.access_other_players', 'tyloo.roster.access_players'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('tyloo.roster', 'player', 'players');
        $this->addCss('/plugins/tyloo/roster/assets/css/tyloo.roster-preview.css');
        $this->addCss('/plugins/tyloo/roster/assets/css/tyloo.roster-preview-theme-default.css');

        $this->addCss('/plugins/tyloo/roster/assets/vendor/prettify/prettify.css');
        $this->addCss('/plugins/tyloo/roster/assets/vendor/prettify/theme-desert.css');

        $this->addJs('/plugins/tyloo/roster/assets/js/player-form.js');
        $this->addJs('/plugins/tyloo/roster/assets/vendor/prettify/prettify.js');
    }

    public function index()
    {
        $this->vars['playersTotal'] = Player::count();
        $this->vars['playersPublished'] = Player::isPublished()->count();
        $this->vars['playersDrafts'] = $this->vars['playersTotal'] - $this->vars['playersPublished'];

        $this->asExtension('ListController')->index();
    }

    public function listExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['tyloo.roster.access_other_players'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    public function formExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['tyloo.roster.access_other_players'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $playerId) {
                if ((!$player = Player::find($playerId)) || !$player->canEdit($this->user))
                    continue;

                $player->delete();
            }

            Flash::success('Successfully deleted those players.');
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
        $data = post('Player');

        $previewHtml = Player::formatHtml($data['content'], true);

        return [
            'preview' => $previewHtml
        ];
    }

}