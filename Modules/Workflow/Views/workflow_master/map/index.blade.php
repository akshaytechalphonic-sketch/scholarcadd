@extends('layouts.app')
@section('content')
    <div> 
        @php echo breadcrums('company.workflow-mapping',['common.home','company.workflow-mapping'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
</div>

<div class="text-xs w-full border bg-white rounded-md ">
    <div class="table_wrapper  max-h-[70vh] overflow-y-auto">
        <table id="data-table-list" class="display table-auto w-full py-1 ">
            <thead class="bg-primary text-white text-xs ">
                <tr class="text-start">
                    <th class="py-2 px-2">{{ __('company.mapped-for') }}</th>
                    <th class="py-2 px-2">{{ __('company.workflow') }}</th>
                    <th class="py-2 px-2">{{ __('company.version') }}</th>
                    <th class="py-2 px-2">{{ __('company.mapped-on') }}</th>
                    <th class="py-2 px-2">{{ __('company.actions') }} </th>
                </tr>
            </thead>
            <tbody id="myTable">
                @php $i = 0; @endphp
                @foreach ($data as $map)
                    <tr> 
                        <td class="custome-tables">{{ $map?->workflow_name }}</td>
                        <td class="custome-tables"> {{ $map_time_data[$i]?->workflow_name }} </td>
                        <td class="custome-tables"> {{ $map_time_data[$i]?->version }} </td>
                        <td class="custome-tables"> @php echo (date_formate($map_time_data[$i]?->mapping_date)) @endphp </td>
                        
                        @php $encr_id = encrypt($map?->id); @endphp
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
                                                {{ __('company.map-workflow') }}
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



@include('workflow::workflow_master.map.modal')
@endsection
@section('scripts') 
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
