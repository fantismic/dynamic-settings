<section>
  <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg ">
    {{-- Header --}}
    <div class="p-1 flex justify-between">
      {{-- Header title --}}
      <div class="text-2xl dark:text-gray-200">{{ucfirst(__('dynsettings::dynsettings.settings'))}}</div>
      {{-- Header message --}}
      <div>
        @if($showMessage)
          <div id="alert" class="flex items-center p-1  text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
              {{ $message }}
            </div>
            <button wire:click="dismissSaveMessage" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-2 h-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
            </button>
          </div>
        @endif
      {{-- End header message --}}
      </div>
      {{-- Buttons --}}
      <div>
        <x-button wire:click="save" class="ml-2" light color="positive" label="{{ucfirst(__('dynsettings::dynsettings.save'))}}" />
        <x-button wire:click="showAddModalBtn" class="ml-2" light color="info" label="{{ucfirst(__('dynsettings::dynsettings.add'))}}" />
      {{-- End buttons --}}
      </div>
    {{-- End header --}}
    </div>


    {{-- Body --}}
    @foreach ($groups as $name => $group)
      {{-- Card body --}}
      <div class="bg-slate-100 dark:bg-gray-700 rounded-lg">

        {{-- Card Content --}}
        <x-card title="{{ucFirst($name)}}">
          @foreach ($group as $name => $settings)
            {{-- Assoc card --}}
            <x-card title="{{ucFirst($name)}}" class="border p-1 mb-4 dark:border-gray-600">
              {{-- Setting item --}}
              <div class="space-y-4">
                @foreach ($settings as $setting)
                  {{-- Setting item 1 --}}
                  <div class="flex space-x-10">
                    {{-- Setting first column  --}}
                    <div class="w-1/2">
                      @switch($setting['type'])
                        @case('array')
                            <x-textarea label="{{$setting['name']}}" wire:model="settingsArr.{{$setting['key']}}" id="{{$setting['key']}}" rows="2" />
                          @break
                        @case('string')
                        @case('int')
                            <x-input label="{{$setting['name']}}" wire:model="settingsArr.{{$setting['key']}}" type="text" id="{{$setting['key']}}"/>
                          @break
                        @case('bool')
                          <x-toggle lg label="{{$setting['name']}}" wire:model.live="settingsArr.{{$setting['key']}}" />
                          @break                               
                      @endswitch
                      <div class="text-sm italic font-mono mt-1">{{$setting['key']}}</div>
                    {{-- End setting first column --}}
                    </div>
                    {{-- Setting second column --}}
                    <div class="tracking-wider w-1/2 place-content-center pl-5 dark:text-gray-200 border-l border-l-gray-300 dark:border-l-gray-600 text-sm">
                      <div class="flex justify-between">
                        <div class="place-content-center">
                          @if($setting['type'] == "array" && $alert_array_format)
                          <b>{{__('dynsettings::dynsettings.alert_array_format')}}</b><br>
                          @endif
                          {!! $setting['description'] !!}
                        </div>
                        <div class="flex">
                          <div class="cursor-pointer" wire:click="showAddModalBtn('{{$setting['key']}}')"><?xml version="1.0" ?><svg height="20" class="text-blue-500 feather feather-edit" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                          <div class="cursor-pointer" wire:click="showRemoveModalBtn('{{$setting['key']}}')"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0,0,300,300"><g fill="red" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M10,2l-1,1h-5v2h1v15c0,0.52222 0.19133,1.05461 0.56836,1.43164c0.37703,0.37703 0.90942,0.56836 1.43164,0.56836h10c0.52222,0 1.05461,-0.19133 1.43164,-0.56836c0.37703,-0.37703 0.56836,-0.90942 0.56836,-1.43164v-15h1v-2h-5l-1,-1zM7,5h10v15h-10zM9,7v11h2v-11zM13,7v11h2v-11z"></path></g></g></svg></div>
                        </div>
                      </div>
                    {{-- End setting second column --}}
                    </div>
                  {{-- Setting item 1 --}}
                  </div>
                @endforeach
              {{-- End setting item --}}
              </div>
            {{-- End assoc card --}}
            </x-card>
          @endforeach
        {{-- End card content --}}
        </x-card>
      {{-- End card body --}}
      </div>
    @endforeach
  </div>
  

  {{-- Modals --}}
@if ($showRemoveModal)
  <x-modal name="showRemoveModal" wire:model="showRemoveModal" width="6xl" align="center" z-10>
    <x-card class="space-y-4 space-x-3 min-w-[500px]">
      <div class="text-xl mb-4">{{ucfirst(__('dynsettings::dynsettings.delete'))}} {{__('dynsettings::dynsettings.setting')}}</div>
      <div class="flex space-x-3 mb-4">
        <label for="setting-remove-item" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('dynsettings::dynsettings.delete_confirmation_message',['name' => $deleteItem->name, 'key' => $deleteItem->key])}}</label>
      <div class="flex space-x-2 justify-end mt-3">
        <div class="mt-3"><x-button negative light label="{{ucfirst(__('dynsettings::dynsettings.delete'))}}" wire:click="deleteSetting"></x-button></div>
        <div class="mt-3"><x-button light label="{{ucfirst(__('dynsettings::dynsettings.cancel'))}}" x-on:click="close"></x-button></div>
      </div>
    </x-card>
  </x-modal>
@endif

  <x-modal name="showAddModal" wire:model="showAddModal" width="6xl" align="center">
    <x-card class="min-w-[600px]">
      <div class="text-xl mb-4">{{$isEdit ? ucfirst(__('dynsettings::dynsettings.update')) : ucfirst(__('dynsettings::dynsettings.add'))}} {{__('dynsettings::dynsettings.setting')}}</div>
      <div class="space-y-3 mb-4 ">

        <x-input label="{{ucfirst(__('dynsettings::dynsettings.name'))}}" wire:model="name" />

        <x-input label="{{ucfirst(__('dynsettings::dynsettings.key'))}}" wire:model="key" />
        
        <x-select 
          label="{{ucfirst(__('dynsettings::dynsettings.type'))}}"
          :options="$allTypes"
          wire:model="type"
          :searchable="true"
          :min-items-for-search="1"
        />
        <x-select 
          label="{{ucfirst(__('dynsettings::dynsettings.group'))}}"
          :options="$allGroups"
          wire:model.live="group"
          :searchable="true"
          :min-items-for-search="1"
        >
          <x-slot name="afterOptions" class="flex justify-center p-2" x-show="displayOptions.length < 5">
              <x-button
                  x-on:click="$wire.addGroup(`${search}`)"
                  primary flat full>
                  <span x-html="`Add group <b>${search}</b>`"></span>
              </x-button>
          </x-slot>
        </x-select>
        <x-select 
          label="{{ucfirst(__('dynsettings::dynsettings.assoc'))}}"
          :options="$allAssocs"
          wire:model.live="assoc"
          :searchable="true"
          :min-items-for-search="1"
          >
          <x-slot name="afterOptions" class="flex justify-center p-2" x-show="displayOptions.length < 5">
              <x-button
                  x-on:click="$wire.addAssoc(`${search}`)"
                  primary flat full>
                  <span x-html="`Add assoc <b>${search}</b>`"></span>
              </x-button>
          </x-slot>
        </x-select>

        <x-textarea rows="2" label="{{__('Description')}}" wire:model="description" />
      </div>

      <div class="flex space-x-2 justify-end">
        <div class="mt-3"><x-button info light label="{{$isEdit ? ucfirst(__('dynsettings::dynsettings.update')) : ucfirst(__('dynsettings::dynsettings.add'))}}" wire:click="addSetting"></x-button></div>
        <div class="mt-3"><x-button light label="{{ucfirst(__('dynsettings::dynsettings.cancel'))}}" x-on:click="close"></x-button></div>
      </div>
    </x-card>
  </x-modal>

</section>