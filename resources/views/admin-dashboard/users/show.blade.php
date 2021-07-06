@extends('layouts.admin-dashboard.app')
<style>
    .nk-tb-list{
        table-layout: fixed;
    }
    .nk-files-view-grid .nk-file-icon-type {
    width: 150px;
    padding: 2rem 0 .5rem 0;
}
@media (min-width: 1200px){
    .nk-files-view-grid .nk-file {
    width: calc(25% - 16px)!important;
}
}
.nk-files-view-grid .nk-file {
    background-color:#f5f6fa7a!important;
}
.stores .select2{
    width: 300px!important;
}
.select2-selection__choice {
    text-transform: capitalize;
}
</style>

@section('content')
@livewire('user-details')

@endsection