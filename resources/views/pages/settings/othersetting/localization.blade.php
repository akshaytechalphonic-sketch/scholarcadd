@extends('layouts.app')
@section('content')
{{-- ------header------ --}}
<x-common-header 
    title="Localization Settings" 
    subtitle="Configure Localization Application Settings"
    :buttons="[
        ['label' => 'Create', 'url' => '/settings/master-setup', 'icon' => 'ri-add-line'],
        // ['label' => 'Back', 'url' => '/settings/master-setup', 'icon' => 'ri-arrow-left-long-line']
    ]"
/>




@endsection