@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
 

<div class="dashboard_content bg-light-4 container-fluid">
<form class="card-title fw-semibold mb-4" action="{{route('ajouter-centre')}}">Liste des centres 
  <button type="submit" class="mx-2 btn btn-sm btn-outline-primary" >
      <span>    <i class="ti ti-plus"></i> </span>  Ajouter  </button></form>

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
      @foreach ($centres as $centre) 
         <div class="col-md-4 mb-4"> {{-- 3 cartes par ligne (col-md-4 = 12/4) --}}
                <div class="card shadow-sm">
                    @if($centre->imageUrl)
                        <img src="{{ asset('storage/'.$centre->imageUrl) }}" class="card-img-top" alt="Image du centre">
                    @else
                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="card-img-top" alt="Image par défaut">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $centre->nom }}</h5>
                        <p class="card-text">
                            📍 {{ $centre->address }}<br>
                            📞 {{ $centre->numeroTlp1 }} 
                            @if($centre->numeroTlp2) / {{ $centre->numeroTlp2 }} @endif
                        </p>
                        <a  href="{{ url('centres/modifier/'.$centre->id) }}" class="btn btn-primary">Voir détails</a>
                        <form id="delete-form-{{ $centre->id }}" class="d-inline"
                              action="{{ url('centres/supprimer/' . $centre->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="button" class="btn btn-sm bg-transparent p-0 delete-btn" data-id="{{ $centre->id }}">
                                <i class="ti ti-trash" style="color:red; font-size:20px;"></i>
                            </button>
                                  </form>
                    </div>
                </div>
            </div>
            @endforeach
            <!--
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle" id="order-listing" >
        
      
            <thead class="text-dark fs-4">
              <tr>
              <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">#</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Nom </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Wilaya </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Commune </h6>
                </th>
              
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Téléphone 01</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Téléphone 02</h6>
                </th>
              
               
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Actions</h6>
                </th>
              
              </tr>
            </thead>
            <tbody>
              @foreach ($centres as $centre) 
                <tr>
                    <td class="border-bottom-0">
                       <h6 class="fw-normal mb-0">{{ $centre->id  }}</h6>
                    </td>
                    <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0 ">  {{ $centre->nom }}  </h6>
                          
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0 ">  
                          
                      
                        @if($centre->communeRelation)
                                {{ $centre->communeRelation->wilaya_name}} 
                            @else
                              Aucune wilaya associée 
                            @endif  
                          </h6>
                          
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0 ">  
                           @if($centre->communeRelation)
                                {{ $centre->communeRelation->commune_name}} 
                            @else
                              Aucune commune associée 
                            @endif  </h6>
                          
                      </td>
                     
                  </td>
                     
                      <td class="border-bottom-0">
                          <h6 class="fw-normal">{!! $centre->numeroTlp1 !!}</h6>
                      
                                                  
                      </td>
                      <td class="border-bottom-0">
                          <h6 class="fw-normal">{!! $centre->numeroTlp2 !!}</h6>
                      
                                                  
                      </td> 
                      <td class="border-bottom-0"> 
                          <a href="{{ url('centres/modifier/'.$centre->id) }}"  class="btn btn-sm">
                            <i  style="font-size:20px;color:blue;" class="ti ti-pencil"></i></a>
                    
                            <form id="delete-form-{{ $centre->id }}" class="d-inline"
                              action="{{ url('centres/supprimer/' . $centre->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="button" class="btn btn-sm bg-transparent p-0 delete-btn" data-id="{{ $centre->id }}">
                                <i class="ti ti-trash" style="color:red; font-size:20px;"></i>
                            </button>
                                  </form>
                                </td>
                        </tr> 
                      @endforeach                   
                      </tbody>
                    </table>
                  </div>-->
                </div>
              </div>
            </div>
                    
      </div> 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let centreId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + centreId);

                Swal.fire({
                    title: "Êtes-vous sûr ?",
                    text: "Cette action est irréversible !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Oui, supprimer !",
                    cancelButtonText: "Annuler"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@endsection