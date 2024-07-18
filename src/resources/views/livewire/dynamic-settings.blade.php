<section>
  <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-lg ">
    {{-- Header --}}
    <div class="p-1 flex justify-between">
      {{-- Header title --}}
      <div class="text-2xl dark:text-gray-200">Settings</div>
      {{-- Header message --}}
      <div>
        @if($showMessage)
          <div id="alert-3" class="flex items-center p-2 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium min-w-60">
              {{ $message }}
            </div>
            <button wire:click="dismissSaveMessage" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
              <span class="sr-only">Close</span>
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
            </button>
          </div>
        @endif
      {{-- End header message --}}
      </div>
      {{-- Buttons --}}
      <div>
        <button wire:click="save" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-emerald-800 dark:hover:bg-green-900 dark:focus:ring-green-900">
          {{__('Save')}}
        </button>
        <button wire:click="showAddModalBtn" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-800 dark:hover:bg-blue-900 focus:outline-none dark:focus:ring-blue-900">
          {{__('Add')}}
        </button>
        <button wire:click="showRemoveModalBtn" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-rose-800 dark:hover:bg-red-900 dark:focus:ring-red-900">
          {{__('Remove')}}
        </button>
      {{-- End buttons --}}
      </div>
    {{-- End header --}}
    </div>


    {{-- Body --}}
    @foreach ($groups as $name => $group)
      {{-- Card body --}}
      <div class="rounded-md bg-white dark:bg-slate-800 dark:bg-opacity-50 shadow pb-3 mb-3">
      {{-- Card title --}}
        <div class="p-3 border-b-2 border-b-gray-300 dark:border-b-gray-600 mb-4">
          <p class="font-medium text-lg whitespace-normal text-gray-700 dark:text-gray-400">{{ucFirst($name)}}</p>
        </div>
        {{-- Card Content --}}
        <div class="">
          @foreach ($group as $name => $settings)
            {{-- Assoc card --}}
            <div class="rounded-md bg-white dark:bg-slate-800 shadow m-3 pb-3 border dark:border-gray-600">
              {{-- Assoc card title --}}
              <div class="p-3 border-b-2 border-b-gray-300 dark:border-b-gray-600 mb-4">
                <p class="font-medium text-base whitespace-normal text-gray-700 dark:text-gray-400">{{ucFirst($name)}}</p>
              </div>
              {{-- Setting item --}}
              <div class="space-y-4">
                @foreach ($settings as $setting)
                  {{-- Setting item 1 --}}
                  <div class="flex space-x-10 m-3">
                    {{-- Setting first column  --}}
                    <div class="w-1/2">
                      @switch($setting['type'])
                        @case('array')
                          <div>
                            <label for="{{$setting['key']}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$setting['name']}}</label>
                            <textarea wire:model="settingsArr.{{$setting['key']}}" id="{{$setting['key']}}" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-600 focus:border-indigo-600 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"></textarea>
                          </div>
                          @break
                        @case('string')
                        @case('int')
                          <div class="mb-3">
                            <label for="{{$setting['key']}}" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{$setting['name']}}</label>
                            <input wire:model="settingsArr.{{$setting['key']}}" type="text" id="{{$setting['key']}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" />
                          </div>
                          @break
                        @case('bool')
                          <div class="mt-2">
                            <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model.live="settingsArr.{{$setting['key']}}">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-400 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-700"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{$setting['name']}}</span>
                            </label>
                          </div>
                          @break                               
                      @endswitch
                      <div class="text-sm italic mt-1 dark:text-gray-400">{{$setting['key']}}</div>
                    {{-- End setting first column --}}
                    </div>
                    {{-- Setting second column --}}
                    <div class="w-1/2 place-content-center pl-5 dark:text-gray-200 border-l border-l-gray-300 dark:border-l-gray-600 text-sm italic">
                      @if($setting['type'] == "array" && $alert_array_format)
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
            </div>
          @endforeach
        {{-- End card content --}}
        </div>
      {{-- End card body --}}
      </div>
    @endforeach
  </div>
  

  {{-- Modals --}}
  @if($showRemoveModal)
  <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-700 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="">
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-gray-200" id="modal-title">{{__('Delete setting')}}</h3>
                <div class="">
                  <label for="setting-remove-item" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Choose a setting to delete')}}</label>
                  <select wire:model="deleteItem" id="setting-remove-item" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected value="0">{{__('Choose a setting to delete')}}</option>
                    @foreach ($settingsAll as $setting)
                      <option value="{{$setting->key}}">{{$setting->name}} ({{$setting->key}})</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button wire:click="deleteSetting" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">{{__('Delete')}}</button>
            <button wire:click="closeModals" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">{{__('Cancel')}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif


  @if($showAddModal)
  <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-700 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="">
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="font-semibold text-xl leading-6 text-gray-900 dark:text-gray-200" id="modal-title">{{__('Add setting')}}</h3>
                <div class="space-y-3 mt-3">
                  <div>
                    <label for="name" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{__('Name')}}</label>
                    <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('name')
                      <div class="text-sm italic text-red-500">Este campo es obligatorio</div>
                    @enderror
                  </div>
                  <div>
                    <label for="key" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{__('Key')}}</label>
                    <input wire:model="key" type="text" id="key" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('key')
                      <div class="text-sm italic text-red-500">Este campo es obligatorio</div>
                    @enderror
                  </div>
                  <div>
                    <label for="setting-remove-item" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Type')}}</label>
                    <select wire:model="type" id="setting-remove-item" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option selected value="0">{{__('Choose a setting to delete')}}</option>
                      @foreach ($allTypes as $typeOption)
                        <option value="{{$typeOption}}">{{$typeOption}}</option>
                      @endforeach
                    </select>
                    @error('type')
                      <div class="text-sm italic text-red-500">Este campo es obligatorio</div>
                    @enderror
                  </div>
                  <div>
                    <label for="group" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{__('Group')}}</label>
                    <input wire:model.live="group" autocomplete="off" list="groups" type="text" id="group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <datalist id="groups">
                      @foreach ($allGroups as $groupOption)
                        <option>{{$groupOption}}</option>
                      @endforeach
                    </datalist>
                    @error('group')
                      <div class="text-sm italic text-red-500">Este campo es obligatorio</div>
                    @enderror
                  </div>
                  <div>
                    <label for="assoc" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{__('Associated with')}}</label>
                    <input wire:model.live="assoc" autocomplete="off" list="assocs" type="text" id="group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <datalist id="assocs">
                      @foreach ($allAssocs as $assocOption)
                        <option>{{$assocOption}}</option>
                      @endforeach
                    </datalist>
                    @error('assoc')
                      <div class="text-sm italic text-red-500">Este campo es obligatorio</div>
                    @enderror
                  </div>
                  <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Description')}}</label>
                    <textarea wire:model="description" id="description" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-600 focus:border-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
            <button wire:click="addSetting" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{__('Add')}}</button>
            <button wire:click="closeModals" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">{{__('Cancel')}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</section>