@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.View Course'),[__('common.home'), __('Course.Course List'), __('Course.View Course')],['dashboard','Course-Course-list']) @endphp
    @section('back_button')
        @php echo back_button('dynamic-course-pages-list') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('Course-Course-edit', ['id' => encrypt($data->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3 space-y-6">
        <div class="flex  mb-5">
            <div class="flex flex-col items-center">
                <div class="bg-white p-3 border rounded">
                    {!! DNS1D::getBarcodeHTML($data->sku_code, 'C128') !!}
                </div>
                <p class="mt-2 text-xs text-gray-600">{{ $data->sku_code ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            {{-- Course Info Table --}}
            <div class="lg:col-span-2 overflow-x-auto border rounded">
                <table class="w-full text-sm border-collapse">
                    <tbody>
                        {{-- Basic Info --}}
                        <tr class="bg-gray-50">
                            <td colspan="2" class="p-3 font-bold text-black">Basic Information</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">Course Name</td>
                            <td class="p-3 font-medium">{{ $data?->name ?? 'N/A' }}</td>
                        </tr>

                         <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">Category</td>
                            <td class="p-3 font-medium">{{ $data?->category?->name ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">Sub Category</td>
                            <td class="p-3 font-medium">{{ $data?->subcategory?->name ?? 'N/A' }}</td>
                        </tr>
                          <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">UOM</td>
                            <td class="p-3 font-medium">{{ $data?->unit?->name ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">Brand</td>
                            <td class="p-3 font-medium">{{ $data?->brand_name ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">Model No</td>
                            <td class="p-3 font-medium">{{ $data?->model_no ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">Cost</td>
                            <td class="p-3  font-medium">
                                {{ $data?->cost_price ?? '0.0' }}
                            </td>
                        </tr>
                        {{-- <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('Course.Selling Price') }}</td>
                            <td class="p-3  font-medium">
                                {{ $data?->selling_price ?? '0.0' }}
                            </td>
                        </tr>
                          <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('gl-accounts.Tax Type') }}</td>
                            <td class="p-3 font-medium">{{ $data?->taxType?->tax_type_name ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('gl-accounts.Tax Code') }}</td>
                            <td class="p-3 font-medium">{{ $data?->tax?->tax_code ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('Course.Reorder Level') }}</td>
                            <td class="p-3 font-medium">{{ $data?->reorder_level ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('Course.Safety Stock') }}</td>
                            <td class="p-3 font-medium">{{ $data?->safety_stock ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('Course.Lead Time Days') }}</td>
                            <td class="p-3 font-medium">{{ $data?->lead_time_days ?? 'N/A' }}</td>
                        </tr> --}}
                        </tr>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ __('Course.Status') }}</td>
                            <td class="p-3 font-medium">{!! status_check($data?->status) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="lg:col-span-1 flex flex-col gap-4 w-full">
                {{-- Course Image --}}
                <div class="w-full border rounded overflow-hidden bg-white">
                    <div class="w-full h-64 flex justify-center items-center bg-gray-100">
                        @if ($data?->Course_image)
                            <div class=" p-2 rounded-md">
                                <a target="_blank"
                                    href="{{ route('secure-documents', ['path' => $data?->Course_image]) }}"><img
                                        class=" "
                                        src="{{ route('secure-documents', ['path' => $data?->Course_image]) }}"
                                         alt=""></a>
                            </div>
                        @else
                            <i class="ri-folder-image-line text-[100px]"></i>
                        @endif

                    </div>
                </div>
                {{-- Course Description --}}
                <div class="w-full border rounded overflow-hidden bg-white">
                    <div class="bg-gray-50 text-sm font-bold text-black p-3">
                        {{ __('Course.Course Description') }}
                    </div>
                    <div class="p-3 text-gray-700 text-sm leading-relaxed hover:bg-gray-50">
                        {!! $data->description ?? '<span class="text-gray-400">No description available</span>' !!}
                    </div>
                </div>
                {{-- Warehouse & Site Section --}}
                <div class="w-full border rounded overflow-hidden bg-white">
                    <table class="w-full text-sm border-collapse">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td colspan="2" class="p-3 font-bold text-black">Warehouse & Site Information
                                </td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium w-1/4">Warehouse</td>
                                <td class="p-3">{{ $data?->warehouse?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium">Site</td>
                                <td class="p-3 font-semibold">{{ $data?->site?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium">Location</td>
                                <td class="p-3 font-semibold text-gray-900">{{ $data?->location?->location_name ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Supplier --}}
                <div class="w-full border rounded overflow-hidden bg-white">
                    <table class="w-full text-sm border-collapse">
                        <tbody>
                            <tr class="bg-gray-50">
                                <td colspan="2" class="p-3 font-bold text-black">Supplier List
                                </td>
                            </tr>

                            @foreach ($data?->CourseSupplier as $item)
                            <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-semibold text-gray-900">{{ $item?->supplier?->name ?? 'N/A' }}</td>

                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@include('pages.common.toggle-sectionjs')
@include('layouts.accordin')
@endsection
