@extends('layouts.app')
@section('content')
{{-- ------header------ --}}
<x-common-header 
    title="Security Settings" 
    subtitle="Configure Security Application Settings"
    :buttons="[
        ['label' => 'Create', 'url' => '/settings/master-setup', 'icon' => 'ri-add-line'],
        // ['label' => 'Back', 'url' => '/settings/master-setup', 'icon' => 'ri-arrow-left-long-line']
    ]"
/>




@endsection