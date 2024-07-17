<?php

namespace Fantismic\DynSettings\Models;

use Exception;
use ReflectionClass;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Throw_;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DynamicSetting extends Model
{

    protected $table = 'dynamic_settings';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'type',
        'name',
        'description',
        'group',
        'associate_with'
    ];

    CONST BOOL = 'bool';
    CONST STRING = 'string';
    CONST ARRAY = 'array';
    CONST INT = 'int';
    CONST JSON = 'json';

    public static function getTypes() {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        unset($constants['CREATED_AT']);
        unset($constants['UPDATED_AT']);
        return $constants;
    }

    public static function add($key,$value,$type,$name,$group,$association,$description=null) {

        if (!in_array($type,Self::getTypes())) {
            throw new Exception("Invalid type ".$type.". Expected bool, int, string, array or json");
        }

        $model = Self::where('key',$key)->first();

        if ($model) {
            throw new Exception("Key ".$key." already exists");
        }

        if ($type == Self::BOOL && !is_bool($value)) {
            throw new Exception("Key ".$key." expects boolean value. ".ucFirst(gettype($value)). " given");
        }

        if ($type != Self::JSON) {
            $value = json_encode($value);
        }

        #Cache::forget('app_custom_settings');

        try {
            Self::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'group' => $group,
                'associate_with' => $association,
            ]);
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }

    }

    public static function set($key,$value) {

        #Cache::forget('app_custom_settings');


        try {
            $model = Self::where('key',$key)->first();

            if (!$model) {
                throw new Exception("Key ".$key." does not exists");
            }
    
            if ($model->type == Self::BOOL && !is_bool($value)) {
                throw new Exception("Key ".$key." expects boolean value. ".ucFirst(gettype($value)). " given");
            }
    
            if ($model->type != Self::JSON) {
                $value = json_encode($value);
            }
    
            $model->value = $value;
            $model->save();
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }

    }

    public static function get($key) {

/*         $settings = Self::getAll();
        return data_get($settings,$key); */
        try {
            $model = Self::where('key',$key)->first();
            if (!$model) return null;
            if ($model->type != Self::JSON) {
                $value = json_decode($model->value);
            } else {
                $value = $model->value;
            }

            return $value;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }

    }


    public static function getAll() {

/*         $settings = Cache::rememberForever('app_custom_settings', function () {
            $settings = Arr::map(Self::All()->pluck('value','key')->toArray(), function ($value, $key) {
                return json_decode($value,true);
            });

            return json_decode(json_encode(Arr::undot($settings)));
        }); */

        try {
            $settings = Arr::map(Self::All()->pluck('value','key')->toArray(), function ($value, $key) {
                return json_decode($value,true);
            });
    
            return json_decode(json_encode(Arr::undot($settings)));
            return $settings;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function getModelByKey($key=null) {
        if ($key) {
            return Self::where('key',$key)->first();
        }
        return Self::all();
    }

    public static function getDot() {
        try {
            return Arr::map(DynamicSetting::All()->pluck('value','key')->toArray(), function ($value, $key) {
                return json_decode($value,true);
            });
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function deleteByID($id) {
        try {
            Self::find($id)->delete();
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function deleteByKey($key) {
        try {
            Self::where('key',$key)->first()->delete();
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function getGroups() {
        return Self::query()->distinct()->pluck('group')->toArray();
    }

    public static function getAssocs($group=null) {
        if ($group) {
            return Self::where('group',$group)->distinct()->pluck('associate_with')->toArray();
        }
        return Self::query()->distinct()->pluck('associate_with')->toArray();
    }

    public static function updateGroupName($oldName, $newName) {
        try {
            Self::where('group',$oldName)->update(['group' => $newName]);
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function updateName($key,$newName) {
        try {
            Self::where('key',$key)->update(['name' => $newName]);
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function updateDescription($key,$newDesc) {
        try {
            Self::where('key',$key)->update(['description' => $newDesc]);
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function updateAssoc($key,$newAssoc) {
        try {
            Self::where('key',$key)->update(['associate_with' => $newAssoc]);
            return true;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public static function getByGroup($group) {
        try {
            return Self::where('group',$group)->get()->toArray();
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }
}