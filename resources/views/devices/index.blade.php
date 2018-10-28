@extends('layouts.app')

@section('body_content')
@include('inc.navbar')
<div id="app">
    <keep-alive>
        <list-devices v-if="currentPage=='ListDevices'" @callback="showDevice"></list-devices>
        <device-details v-else-if="currentPage=='DeviceDetails'" :device-id="selectedDevice" @callback="showPage('ListDevices')"></device-details>
    </keep-alive>
</div>
@endsection

@section('js_content')
@endsection

@section('header_content')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', '') }}"></script>
@endsection