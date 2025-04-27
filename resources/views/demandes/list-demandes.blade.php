@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
<style>
        /* Conteneur spécifique */
        #addDonBtn {
            position: relative;
            top: 0px;
            padding-top:0px;
            margin-top:0px;
        }

        /* Styles pour les icônes */
        #addDonBtn .goutte {
            position: absolute;
            top: 0px;
            left: 0px; 
            font-size:24px;

        }

        #addDonBtn .addGout {
            position: absolute;
            top:  9px;
            left:7px;
            font-size:10px;
           
           
          
         
        }

        /* Styles supplémentaires pour les classes */
        #addDonBtn .goutte {
            /* Ajoutez des styles spécifiques pour la classe "goutte" ici */
        }

        #addDonBtn .addGout {
            /* Ajoutez des styles spécifiques pour la classe "addGout" ici */
        }
    </style>

<div class="dashboard_content bg-light-4 container-fluid" style="height: 100%"> 
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-lg-5 me-3  card py-3 static2 d-flex align-items-center justify-content-center ">
                    
                      <div class="row" >
                        <div class="col-3 text-end">
                        <img width="24" height="14" src="https://img.icons8.com/material-rounded/3AC47D/24/long-arrow-left.png" alt="long-arrow-left" class="iconstatic"/>
                    
                           <img  height="60" src="https://img.icons8.com/pastel-glyph/3AC47D/64/--bloodbag.png"
                            alt="--bloodbag" >
                        </div>
                        <div class="col-7">
                            <p class="card-title fw-bold">310</p>
                            <p  class="fs-3">Demandes envoyées</p>
                     
                        </div> 
                    </div>
                </div>
                <div class="col-sm-12 col-lg-5  card py-3 static2 d-flex align-items-center justify-content-center">
                 <div class="row">    
                    <div class="col-4 text-end">
                        <img  height="45" src="https://img.icons8.com/ios-filled/de2e23/50/leave.png" alt="leave"/>
                      </div>
                      <div class="col-7">
                          <p class="card-title fw-bold">31</p>
                          <p class="fs-3">demandes nécessitant un don</p>
                      </div>
                   </div>
                </div>
            </div>
        
            <div class="row mt-3 col-sm-12">
                <div class="col-sm-12 col-lg-5  me-3  card py-3 static2 d-flex align-items-center justify-content-center">
                   <div class="row">
                    <div class="col-4 text-end">
                          <img  height="45" src="https://img.icons8.com/ios-filled/F8C039/50/batch-assign.png" alt="batch-assign"/>
                      </div>
                      <div class="col-7 "> 
                          <p class="card-title fw-bold">540</p>
                          <p class="fs-3">Donneurs actifs</p>
                      </div>
                   </div>
                </div>
        
                <div class="col-sm-12 col-lg-5  card py-3 static2 d-flex align-items-center justify-content-center">
                <div class="row">    
                   <div class="col-4 text-end">
                        <img width="20" height="14" src="https://img.icons8.com/material-rounded/3D9BFF/24/long-arrow-right.png" alt="long-arrow-right"/>
                        <img  height="70" src="https://img.icons8.com/pastel-glyph/3D9BFF/64/--bloodbag.png" alt="--bloodbag" class="iconstatic">
                    </div>
                    <div class="col-7"> 
                        <p class="card-title fw-bold">31</p>
                        <p class="fs-3">Demandes nécessitant un don</p>
                    </div>
                  </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-sm-12" >
            <img src="{{ asset('image/Blood-donation-pana.png') }}" alt="" style="height: 270px;" class="imgdemande">
        </div>
    </div> 
    
<div class="container-fluid">
      
            
<form class="card-title fw-semibold mb-4" action="{{route('ajouter-demande')}}">Liste des demandes 
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
                  <h6 class="fw-semibold mb-0 small">Date  </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Demandeur </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Service medical</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Accepter par</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small" >Groupage demandé</h6>
                </th>
               
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Actions</h6>
                </th>
              
              </tr>
            </thead>
            <tbody>
              @foreach ($demandes as $demande) 
                <tr>
                    <td class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">{{ $demande->id  }}</h6>
                      <p class="small"> {{ $demande->typeDemande}}</p>
                    </td>
                    <td class="border-bottom-0">
                      <h6 class="fw-normal">
                       {!! $demande->dateDemande !!}   

                      </h6>
                  
                    
                    
                      <td class="border-bottom-0">
                    
                      <h6 class="fw-semibold mb-0 ">  {{ $demande->demandeur->personne->nom }}  {{ $demande->demandeur->personne->prenom }}</h6>
                          <p class="small"> {{ $demande->demandeur->personne->numeroTlp1 }}</p>

                      </td>
                  </td>
                      <td class="border-bottom-0">
                          <h6 class="fw-normal">{!! $demande->serviceMedical !!}</h6>
                      
                                                  
                      </td>
                      <td class="border-bottom-0">  
                     

                      <span class="  fw-semibold">
                      ({{count($demande->personnes)}})  Accepté
                      </span>
                      </td>
                      <td class="border-bottom-0">
                        <div class="d-flex align-items-center">
                          <span class="badge bg-primary rounded-3 fw-semibold"> 
                          @if($demande->groupage)
                                {{ $demande->groupage->type}} 
                            @else
                              N/A
                            @endif  
                           </span>
                            
                        </div>
                      </td> 
                      <td class="border-bottom-0"> 
                      <a href="{{ url('demandes/acceptees', $demande->id) }}"  class="btn btn-sm">
                      <i  style="font-size:20px;color:blue;" class="ti ti-eye"></i></a>
                          <a href="{{ route('demandes.modifier', $demande->id) }}"  class="btn btn-sm">
                            <i  style="font-size:20px;color:blue;" class="ti ti-pencil"></i></a>
                    
                     
                          <form id="delete-form-{{ $demande->id }}" class="d-inline"
                          action="{{ route('demande.delete' , $demande->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        <button type="button" class="btn btn-sm bg-transparent p-0 delete-btn" data-id="{{ $demande->id }}">
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
                let demandeId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + demandeId);

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