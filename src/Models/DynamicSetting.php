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

    CONST BOOL = 'boolean';
    CONST STRING = 'string';
    CONST ARRAY = 'array';
    CONST INT = 'integer';
    CONST DOUBLE = 'double';
    CONST FLOAT = 'double';

    public static function getTypes() {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        unset($constants['CREATED_AT']);
        unset($constants['UPDATED_AT']);
        unset($constants['FLOAT']);
        return $constants;
    }

    public static function isAcceptedBool($value) {
        if (is_bool($value)) return true;

        if (!config('dynsettings.strict_bools', false)) {
            if (
                $value == 1 ||
                $value == "1" ||
                $value == "true" ||
                $value == 0 ||
                $value == "0" ||
                $value == "false"
            ) {
                return true;
            }
        }

        return false;
    }

    public static function convertAcceptedBool($value) {

        if (is_bool($value)) return $value;

        if (
            $value == 1 ||
            $value == "1" ||
            strtolower($value) == "true" ||
            $value == true
        ) {
            return TRUE;
        }

        if (
            $value == 0 ||
            $value == "0" ||
            strtolower($value) == "false" ||
            $value == false
        ) {
            return FALSE;
        }
    }

    public static function validateType($type,$value,$key='') {
        if ($type == Self::BOOL && !Self::isAcceptedBool($value)) {
            throw new Exception("Key ".$key." expects a boolean value. ".ucFirst(gettype($value)). " given. Accepted values: true | 1 | '1' | 'true' | false | 0 | '0' | 'false'");
        }

        if ($type == Self::STRING && !is_string($value)) {
            throw new Exception("Key ".$key." expects a string value. ".ucFirst(gettype($value)). " given");
        }

        if ($type == Self::INT && !is_integer($value)) {
            throw new Exception("Key ".$key." expects an integer value. ".ucFirst(gettype($value)). " given");
        }

        if ($type == Self::DOUBLE && !is_double($value)) {
            throw new Exception("Key ".$key." expects a double value. ".ucFirst(gettype($value)). " given");
        }

        if ($type == Self::ARRAY && !is_array($value)) {
            throw new Exception("Key ".$key." expects an array value. ".ucFirst(gettype($value)). " given");
        }

    }

    public static function convertValue2Store($type,$value) {
        switch ($type) {
            case Self::BOOL:
                return json_encode(Self::convertAcceptedBool($value));
                break;
            
            case Self::ARRAY:
                if (config('dynsettings.filter_arrays', true)) {
                    $value = array_filter($value);
                }
                return json_encode($value);
            
            case Self::STRING:
            case Self::INT:
            case Self::DOUBLE:
                return $value;
                break;
        }
    }

    public static function convertValue2Retrieve($type,$value) {
        switch ($type) {           
            case Self::ARRAY:
            case Self::BOOL:
                return json_decode($value,true);
            
            case Self::STRING:
            case Self::INT:
            case Self::DOUBLE:
                return $value;
                break;
        }
    }

    public static function add($key,$value,$type,$name,$group,$association,$description=null) {

        if (!in_array($type,Self::getTypes())) {
            throw new Exception("Invalid type ".$type.". Expected boolean, integer, string, array or double");
        }

        $model = Self::where('key',$key)->first();

        if ($model) {
            throw new Exception("Key ".$key." already exists");
        }

        Self::validateType($type,$value,$key);

        $value = Self::convertValue2Store($type, $value);

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
    
            Self::validateType($model->type,$value,$key);
            
            $model->value = Self::convertValue2Store($model->type, $value);
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

            return Self::convertValue2Retrieve($model->type,$model->value);

        } catch (\Throwable $th) {
            throw new Exception($th);
        }

    }


    public static function getAll() {

        try {
            $settings = Arr::map(Self::All()->pluck('value','key')->toArray(), function ($value, $key) {
                if (json_validate($value)) {
                    return json_decode($value,true);
                } else {
                    return $value;
                }
            });
    
            return json_decode(json_encode(Arr::undot($settings)));

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
                if (json_validate($value)) {
                    return json_decode($value,true);
                } else {
                    return $value;
                }
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