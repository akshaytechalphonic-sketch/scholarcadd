@extends('layouts.app')
@section('content')
{{-- ------header------ --}}
<x-common-header 
    title="Ban IP Address Settings" 
    subtitle="Configure Ban IP Address Application Settings"
    :buttons="[
        ['label' => 'Create', 'url' => '/settings/master-setup', 'icon' => 'ri-add-line'],
    ]"
/>




@endsection