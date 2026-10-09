@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Landing Page Sections ',['common.home','Landing Page Sections'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('landingpage.index') @endphp
    @endsection
</div>
<div class="mt-4">


    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md mb-2"
        open>
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Add Section</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <form action="{{ route('section.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">

                    <div class="w-full">
                        <label for=""
                            class="text-sm font-semibold text-black">{{ __('common.Select Category') }}
                            {{-- <span class="text-red-500 font-bold">*</span></label> --}}
                        <select class="select-single mt-1 form-input" style="width:100%" name="page_id">
                            <option value="">{{ __('common.chooseoption') }}</option>
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}"> {{ $page->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Section</label>
                        <input type="text" class="input-box form-input mt-1 form-control-file" name="section">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label</label>
                        <input type="text" class="input-box form-input mt-1 form-control-file" name="label">
                    </div>

                    <div class="w-full relative z-10">
                        <label class="text-sm font-semibold text-black">Heading</label>
                        <input type="text" class="input-box form-input mt-1" name="heading">
                    </div>



                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Button Label</label>
                        <input class="w-full form-input description-validation mt-1" name="button_label"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>


                    <!-- Image 1 -->
                    <div class="w-full">

                        <label class="text-sm font-semibold text-black">Image One</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_one"
                            onChange="validateFile(this,'image_document',5);">
                    </div>

                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label One</label>
                        <input class="w-full form-input description-validation mt-1" name="label_one"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 2 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Two</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_two"
                            onChange="validateFile(this,'image_document',5);">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Two</label>
                        <input class="w-full form-input description-validation mt-1" name="label_two"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 3 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Three</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_three"
                            onChange="validateFile(this,'image_document',5);">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Three</label>
                        <input class="w-full form-input description-validation mt-1" name="label_three"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 4 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Four</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_four"
                            onChange="validateFile(this,'image_document',5);">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Four</label>
                        <input class="w-full form-input description-validation mt-1" name="label_four"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>

                    <!-- Image 5 -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Image Five</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="image_five"
                            onChange="validateFile(this,'image_document',5);">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Label Five </label>
                        <input class="w-full form-input description-validation mt-1" name="label_five"
                            data-maxlength="200">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                    </div>
                </div>
                <div class="w-full relative z-10">
                    <label class="text-sm font-semibold text-black">Description</label>
                    <textarea class="w-full summernote form-input description-validation mt-1" name="description">
    </textarea>
                </div>
                <div class="flex justify-end mt-4">
                    <div class="flex gap-2">
                        <button type="reset" class="btn-primary-sm flex items-center gap-1">
                            <i class="ri-refresh-line"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </details>


    {{-- <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300            hover:shadow-md mb-2">
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Section Five</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Label </label>
                    <input type="text" class="input-box form-input mt-1 form-control-file" name="">

                </div>
                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Heading
                    </label>
                    <input type="text" class="input-box form-input mt-1" name="" required>
                </div>
                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Description </label>
                    <input class="w-full form-input description-validation" value="" name="" required
                        data-maxlength="200">
                    <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                </div>
                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Button Label </label>
                    <input class="w-full form-input description-validation" value="" name="" required
                        data-maxlength="200">
                    <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/200</div>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Section One Image </label>
                    <input type="file" class="input-box form-input mt-1 form-control-file" name=""
                        onChange="validateFile(this,'image_document',5);" multiple>
                </div>


            </div>
        </div>
    </details>



    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300            hover:shadow-md mb-2">
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Section Six</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">

                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Heading
                    </label>
                    <input type="text" class="input-box form-input mt-1" name="" required>
                </div>

                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Testimonials Heading
                    </label>
                    <input type="text" class="input-box form-input mt-1" name="" required>
                </div>


                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">Testimonials Image </label>
                    <input type="file" class="input-box form-input mt-1 form-control-file" name=""
                        onChange="validateFile(this,'image_document',5);" multiple>
                </div>


                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Contact Us Label
                    </label>
                    <input type="text" class="input-box form-input mt-1" name="" required>
                </div>


                <div class="w-full relative z-10">
                    <label for="" class="text-sm font-semibold text-black">Contact Us Heading
                    </label>
                    <input type="text" class="input-box form-input mt-1" name="" required>
                </div>




            </div>
        </div>
    </details>
 --}}

    <section class="px-3">
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-gray-200 px-5  py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">All Sections List</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <div class="overflow-x-auto bg-white rounded-md border p-4">
            <table id="sectionTable" class="display table-auto w-full py-1 text-xs" style="width:100%">
                <thead class="bg-primary text-white ">
                    <tr>
                        {{-- <th class="px-4 py-3 whitespace-nowrap">ID</th> --}}
                        <th class="px-4 py-3 whitespace-nowrap">Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Label</th>
                        <th class="px-4 py-3 whitespace-nowrap">Heading</th>
                        <th class="px-4 py-3 whitespace-nowrap">Description</th>
                        <th class="px-4 py-3 whitespace-nowrap">Button Label</th>
                        <th class="px-4 py-3 whitespace-nowrap">Created At</th>
                        <th class="px-4 py-3 whitespace-nowrap">Images</th>
                        <th class="px-4 py-3 whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                </tbody>
            </table>
        </div>
    </section>





@endsection
@section('scripts')
    @include('layouts.summer_note_js')
    <script>
        $(function() {
            $('#sectionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('section.data.ajax') }}",
                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id'
                    // },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'label',
                        name: 'label'
                    },
                    {
                        data: 'heading',
                        name: 'heading'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'button_label',
                        name: 'button_label'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'images',
                        name: 'images',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },

                ]
            });
        });
    </script>
@endsection
