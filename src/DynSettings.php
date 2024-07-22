<?php

namespace Fantismic\DynSettings;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Fantismic\DynSettings\Models\DynamicSetting;

class DynSettings {

    
    /**
     * toObject
     *
     * @param  mixed $thing
     * @return mixed
     */
    private function toObject($thing): object|null {
        return json_decode(json_encode($thing));
    }
    
    
    /**
     * validateType
     *
     * @param  string $type
     * @param  mixed $value
     * @param  string $key
     * @return void
     */
    public function validateType($type,$value,$key=''): void {
        DynamicSetting::validateType($type,$value,$key);
    }

    /**
     * getTypes
     *
     * @return array
     */
    public function getTypes(): array {
        return DynamicSetting::getTypes();
    }
    
    /**
     * add
     *
     * @param  string $key
     * @param  mixed $value
     * @param  string $type
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function add(string $key, mixed $value, string $type, string $name, string $group='General', string $association='Misc',string $description=null): mixed {
        return DynamicSetting::add($key,$value,$type,$name,$group,$association,$description);
    }
    
    /**
     * addBool
     *
     * @param  string $key
     * @param  bool $value
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function addBool(string $key, string $name, bool $value, string $group, string $association, string $description = null): mixed {
        return DynamicSetting::add($key,$value,'boolean',$group,$association,$description);
    }
    
    /**
     * addString
     *
     * @param  string $key
     * @param  string $value
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function addString(string $key, string $name, string $value, string $group, string $association, string $description = null): mixed {
        return DynamicSetting::add($key,$value,'string',$group,$association,$description);
    }
        
    /**
     * addInt
     *
     * @param  string $key
     * @param  int $value
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function addInt(string $key, string $name, int $value, string $group, string $association, string $description = null): mixed {
        return DynamicSetting::add($key,$value,'integer',$group,$association,$description);
    }
    
    /**
     * addFloat
     *
     * @param  string $key
     * @param  float $value
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function addFloat(string $key, string $name, float $value, string $group, string $association, string $description = null): mixed {
        return $this->addDouble($key,$value,'double',$group,$association,$description);
    }
    
    /**
     * addDouble
     *
     * @param  string $key
     * @param  double $value
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function addDouble(string $key, string $name, float $value, string $group, string $association, string $description = null): mixed {
        return DynamicSetting::add($key,$value,'double',$group,$association,$description);
    }
    
    /**
     * addArray
     *
     * @param  string $key
     * @param  array $value
     * @param  string $name
     * @param  string $group
     * @param  string $association
     * @param  string $description
     * @return mixed
     */
    public function addArray(string $key, string $name, array $value, string $group, string $association, string $description = null): mixed {
        return DynamicSetting::add($key,$value,'array',$group,$association,$description);
    }

    /**
     * set
     *
     * @param  string $key
     * @param  mixed $value
     * @return bool
     */
    public function set(string $key, mixed $value): bool {
        return DynamicSetting::set($key,$value);
    }
    
    /**
     * get
     *
     * @param  string $key
     * @return mixed
     */
    public function get(string $key): mixed {
        return DynamicSetting::get($key);
    }
        
    /**
     * isKey
     *
     * @param  string $key
     * @return bool
     */
    public function isKey(string $key): bool {
        if (is_null($key) || empty($key)) return false;
        
        if ($this->getModel($key)) {
            return true;
        } else {
            return false;
        }
    }
    

    public function getModel($key=null) {
        return DynamicSetting::getModelByKey($key);
    }

    public function getModelByID($id) {
        return DynamicSetting::find($id);
    }

    public function getKeyData($key) {
        return $this->toObject(DynamicSetting::getModelByKey($key));
    }

    public function getArray() {
        return json_decode(json_encode(DynamicSetting::getAll()),true);
    }

    public function getObject() {
        return $this->toObject(DynamicSetting::getAll());
    }

    public function getDot() {
        return DynamicSetting::getDot();
    }

    public function all() {
        return DynamicSetting::all()->toArray();
    }

    public function saveArray($arr) {
        $settings_all = $this->getModel();
        $form_settings_dot = Arr::dot($arr);
        
        $keys_array = [];
        foreach ($form_settings_dot as $key => $value) {
            if (stripos($key,'.0') ==! false) {
                $keys_array[] = substr($key, 0, strrpos($key, "."));
            }
        }

        

        foreach ($keys_array as $key) {
            $form_settings_dot[$key] = array();
            $counter=0;
            do {
                $form_settings_dot[$key][] = trim($form_settings_dot[$key.".".$counter]);
                unset($form_settings_dot[$key.".".$counter]);
                $counter++;
            } while (isset($form_settings_dot[$key.".".$counter]));
        }
        
        foreach ($form_settings_dot as $key => $value) {
            if ($settings_all->where('key', $key)->first()->type == 'array') {
                if (!is_array($value)) $value = explode(',',$value);
            }
            $this->set($key,$value);
        }

        return true;
    }

    public function delete($id) {
        return DynamicSetting::deleteByID($id);
    }

    public function deleteByKey($key) {
        return DynamicSetting::deleteByKey($key);
    }

    public function getGroups() {
        return DynamicSetting::getGroups();
    }

    public function getAssocs($group=null) {
        return DynamicSetting::getAssocs($group);
    }

    public function updateGroupName($oldName, $newName) {
        return DynamicSetting::updateGroupName($oldName,$newName);
    }

    public function updateName($key,$newName) {
        return DynamicSetting::updateName($key,$newName);
    }

    public function updateDescription($key,$newDesc) {
        return DynamicSetting::updateDescription($key,$newDesc);
    }

    public function updateAssoc($key,$newAssoc) {
        return DynamicSetting::updateAssoc($key,$newAssoc);
    }

    public function getByGroup($group) {
        return DynamicSetting::getByGroup($group);
    }

    
    /**
     * should
     * 
     * get value of boolean setting to assert if action should be done based on the site config
     *
     * @param  string $key
     * @return mixed
     */
    public function should(string $key): mixed {
        $data = $this->getKeyData($key);
        if ($data->type != 'boolean') {
            throw new Exception("This method only works with boolean type settings.");
        }
        
        return $this->get($key);
    }
    
    /**
     * is
     *
     * @param  string $key
     * @param  mixed $value
     * @return bool
     */
    public function is($key,$value): bool
    {
        $data = $this->getKeyData($key);
        $keyValue = $this->get($key);
        
        switch ($data->type) {
            case 'boolean':
                return $value == $keyValue;
                break;
            
            case 'string':
                return strtolower($value) == strtolower($keyValue);
                break;

            case 'array':
            case 'integer':
            case 'double':
                return $value == $keyValue;
                break;
        }

        return false;
    }

    public function has($key, $needle, $strict = true): bool {
        $data = $this->getKeyData($key);
        if ($data->type != "array") {
            throw new Exception("This method only works with array type settings.");
        }
        $value = $this->get($key);

        if (!$strict) {
            $value = array_map('strtolower',$value);
            $needle = strtolower($needle);
        }
        

        return in_array($needle,$value);
    }
}