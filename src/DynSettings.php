<?php

namespace Fantismic\DynSettings;

use Exception;
use Illuminate\Support\Arr;
use Fantismic\DynSettings\Models\DynamicSetting;

class DynSettings {

    private function toObject($thing) {
        return json_decode(json_encode($thing));
    }

    public function getTypes() {
        return DynamicSetting::getTypes();
    }

    public function add($key,$value,$type,$name,$group='General',$association='Misc',$description=null) {
        return DynamicSetting::add($key,$value,$type,$name,$group,$association,$description);
    }

    public function set($key,$value) {
        return DynamicSetting::set($key,$value);
    }

    public function get($key) {
        return DynamicSetting::get($key);
    }

    public function getModel($key=null) {
        return DynamicSetting::getModelByKey($key);
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
        if ($data->type != 'bool') {
            throw new Exception("This method only works with BOOL type settings.");
        }

        return $keyValue;
    }

    public function is($key,$value)
    {
        $data = $this->getKeyData($key);
        $keyValue = json_decode($data->value,true);
        
        switch ($data->type) {
            case 'bool':
                return $value === $keyValue;
                break;
            
            case 'string':
                return strtolower($value) == strtolower($keyValue);
                break;

            case 'array':
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