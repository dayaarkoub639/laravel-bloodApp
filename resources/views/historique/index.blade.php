@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
 

<div class="dashboard_content bg-light-4 container-fluid">
   
     

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

            <!--ICi recherche-->
    <div class="d-flex align-items-stretch">



    <div class="card w-100 container">
      <div class="card-body p-4 row">
   <h1>  Historique
   </h1>
   <p>En cours de construction ....</p>
   
                </div>
              </div>
            </div>
                    
      </div> 
 

@endsection