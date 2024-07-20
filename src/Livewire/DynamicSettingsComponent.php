<?php

namespace Fantismic\DynSettings\Livewire;

use Exception;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Fantismic\DynSettings\Facades\DynSettings;

class DynamicSettingsComponent extends Component
{

    public $wire_ui = false;
    public $layout_mode;
    public $layout_path;
    public $alert_array_format;

    public $settingsArr = [];
    public $settingsAll = [];

    public $showMessage=false;
    public $message;

    public $showRemoveModal=false;
    public $showAddModal=false;

    public $deleteItem = "0";
    public $isEdit = false;
    public $editID;

    #[Validate('required|min:3')] 
    public $name;
    #[Validate('required|min:3')] 
    public $key;
    #[Validate('required|min:3')] 
    public $type;
    #[Validate('required|min:3')] 
    public $group;
    #[Validate('required|min:3')] 
    public $assoc;
    public $description;
    public $value;
    
    public $allTypes = [];
    public $allGroups = [];
    public $allAssocs = [];
    
    public $groups = [];

    public function mount() {
        
        $this->layout_mode = config('dynsettings.layout_mode', 'component');
        $this->layout_path = config('dynsettings.layout_path', 'layouts.app');
        $this->alert_array_format = config('dynsettings.alert_array_format', true);

        switch (strtolower(config('dynsettings.component_blade'))) {
            case 'wireui':
                $this->wire_ui = true;
                break;

            case 'blade':
                $this->wire_ui = false;
                break;
            
            default:
                if (class_exists('WireUi\ServiceProvider')) {
                    $this->wire_ui = true;
                }
                break;
        }

        $this->getData();

    }

    public function boot()
    {
        if (!$this->isEdit){
            $this->withValidator(function ($validator) {
                $validator->after(function ($validator) {
                    if (DynSettings::isKey($this->key)) {
                        $validator->errors()->add('key', "A key already exists with this value");
                    }
                });
            });
        }
    }

    public function resetFields() {
        $this->allTypes = [];
        $this->allGroups = [];
        $this->allAssocs = [];
        $this->name = null;
        $this->key = null;
        $this->type = null;
        $this->group = null;
        $this->assoc = null;
        $this->description = null;
    }

    public function getData() {
        $this->settingsArr = DynSettings::getArray();
        $this->settingsAll = DynSettings::getModel();

        $this->allTypes = DynSettings::getTypes();
        $this->allGroups = DynSettings::getGroups();

        $this->groups = [];
        $groups = collect($this->settingsAll->groupBy('group'))->recursive();     
           
        foreach ($groups as $name => $group) {
            $this->groups[$name] = collect($group->groupBy('associate_with'))->recursive();
        }
    }

    public function updatedGroup($value) {
        $this->allAssocs = DynSettings::getAssocs($value);
    }

    public function save() {
        DynSettings::saveArray($this->settingsArr);
        $this->message = __('dynsettings::dynsettings.save_message');
        $this->showMessage = true;
    }

    public function dismissSaveMessage() {
        $this->showMessage = false;
    }

    public function showRemoveModalBtn($setting) {
        $this->deleteItem = DynSettings::getKeyData($setting);
        $this->showRemoveModal = true;
    }

    public function deleteSetting() {
        if (DynSettings::deleteByKey($this->deleteItem->key)) {
            $this->message = __('dynsettings::dynsettings.delete_message');
            $this->showMessage = true;
            $this->resetFields();
            $this->getData();
            $this->closeModals();
        }
    }

    public function showAddModalBtn($edit=null) {
        
        $this->isEdit = false;

        if (!is_null($edit)) {
            $setting = DynSettings::getKeyData($edit);

            $this->allAssocs = DynSettings::getAssocs($setting->group);

            $this->editID = $setting->id;
            $this->key = $setting->key;
            $this->type = $setting->type;
            $this->name = $setting->name;
            $this->description = $setting->description;
            $this->group = $setting->group;
            $this->assoc = $setting->associate_with;
            $this->value = $setting->value;
            $this->isEdit = true;
        }
        $this->showAddModal = true;
        
    }

    public function addSetting() {
        $this->validate();

        $value = 'n/v';
        if ($this->type == 'bool') {
            $value = false;
        }

        if ($this->isEdit) {
            if ($this->type == 'bool' && !is_bool($this->value)) {
                $value = false;
            } else {
                $value = $this->value;
            }
            $model = DynSettings::getModelByID($this->editID);
            $model->key = $this->key;
            $model->name = $this->name;
            $model->type = $this->type;
            $model->group = $this->group;
            $model->associate_with = $this->assoc;
            $model->description = $this->description;
            $model->value = $value;
            $model->save();
            $this->message = __('dynsettings::dynsettings.mod_setting');
            $this->showMessage = true;
            $this->closeModals();
        } else {
            if (DynSettings::add($this->key,$value,$this->type,$this->name,$this->group,$this->assoc,$this->description)) {
                $this->message = __('dynsettings::dynsettings.add_setting');
                $this->showMessage = true;
                $this->closeModals();
            }
        }
    }

    public function addGroup($groupName) {
        $this->allGroups[] = $groupName;
    }

    public function addAssoc($assocName) {
        $this->allAssocs[] = $assocName;
    }

    public function closeModals() {
        $this->resetValidation();
        $this->resetFields();
        $this->getData();
        $this->showRemoveModal = false;
        $this->showAddModal = false;
    }


    public function render()
    {
        if ($this->wire_ui) {
            $view = 'DynSettingsPackage::dynamic-settings-wire_ui';
        } else {
            $view = 'DynSettingsPackage::dynamic-settings';
        }

        if ($this->layout_mode == 'fullpage') {
            if (is_bool($this->layout_path) || is_null($this->layout_path) || empty($this->layout_path)) {
                throw new Exception("Layout mode: fullpage, no layout blade provided. Check dynsettings config file.");
            }
            return view($view)->layout($this->layout_path);
        }
        
        return view($view);
    }
}
