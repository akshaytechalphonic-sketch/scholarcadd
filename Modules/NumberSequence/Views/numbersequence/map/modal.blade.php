
<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden  z-50"></div>
<div id="map-sidebar" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white w-80" tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('common.numbersequence-map') }}
        </h5>
        <button type="button" onclick="togglview_map_model()" class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>

    <form action="{{ route('numbersequence-maping') }}" class="basic-form" method="POST">
        @csrf
        <div class="p-4">

            <div class="w-full">
                <input type="hidden" name="formid" id="formdata" class="form-control" />
                <label class="text-sm">{{ __('common.numbersequence-map') }}</label>
                <select name='sequencemaping'  required style="width:100%" class="select-single mt-2 ">
                    <option value="">{{ __('common.chooseoption') }}</option>
                    @foreach ($no_seqs as $sequence)
                        <option value="{{ $sequence?->id }}"> {{ $sequence?->code }} / {{ $sequence?->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="togglview_map_model()" class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                    <button type="submit" onclick="togglview_modelSidebar()" class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                        {{ __('company.map') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
