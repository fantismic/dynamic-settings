<section>
  <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-lg ">
    {{-- Header --}}
    <div class="p-1 flex justify-between">
      {{-- Header title --}}
      <div class="text-2xl dark:text-gray-200">{{ucfirst(__('dynsettings::dynsettings.settings'))}}</div>
      {{-- Buttons --}}
      <div>
        <button wire:click="showAddModalBtn" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-800 dark:hover:bg-blue-900 focus:outline-none dark:focus:ring-blue-900">
          {{ucfirst(__('dynsettings::dynsettings.add'))}}
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
                            @error('settingsArr.'.$setting['key']) <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.invalid_list'))}}</div> @enderror
                          </div>
                          @break
                        @case('string')
                          <div class="mb-3">
                            <label for="{{$setting['key']}}" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{$setting['name']}}</label>
                            <input wire:model="settingsArr.{{$setting['key']}}" type="text" id="{{$setting['key']}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" />
                            @error('settingsArr.'.$setting['key']) <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.invalid_string'))}}</div> @enderror
                          </div>
                          @break
                        @case('integer')
                        @case('double')
                          <div class="mb-3">
                            <label for="{{$setting['key']}}" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{$setting['name']}}</label>
                            <input wire:model="settingsArr.{{$setting['key']}}" type="number" id="{{$setting['key']}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-slate-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" />
                            @error('settingsArr.'.$setting['key']) <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.invalid_integer'))}}</div> @enderror
                          </div>
                          @break
                        @case('boolean')
                          <div class="mt-2">
                            <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model.live="settingsArr.{{$setting['key']}}">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-400 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-700"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{$setting['name']}}</span>
                            </label>
                          </div>
                          @break                               
                      @endswitch
                      <div class="text-sm font-mono italic mt-1 dark:text-gray-400">{{$setting['key']}}</div>
                    {{-- End setting first column --}}
                    </div>
                    {{-- Setting second column --}}
                    <div class="w-1/2 tracking-wider pl-5 dark:text-gray-200 border-l border-l-gray-300 dark:border-l-gray-600 text-sm">
                      <div class="flex justify-between">
                        <div class="place-content-center">
                          @if($setting['type'] == "array" && $alert_array_format)
                            <b>{{__('dynsettings::dynsettings.alert_array_format')}}</b><br>
                          @endif
                          {!! $setting['description'] !!}
                        </div>
                        <div class="flex">
                          
                          <div class="cursor-pointer" wire:click="saveSetting('{{$setting['key']}}')"><svg class="w-5 h-5 mr-1 text-green-500 dark:text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 22 22"><path stroke="currentColor" stroke-linecap="round" stroke-width="1" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/></svg></div>
                          <div class="cursor-pointer" wire:click="showAddModalBtn('{{$setting['key']}}')"><svg height="20" class="text-blue-500 feather feather-edit" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
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
            </div>
          @endforeach
        {{-- End card content --}}
        </div>
      {{-- End card body --}}
      </div>
    @endforeach
  </div>
  
  @if($showRemoveModal)
  <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-700 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="">
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-gray-200" id="modal-title">{{ucfirst(__('dynsettings::dynsettings.delete'))}} {{__('dynsettings::dynsettings.setting')}}</h3>
                <div class="mt-3">
                  <label for="setting-remove-item" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('dynsettings::dynsettings.delete_confirmation_message',['name' => $deleteItem->name, 'key' => $deleteItem->key])}}</label>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button wire:click="deleteSetting" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">{{ucfirst(__('dynsettings::dynsettings.delete'))}}</button>
            <button wire:click="closeModals" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">{{ucfirst(__('dynsettings::dynsettings.cancel'))}}</button>
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
                <h3 class="font-semibold text-xl leading-6 text-gray-900 dark:text-gray-200" id="modal-title">{{$isEdit ? ucfirst(__('dynsettings::dynsettings.update')) : ucfirst(__('dynsettings::dynsettings.add'))}} {{__('dynsettings::dynsettings.setting')}}</h3>
                <div class="space-y-3 mt-3">
                  <div>
                    <label for="name" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(__('dynsettings::dynsettings.name'))}}</label>
                    <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('name')
                      <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.mandatory_field'))}}</div>
                    @enderror
                  </div>
                  <div>
                    <label for="key" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(__('dynsettings::dynsettings.key'))}}</label>
                    <input wire:model="key" type="text" id="key" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('key')
                      <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.mandatory_field'))}}</div>
                    @enderror
                  </div>
                  <div>
                    <label for="setting-remove-item" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(__('dynsettings::dynsettings.type'))}}</label>
                    <select wire:model="type" id="setting-remove-item" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option selected value="0">{{__('dynsettings::dynsettings.type')}}</option>
                      @foreach ($allTypes as $typeOption)
                        <option value="{{$typeOption}}">{{$typeOption}}</option>
                      @endforeach
                    </select>
                    @error('type')
                      <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.mandatory_field'))}}</div>
                    @enderror
                  </div>
                  <div>
                    <label for="group" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(__('dynsettings::dynsettings.group'))}}</label>
                    <input wire:model.live="group" autocomplete="off" list="groups" type="text" id="group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <datalist id="groups">
                      @foreach ($allGroups as $groupOption)
                        <option>{{$groupOption}}</option>
                      @endforeach
                    </datalist>
                    @error('group')
                      <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.mandatory_field'))}}</div>
                    @enderror
                  </div>
                  <div>
                    <label for="assoc" class="block ml-1 mb-1 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(__('dynsettings::dynsettings.assoc'))}}</label>
                    <input wire:model.live="assoc" autocomplete="off" list="assocs" type="text" id="group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <datalist id="assocs">
                      @foreach ($allAssocs as $assocOption)
                        <option>{{$assocOption}}</option>
                      @endforeach
                    </datalist>
                    @error('assoc')
                      <div class="text-sm italic text-red-500">{{ucfirst(__('dynsettings::dynsettings.mandatory_field'))}}</div>
                    @enderror
                  </div>
                  <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ucfirst(__('dynsettings::dynsettings.description'))}}</label>
                    <textarea wire:model="description" id="description" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-600 focus:border-indigo-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
            <button wire:click="addSetting" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{$isEdit ? ucfirst(__('dynsettings::dynsettings.update')) : ucfirst(__('dynsettings::dynsettings.add'))}}</button>
            <button wire:click="closeModals" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">{{ucfirst(__('dynsettings::dynsettings.cancel'))}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif


  @if($showConfirmationModal)
  <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-700 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="">
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-gray-200" id="modal-title">{{$message}}</h3>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button wire:click="closeModals" type="button" class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">{{ucfirst(__('dynsettings::dynsettings.accept'))}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</section>