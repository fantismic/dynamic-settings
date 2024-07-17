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

    public $settingsArr = [];
    public $settingsAll = [];

    public $showMessage=false;
    public $message;

    public $showRemoveModal=false;
    public $showAddModal=false;

    public $deleteItem = "0";

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
    
    public $allTypes = [];
    public $allGroups = [];
    public $allAssocs = [];
    
    public $groups = [];

    public function mount() {
        
        $this->layout_mode = config('dynsettings.layout_mode', 'component');
        $this->layout_path = config('dynsettings.layout_path', 'layouts.app');

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
        $this->message = 'Changes saved!';
        $this->showMessage = true;
    }

    public function dismissSaveMessage() {
        $this->showMessage = false;
    }

    public function showRemoveModalBtn() {
        $this->showRemoveModal = true;
    }

    public function deleteSetting() {
        if ($this->deleteItem == "0") {
            return false;
        }
        if (DynSettings::deleteByKey($this->deleteItem)) {
            $this->message = 'Setting '.$this->deleteItem. ' has been removed!';
            $this->showMessage = true;
            $this->resetFields();
            $this->getData();
            $this->closeModals();
        }
    }

    public function showAddModalBtn() {
        $this->showAddModal = true;
    }

    public function addSetting() {
        $this->validate();

        if (DynSettings::add($this->key,'empty',$this->type,$this->name,$this->group,$this->assoc,$this->description)) {
            $this->message = 'Setting '.$this->name. ' has been created!';
            $this->showMessage = true;
            $this->resetFields();
            $this->getData();
            $this->closeModals();
        }
        
    }

    public function addGroup($groupName) {
        $this->allGroups[] = $groupName;
    }

    public function addAssoc($assocName) {
        $this->allAssocs[] = $assocName;
    }

    public function closeModals() {
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
