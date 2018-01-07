<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use App\Models\Content;

class Entity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entities';

    /**
     * The primary key
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * Indicates  the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the contents of the Entity.
     */
    public function contents()
    {
        return $this->hasMany('App\Models\Content');
    }

    /**
     * Events.
     *
     * @var bool
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {

            // Auto populate the _id field
            if (!isset($model['id'])) {
                $model['id'] = Uuid::uuid4()->toString();
            }

            // Contents are sent to another table
            if (isset($model['contents'])) {
                $thisEntityContents = $model['contents'];
                $defaultLang = '';
                foreach ($thisEntityContents as $rowOrFieldKey => $rowOrFieldValue) {
                    if (is_integer($rowOrFieldKey)) {
                        // If is an array, then we assume fields come as in the content table
                        Content::create([
                            'entity_id'   =>  $model['id'],
                            'field' =>  $rowOrFieldValue['field'],
                            'lang'  =>  isset($rowOrFieldValue['lang']) ? $rowOrFieldValue['lang'] : $defaultLang ,
                            'value' =>  $rowOrFieldValue['value']
                        ]);
                    } else {
                        // If not, we are going to use the default language and the keys as field names
                        Content::create([
                            'entity_id'   =>  $model['id'],
                            'field' =>  $rowOrFieldKey,
                            'lang'  =>  $defaultLang,
                            'value' =>  $rowOrFieldValue
                        ]);
                    }
                };
                unset($model['contents']);
            };
        });
    }
}
