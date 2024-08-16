@extends('layout')
@section('content')
    <div class="container-fluid border-0 p-5">
        
        @include('modals.remove-selected-client')
        @include('modals.new-client-modal')
    </div>
@endsection
