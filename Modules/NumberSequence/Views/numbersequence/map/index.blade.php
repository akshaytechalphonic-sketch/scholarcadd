@extends('layouts.app')
@include('layouts.select2css')
@section('content')
    <div>
        @php echo breadcrums('common.numbersequence-maping',['common.home','common.numbersequence-maping'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
</div>

<div class="text-xs w-full border bg-white rounded-md ">
    <div class="table_wrapper  max-h-[70vh] overflow-y-auto">
        <table id="data-table-list" class="display table-auto w-full py-1 ">
            <thead class="bg-primary text-white text-xs ">
                <tr class="text-start">
                    <th class="py-2 px-2">{{ __('company.area') }}</th>
                    <th class="py-2 px-2">{{ __('company.number-sequence') }}</th>
                    <th class="py-2 px-2">{{ __('company.formate') }}</th>
                    <th class="py-2 px-2">{{ __('company.next') }}</th>
                    <th class="py-2 px-2">{{ __('company.mapped-on') }}</th>
                    <th class="py-2 px-2">{{ __('common.created_by') }}</th>
                    <th class="py-2 px-2">{{ __('common.action') }}</th>

                </tr>
            </thead>
            <tbody id="myTable">
                @php $i = 0; @endphp
                @foreach ($data as $no_seq)
                    <tr>
                        <td class="custome-tables">{{ $no_seq?->name }}</td>
                        <td class="custome-tables"> {{ $map_time_data[$i]?->name }} </td>
                        <td class="custome-tables"> {{ $map_time_data[$i]?->format }} </td>
                        <td class="custome-tables"> {{ $map_time_data[$i]?->next }} </td>
                        <td class="custome-tables"> @php echo (date_formate($map_time_data[$i]?->maping_date)) @endphp </td>
                        <td class="custome-tables"> {{ $no_seq?->creator?->name }} </td>
                        @php $encr_id = encrypt($no_seq?->id); @endphp
                        <td class="custome-tables ">
                            <div class="dropdown relative inline-block"> <button onClick="toggleDropdown(this)"
                                    class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                    <i class="ri-more-2-fill ml-2"></i> </button>
                                <div
                                    class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-48">
                                    <ul class="text-sm text-black">
                                        <li>
                                            <button  onclick="togglview_map_model('{{ $encr_id }}')"
                                                class="flex  gap-2 items-center w-full  py-2 px-2 bg-secondary hover:bg-gray-100 "> <i
                                                    class="ri-user-line mt-1"></i>
                                                {{ __('common.numbersequence-map') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php $i++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>



@include('numbersequence::numbersequence.map.modal')
@endsection
@section('scripts')
@include('layouts.select2js')
 <script>
    function togglview_map_model(w_id = null) {
        const mapSidebar = document.getElementById('map-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        const isOpen = !mapSidebar.classList.contains('translate-x-full');
        if (isOpen) {
            mapSidebar.classList.add('translate-x-full');
        } else {
            mapSidebar.classList.remove('translate-x-full');
        }

        function closeAllSidebars() {
            const mapSidebar = document.getElementById('map-sidebar');
            const overlay = document.querySelector(".drawer-overlay");
            mapSidebar.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        }

        function toggleOverlay() {
            const mapSidebar = document.getElementById('map-sidebar');
            const overlay = document.querySelector(".drawer-overlay");
            if (
                !mapSidebar.classList.contains('translate-x-full')
            ) {
                overlay.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
            }
        }
        document.querySelector(".drawer-overlay").addEventListener('click', closeAllSidebars);
        toggleOverlay();
        $('#formdata').val(w_id);
    }
</script>
@endsection
