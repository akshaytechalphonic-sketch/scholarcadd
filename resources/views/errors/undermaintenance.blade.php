@extends('layouts.master')
@section('Page_content')
    <div class="flex justify-center flex-col items-center h-screen ">
        <h1 class="text-[100px] md:text-[180px]  font-extrabold text-primary ">
            Under Maintenance
        </h1>
        <div class="flex justify-center items-center mt-5">
            <a href={{ url('/') }}
                class="bg-primary text-white px-6 py-3 rounded-full text-sm font-semibold flex items-center transition duration-300 transform hover:bg-blue-500 hover:scale-105 ">
                <i class="ri-arrow-left-line mr-2"></i>Back To Home
            </a>
        </div>
    </div>
@endsection
