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
     * @return bool
     */
    public function add(string $key, mixed $value, string $type, string $name, string $group='General', string $association='Misc',string $description=null): bool {
        return DynamicSetting::add($key,$value,$type,$name,$group,$association,$description);
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

    public function should($key) {
        $data = $this->getKeyData($key);
        $keyValue = json_decode($data->value,true);
        if ($data->type != 'boolean') {
            throw new Exception("This method only works with boolean type settings.");
        }

        return $keyValue;
    }

    public function is($key,$value)
    {
        $data = $this->getKeyData($key);
        $keyValue = json_decode($data->value,true);
        
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

        return $data;
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

}