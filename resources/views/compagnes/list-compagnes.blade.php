@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
 

<div class="dashboard_content bg-light-4 container-fluid"  >
   
    
 
      
            
<form class="card-title fw-semibold mb-4" action="{{route('ajouter-compagne')}}">Liste des compagnes 
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
    <div class="card w-100">
      <div class="card-body p-4">
        
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle" id="order-listing" >
        
      
            <thead class="text-dark fs-4">
              <tr>
              <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">#</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Centre </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Etablissement </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Wilaya  </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Commune </h6>
                </th>
              
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Date Début</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Date Fin</h6>
                </th>
              
               
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Actions</h6>
                </th>
              
              </tr>
            </thead>
            <tbody>
              @foreach ($compagnes as $compagne) 
                <tr>
                    <td class="border-bottom-0">
                       <h6 class="fw-normal mb-0">{{ $compagne->id  }}</h6>
                    </td>
                    <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0 ">  
                        @if($compagne->idCentre)
                                {{ $compagne->centre->nom}} 
                            @else
                              Aucune centre associé
                            @endif  </h6>
                          
                      </td>
                    <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0 ">  {{ $compagne->etablissement }}  </h6>
                          
                      </td>
                    <td class="border-bottom-0">
                      <h6 class="fw-normal">
                      @if($compagne->communeRelation)
                                {{ $compagne->communeRelation->wilaya_name}} 
                            @else
                              Aucune wilaya associée 
                            @endif 

                        </h6> 
                      </td>
                      <td class="border-bottom-0">
                        <h6 class="fw-normal mb-0 "> 
                        @if($compagne->communeRelation)
                                {{ $compagne->communeRelation->commune_name}} 
                            @else
                              Aucune commune associée 
                            @endif   </h6>
                          
                      </td>
                    
                  </td>
                     
                      <td class="border-bottom-0">
                          <h6 class="fw-normal">{!! $compagne->dateDebut !!}</h6>
                      
                                                  
                      </td>
                      <td class="border-bottom-0">
                          <h6 class="fw-normal">{!! $compagne->dateFin !!}</h6>                 
                      </td> 
                      <td class="border-bottom-0"> 
                          <a href="{{ url('compagnes/modifier/'.$compagne->id) }}"  class="btn btn-sm">
                            <i  style="font-size:20px;color:blue;" class="ti ti-pencil"></i></a>
                    
                            <form id="delete-form-{{ $compagne->id }}" class="d-inline"
                                action="{{ url('compagnes/supprimer/' . $compagne->id) }}" method="POST">
                              {{ csrf_field() }}
                              {{ method_field('DELETE') }}

                              <button type="button" class="btn btn-sm bg-transparent p-0 delete-btn" data-id="{{ $compagne->id }}">
                                  <i class="ti ti-trash" style="color:red; font-size:20px;"></i>
                              </button>
                          </form>
                      </td>
              </tr> 
            @endforeach                   
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
           
      </div>
   
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let compagneId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + compagneId);

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