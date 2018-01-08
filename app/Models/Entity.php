<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;
use App\Models\Content;
use App\Models\Medium;

class Entity extends Model
{

    public static $entityFields = ['id', 'parent', 'model', 'active', 'created_by', 'updated_by', 'publicated_at', 'unpublicated_at'];
    public static $contentFields = [];
    public static $dataFields = [];

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
        return $this->hasMany('App\Models\Content', 'entity_id');
    }

    /**
     * Get the other models related.
     */
    public function data()
    {
        $modelClass = Entity::getDataClass($this['model']);
        if ($modelClass && count($modelClass::$dataFields) > 0) {
            return $this->hasOne($modelClass);
        } else {
            return $this->hasOne('App\\Models\\Entity', 'id');
        }
    }

    /**
     * The roles that belong to the user.
     */
    public function relations()
    {
        return $this->belongsToMany('App\Models\Entity', 'relations', 'entity_caller_id', 'entity_called_id')
            ->using('App\Models\Relation')
            ->as('relations')
            ->withPivot('kind', 'position', 'tags')
            ->withTimestamps();
    }

    /*
     *  Return a class from a string
     */
    public static function getDataClass($modelName) {
        if ($modelName && $modelName != '') {
            return ("App\\Models\\Data\\".(ucfirst($modelName)));
        } else {
            return NULL;
        }

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

            // Auto populate the model field
            if (!isset($model['model'])) {
                $model['model'] = 'entity';
            }

            $modelClass =  Entity::getDataClass($model['model']);

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

            // Data are sent to specific table
            if (isset($model['data'])) {
                $dataToInsert = ['entity_id' => $model['id']];
                foreach ($modelClass::$dataFields as $field) {
                    if (isset($model['data'][$field])) {
                        $dataToInsert[$field] = $model['data'][$field];
                    }
                }
                $modelClass::create($dataToInsert);
                unset($model['data']);
            };
        });

        self::created(function($model) {
            // Create the ancestors relations
            if (isset($model['parent']) && $model['parent'] != '') {
                $parentEntity = Entity::find($model['parent']);
                $model->relations()->attach($parentEntity['id'], ['kind' => 'ancestor', 'position' => 1]);
                $ancestors = ($parentEntity->relations()->where('kind', 'ancestor')->orderBy('position'))->get();
                for ($a = 0; $a < count($ancestors); $a++) {
                    $model->relations()->attach($ancestors[$a]['id'], ['kind' => 'ancestor', 'position' => ($a + 2)]);
                }
            };
        });
    }
}
