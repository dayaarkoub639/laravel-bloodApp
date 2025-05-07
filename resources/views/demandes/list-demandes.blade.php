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

           <!-- Section de filtres -->
    <div class="mb-4">
        <div class="filter-section">
            <form id="filterForm" action="{{ route('demandes.searchAdvanced') }}" method="POST">
            @csrf
                <div class="row">
                    <!-- Filtre par période -->
                    <div class="col-md-6 col-lg-6 mb-3">
                        <label class="filter-title">Période</label>
                        <div class="filter-buttons">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="periode" id="periode24h" value="24h" {{ request('periode') == '24h' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="periode24h">Pendant 24 h</label>
                                
                                <input type="radio" class="btn-check" name="periode" id="periode7j" value="7j" {{ request('periode') == '7j' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="periode7j">Pendant une semaine</label>
                                
                                <input type="radio" class="btn-check" name="periode" id="periode15j" value="15j" {{ request('periode') == '15j' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="periode15j">Pendant 2 semaines</label>
                                
                                <input type="radio" class="btn-check" name="periode" id="periode1m" value="1m" {{ request('periode') == '1m' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="periode1m">Pendant un mois</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtre par groupage sanguin -->
                    <div class="col-md-6 col-lg-6 mb-3">
                        <label for="groupage" class="filter-title">Groupage sanguin</label>
                        <select id="groupage" name="groupage" class="form-select">
                                    <option value="">Choisir le groupage</option>
                                    @foreach ($listeGroupage as $value)
                                        <option value="{{ $value->id }}" {{ old('groupage', request('groupage')) == $value->id ? 'selected' : '' }}>
                                            {{ $value->type }}
                                        </option>
                                    @endforeach
                                </select>
                    </div>
                    
                    
                            <!-- Wilaya -->
                            <div class="form-group col">
                                <label for="wilaya" class="form-label">Wilaya</label>
                                <select id="wilaya" name="wilaya" class="form-select">
                                    <option value="">Sélectionner une wilaya</option>
                                    @foreach ($listeWilayas as $wilaya)
                                        <option value="{{ $wilaya->id }}" {{ old('wilaya', request('wilaya')) == $wilaya->id ? 'selected' : '' }}>
                                            {{ $wilaya->name_ascii }}  - {{ $wilaya->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Commune -->
                            <div class="form-group col">
                                <label for="commune" class="form-label">Commune</label>
                                <select id="commune" name="commune" class="form-select">
                                    <option value="">Sélectionner une commune</option>
                                    {{-- Remplissage dynamique avec JS/AJAX si besoin --}}
                                </select>
                            </div>
                </div>
                
                <div class="d-flex justify-content-end mt-2">
                   
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="ti ti-search"></i> Filtrer
                    </button>
                </div>
            </form>
            <form class="container" method="POST" action="{{ route('demandes.searchAdvanced') }}">    @csrf
            <button type="submit" class="btn btn-sm btn-secondary me-2" id="resetFilters">
                        <i class="ti ti-refresh"></i> Réinitialiser
                    </button>
        
            </form>
        </div>
    </div>
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
                          <p class="small"> {{ $demande->demandeur->personne->numeroTlp1 }}    </p>
 
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
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 

    $('#wilaya').change(function() {
        var wilaya_id = $(this).val();
       // Convertir le code wilaya en format 01, 02, etc.
       var wilaya_code = wilaya_id.padStart(2, '0');
        $('#commune').html('<option value="">Chargement...</option>');

        if (wilaya_code) {
            $.ajax({
                url: '/api/communes/' + wilaya_code,
                type: 'GET',
                success: function(data) {
                    $('#commune').html('<option value="">Sélectionner une commune</option>');
                    $.each(data, function(index, commune) {
                        $('#commune').append('<option value="' + commune.id + '">' + commune.commune_name + '</option>');
                    });
                }
            });
        } else {
            $('#commune').html('<option value="">Sélectionner une wilaya d\'abord</option>');
        }
    });
 
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