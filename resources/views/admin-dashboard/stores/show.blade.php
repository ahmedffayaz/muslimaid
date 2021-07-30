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
@media (min-width: 767px){
.stores .select2{
    width: 300px!important;
}}
ul.categories { 
  list-style: none;
  margin: 5px 5x;
}
ul.categories li {
  margin: 10px 0;
}
.centered{
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
}

/* 

@media (max-width: 350px){
    #tracking_url{
        width: 75%
    }
}

@media (min-width:350px) and (max-width: 767px){
    #tracking_url{
        width: 90%
    }
}
@media (min-width: 992px){
    #tracking_url{
        width: 85%
    }
} */
</style>

@section('content')
@livewire('store-details')

@endsection
