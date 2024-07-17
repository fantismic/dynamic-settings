<section>
  <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-lg ">
    {{-- Header --}}
    <div class="p-1 flex justify-between">
      {{-- Header title --}}
      <div class="text-2xl dark:text-gray-200">Settings</div>
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
        <x-button wire:click="save" class="ml-2" light color="positive" label="{{__('Save')}}" />
        <x-button wire:click="showAddModalBtn" class="ml-2" light color="info" label="{{__('Add')}}" />
        <x-button wire:click="showRemoveModalBtn" class="ml-2" light color="negative" label="{{__('Remove')}}" />
      {{-- End buttons --}}
      </div>
    {{-- End header --}}
    </div>


    {{-- Body --}}
    @foreach ($groups as $name => $group)
      {{-- Card body --}}
      <div class="bg-slate-100 dark:bg-gray-700 rounded-lg pb-1 mb-3">

        {{-- Card Content --}}
        <x-card title="{{ucFirst($name)}}">
          @foreach ($group as $name => $settings)
            {{-- Assoc card --}}
            <x-card title="{{ucFirst($name)}}" class="border p-3 dark:border-gray-600">
              {{-- Setting item --}}
              <div class="space-y-4">
                @foreach ($settings as $setting)
                  {{-- Setting item 1 --}}
                  <div class="flex space-x-10 m-3">
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
                    {{-- End setting first column --}}
                    </div>
                    {{-- Setting second column --}}
                    <div class="w-1/2 place-content-center pl-5 dark:text-gray-200 border-l border-l-gray-300 dark:border-l-gray-600 text-sm italic">
                      @if($setting['type'] == "array")
                      <b>Always comma separated values!</b><br>
                      @endif
                      {{$setting['description']}}
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

  <x-modal name="showRemoveModal" wire:model="showRemoveModal" width="6xl" align="center" z-10>
    <x-card class="space-y-4 space-x-3 min-w-[500px]">
      <div class="text-xl mb-4">{{__('Delete setting')}}</div>
      <div class="flex space-x-3 mb-4">
        <x-select 
          label="{{__('Choose a setting to delete')}}"
          :options="$settingsAll"
          wire:model="deleteItem"
          option-value="key"
          option-label="name"
          :searchable="true"
          :min-items-for-search="1"
        />
      </div>

      <div class="flex space-x-2 justify-end">
        <div class="mt-3"><x-button negative light label="{{__('Delete')}}" wire:click="deleteSetting"></x-button></div>
        <div class="mt-3"><x-button light label="{{__('Cancel')}}" x-on:click="close"></x-button></div>
      </div>
    </x-card>
  </x-modal>


  <x-modal name="showAddModal" wire:model="showAddModal" width="6xl" align="center">
    <x-card class="min-w-[600px]">
      <div class="text-xl mb-4">{{__('Delete setting')}}</div>
      <div class="space-y-3 mb-4 ">

        <x-input label="{{__('Name')}}" wire:model="name" />

        <x-input label="{{__('Key')}}" wire:model="key" />
        
        <x-select 
          label="{{__('Choose a type')}}"
          :options="$allTypes"
          wire:model="type"
          :searchable="true"
          :min-items-for-search="1"
        />
        <x-select 
          label="{{__('Choose a group')}}"
          :options="$allGroups"
          wire:model="group"
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
          label="{{__('Choose an assoc')}}"
          :options="$allAssocs"
          wire:model="assoc"
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
        <div class="mt-3"><x-button info light label="{{__('Add')}}" wire:click="addSetting"></x-button></div>
        <div class="mt-3"><x-button light label="{{__('Cancel')}}" x-on:click="close"></x-button></div>
      </div>
    </x-card>
  </x-modal>

</section> 
{{-- <hr class="h-px my-3 bg-gray-200 border-0 dark:bg-gray-700"> --}}