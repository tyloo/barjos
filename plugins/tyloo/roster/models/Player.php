<?php namespace Tyloo\Roster\Models;

use App;
use Str;
use Lang;
use Model;
use Markdown;
use ValidationException;
use Tyloo\Roster\Classes\TagProcessor;
use Backend\Models\User;

class Player extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table = 'tyloo_roster_players';

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required',
        'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i'],
        'content' => 'required',
        'excerpt' => ''
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['published_at'];

    /**
     * The attributes on which the player list can be ordered
     * @var array
     */
    public static $allowedSortingOptions = [
        'title asc' => 'Title (ascending)',
        'title desc' => 'Title (descending)',
        'created_at asc' => 'Created (ascending)',
        'created_at desc' => 'Created (descending)',
        'updated_at asc' => 'Updated (ascending)',
        'updated_at desc' => 'Updated (descending)',
        'published_at asc' => 'Published (ascending)',
        'published_at desc' => 'Published (descending)',
    ];

    /*
     * Relations
     */
    public $belongsTo = [
        'user' => ['Backend\Models\User']
    ];

    public $attachMany = [
        'featured_images' => ['System\Models\File', 'order' => 'sort_order'],
        'content_images' => ['System\Models\File']
    ];

    /**
     * @var array The accessors to append to the model's array form.
     */
    protected $appends = ['summary', 'has_summary'];

    public $preview = null;

    /**
     * Lists players for the front end
     * @param  array $options Display options
     * @return self
     */
    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'       => 1,
            'perPage'    => 30,
            'sort'       => 'created_at',
            'search'     => '',
            'published'  => true
        ], $options));

        $searchableFields = ['title', 'slug', 'excerpt', 'content'];

        if ($published)
            $query->isPublished();

        /*
         * Sorting
         */
        if (!is_array($sort)) $sort = [$sort];
        foreach ($sort as $_sort) {

            if (in_array($_sort, array_keys(self::$allowedSortingOptions))) {
                $parts = explode(' ', $_sort);
                if (count($parts) < 2) array_push($parts, 'desc');
                list($sortField, $sortDirection) = $parts;

                $query->orderBy($sortField, $sortDirection);
            }
        }

        /*
         * Search
         */
        $search = trim($search);
        if (strlen($search)) {
            $query->searchWhere($search, $searchableFields);
        }

        return $query->paginate($perPage, $page);
    }

    public static function formatHtml($input, $preview = false)
    {
        $result = Markdown::parse(trim($input));

        if ($preview)
            $result = str_replace('<pre>', '<pre class="prettyprint">', $result);

        $result = TagProcessor::instance()->processTags($result, $preview);

        return $result;
    }

    public function afterValidate()
    {
        if ($this->published && !$this->published_at) {
            throw new ValidationException([
               'published_at' => Lang::get('tyloo.roster::lang.player.published_validation')
            ]);
        }
    }

    public function scopeIsPublished($query)
    {
        return $query
            ->whereNotNull('published')
            ->where('published', true)
        ;
    }

    public function beforeSave()
    {
        $this->content_html = self::formatHtml($this->content);
    }

    /**
     * Used by "has_summary", returns true if this player uses a summary (more tag)
     * @return boolean
     */
    public function getHasSummaryAttribute()
    {
        return strlen($this->getSummaryAttribute()) < strlen($this->content_html);
    }

    /**
     * Used by "summary", returns the HTML content before the <!-- more --> tag
     * @return string
     */
    public function getSummaryAttribute()
    {
        $more = '<!-- more -->';
        $parts = explode($more, $this->content_html);
        return array_get($parts, 0);
    }

    /**
     * Slot options
     * @return array
     */
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

    /**
     * Sets the "url" attribute with a URL to this object
     * @param string $pageName
     * @param Cms\Classes\Controller $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    /**
     * Used to test if a certain user has permission to edit player,
     * returns TRUE if the user is the owner or has other players access.
     * @param User $user
     * @return bool
     */
    public function canEdit(User $user)
    {
        return ($this->user_id == $user->id) || $user->hasAnyAccess(['tyloo.roster.access_other_players']);
    }
}
