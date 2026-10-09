@extends('layouts.app')
@section('content')
{{-- ------header------ --}}
<x-common-header 
    title="Appearance Settings" 
    subtitle="Configure Appearance application settings"
    :buttons="[
        ['label' => 'Create', 'url' => '/settings/master-setup', 'icon' => 'ri-add-line'],
    ]"
/>



@endsection