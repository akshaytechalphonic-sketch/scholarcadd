@extends('layouts.app')
@section('css')
@endsection

@section('content')
{{-- @section('extra_buttons')
   <div data-section="deliveryNoteSection"
     class="hidden transform -translate-x-full transition-all duration-300 ease-in-out">
    <a href="/" class="topbarbtn">Delivery Note <i class="ri-arrow-right-s-line"></i></a>
    <a href="/" class="topbarbtn">Created By Me <i class="ri-arrow-right-s-line"></i></a>
    <a href="/" class="topbarbtn">Approved <i class="ri-arrow-right-s-line"></i></a>
    <a href="/" class="topbarbtn">Rejected <i class="ri-arrow-right-s-line"></i></a>
    <a href="/" class="topbarbtn">In Review <i class="ri-arrow-right-s-line"></i></a>
    <a href="/" class="topbarbtn">Draft <i class="ri-arrow-right-s-line"></i></a>
    <a href="/" class="topbarbtn">Invoice Generated <i class="ri-arrow-right-s-line"></i></a>
</div>
@endsection--}}
<div class="px-1 mb-3">
    <h5 class="text-md font-semibold">Dashboard </h5>
</div>


<section class="toggle-status-section grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-2 mb-4">
    <!-- All Records -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="" onclick="window.location.href='{{ url('/course-category/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-node-tree text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Parent Category</h3>
        </div>
        <div class="bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="total">{{ $totalCategories }}</span>
        </div>
    </div>

    <!-- Created By Me -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="created_by_me" onclick="window.location.href='{{ url('/Course-Course/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-book-open-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Total Courses</h3>
        </div>
        <div class="bg-green-100 text-indigo-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="TotaCourses">{{$totalCourse}}</span>
        </div>
    </div>
    <!-- Draft -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="Draft" onclick="window.location.href='{{ url('/dynamic-course-pages/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-links-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">CourseLinks</h3>
        </div>
        <div class="bg-blue-100 text-gray-700 text-sm font-bold px-4 py-2 rounded-md">
            <span id="draft_count">{{$totalCourseLinks}}</span>
        </div>
    </div>
    <!-- In Review -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="In review" onclick="window.location.href='{{ url('/course-country/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-map-pin-2-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Total Country</h3>
        </div>
        <div class="bg-red-100 text-cyan-800 text-sm font-bold px-4 py-2 rounded-md">
            <span id="country_count">{{$totalCountry}}</span>
        </div>
    </div>
    <!-- Approved -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="state" onclick="window.location.href='{{ url('/course-state/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-government-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Total State</h3>
        </div>
        <div class="bg-purple-100 text-gray-700 text-sm font-bold px-4 py-2 rounded-md">
            <span id="state_count">{{$totalState}}</span>
        </div>
    </div>
    <!-- Rejected -->
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="Rejected" onclick="window.location.href='{{ url('/course-city/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-community-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Total City</h3>
        </div>
        <div class="bg-green-100 text-gray-700 text-sm font-bold px-4 py-2 rounded-md">
            <span id="rejected_count">{{$totalCity}}</span>
        </div>
    </div>
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="Rejected" onclick="window.location.href='{{ url('/faqs-list/list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-information-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Total FAQs</h3>
        </div>
        <div class="bg-blue-100 text-gray-700 text-sm font-bold px-4 py-2 rounded-md">
            <span id="rejected_count">{{$totalFaqs}}</span>
        </div>
    </div>
    <div class="flex items-center justify-between bg-white border rounded-md p-4 hover:shadow transition status-filter cursor-pointer"
        data-status="Rejected" onclick="window.location.href='{{ url('/enrolled/enrollment-list') }}'">
        <div class="flex items-center gap-2">
            <i class="ri-user-follow-line text-indigo-500 text-2xl"></i>
            <h3 class="text-sm font-semibold text-gray-700">Enrolled Candidates</h3>
        </div>
        <div class="bg-red-100 text-gray-700 text-sm font-bold px-4 py-2 rounded-md">
            <span id="rejected_count">{{$totalCandidate}}</span>
        </div>
    </div>

</section>

@endsection
@section('scripts')
@endsection
