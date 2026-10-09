@extends('layouts.app')
@section('content')
{{-- ------header------ --}}
<x-common-header 
    title="Storage Settings" 
    subtitle="Configure Storage Application Settings"
    :buttons="[
        ['label' => 'Create', 'url' => '/settings/master-setup', 'icon' => 'ri-add-line'],
    ]"
/>




@endsection