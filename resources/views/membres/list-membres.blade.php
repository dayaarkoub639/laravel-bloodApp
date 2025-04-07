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
<div class="container-fluid">
      
            
<form class="card-title fw-semibold mb-4" action="{{route('ajouter-membre')}}">Liste des membres 
  <button type="submit" class="mx-2 btn btn-sm btn-outline-primary" >
            <span>
                  <i class="ti ti-plus"></i>
                </span>
                Ajouter
             
          
          
          
          </button></form>
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
            <!--ICi recherche TODO-->
            <div class="accordion mb-4" id="filterAccordion">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingFilters">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="true" aria-controls="collapseFilters">
        Recherche avancée
      </button>
    </h2>
    <div id="collapseFilters" class="accordion-collapse collapse show" aria-labelledby="headingFilters" data-bs-parent="#filterAccordion">
      <div class="accordion-body">
        <div class="container">
          <!-- Recherche -->
          <div class="mb-4">
            <label for="search-input" class="form-label">Rechercher un membre</label>
            <div class="input-group">
              <input
                class="form-control"
                type="search"
                id="search-input"
                placeholder="Chercher par nom, prenom ou pseudo ..." />
              <button class="btn btn-primary" id="search">Chercher</button>
            </div>
          </div>

          <!-- Filtres -->
          <div class="row g-3">
            <div class="col-md-3">
              <label for="groupage" class="form-label">Groupage</label>
              <select id='groupage' name='groupage' class="form-select">
                <option value=''>Choisir le groupage</option>
                @foreach ($listeGroupage as $key => $value)
                <option value='{!! $value->id !!}'>{!! $value->type !!}</option>
                @endforeach

            </select> 
            </div>

            <div class="col-md-2">
              <label for="c_select" class="form-label">C</label>
              <select id="c_select" class="form-select">
                <option selected disabled>C</option>
                <option value="1">C+</option>
                <option value="0">C-</option>
              </select>
            </div>

            <div class="col-md-2">
              <label for="e_select" class="form-label">E</label>
              <select id="e_select" class="form-select">
                <option selected disabled>E</option>
                <option value="1"> E+</option>
                <option value="0">E-</option>
              </select>
            </div>

            <div class="col-md-2">
              <label for="c_lower_select" class="form-label">c</label>
              <select id="c_lower_select" name="c_lower_select" class="form-select">
                <option selected disabled>c</option>
                <option value="1">c+</option>
                <option value="0">c-</option>
              </select>
            </div>

            <div class="col-md-2">
              <label for="e_lower_select" class="form-label">e</label>
              <select id="e_lower_select" class="form-select">
                <option selected disabled>e</option>
                <option  value="1">e+</option>
                <option  value="0">e-</option>
              </select>
            </div>

            <div class="col-md-2">
              <label for="kell" class="form-label">Kell</label>
              <select id="kell" class="form-select">
                <option selected disabled>Kell</option>
                <option value="1">Kell+</option>
                <option value="0">Kell-</option>
              </select>
            </div>

           
 
    <div class="form-group col">
        <label for="wilaya" class="form-label">Wilaya</label>
        <select id="wilaya" name="wilaya" class="form-select">
            <option value="">Sélectionner une wilaya</option>
            @foreach ($listeWilayas as $wilaya)
                <option value="{{ $wilaya->id }}">{{ $wilaya->name_ascii }}</option>
            @endforeach
        </select>
    </div>

    <!-- Sélection de la Commune -->
    <div class="form-group col">
        <label for="commune" class="form-label">Commune</label>
        <select id="commune" name="commune" class="form-select">
            <option value="">Sélectionner une commune</option>
        </select>
    </div>
 
  
          </div>
        </div> <!-- /.container -->
      </div> <!-- /.accordion-body -->
    </div>
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
                  <h6 class="fw-semibold mb-0 small">Nom Prénom</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Date de naissance</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Commune</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small" >Groupage</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">N°.tel</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Actions</h6>
                </th>
              
              </tr>
            </thead>
            <tbody>
              
              @foreach ($users as $personne) 
            
                <tr>
                    <td class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">{{ str_pad($personne->idUser, 2, '0', STR_PAD_LEFT) }}</h6>
                      <span class="fw-normal small">{!! $personne->typePersonne !!}</span>    
                    </td>
                    <td class="border-bottom-0">
                      <h6 class="fw-normal mb-0">
                      {!!$personne->user()->value("pseudo");  !!}
                       
                      </h6>
                      <span class="fw-normal small">{!! $personne->nom !!} {!!  $personne->prenom !!}</span>    
                    
                  </td>
                      <td class="border-bottom-0">
                          <h6 class="fw-normal">{!! $personne->dateDeNess !!}</h6>
                                                  
                      </td>
                      <td class="border-bottom-0">
                       
                        <h6 class="fw-normal"> 

                        @if($personne->commune)
                                {{ $personne->commune->wilaya_name }} 
                            @else
                              Aucune wilaya associée 
                            @endif  
      </h6>   
                     
                      <span class="fw-normal small">
                      @if($personne->commune)
                                {{ $personne->commune->commune_name }} 
                            @else
                              Aucune commune associée 
                            @endif  
                      </span>  
                      </td>
                      <td class="border-bottom-0">
                        <div class="d-flex align-items-center gap-2">
                          <span class="badge bg-primary rounded-3 fw-semibold"> 
                          @if($personne->groupage)
                                {{ $personne->groupage->type}} 
                            @else
                              N/A
                            @endif  
                           </span>
                            
                        </div>
                      </td>
                      
                      <td class="border-bottom-0">
                        <h6 class="fw-semibold mb-0 ">{!! $personne->numeroTlp1!!}</h6>
                        <span class="fw-normal small">{!! $personne->numeroTlp2 !!}</span>    
                      </td>
                      <td class="border-bottom-0">

                      
                    <!--TODO SUPPRIMER FOR SUPER ADMIN-->
                   
                <a href="{{ url('membres/modifier/'.$personne->idUser) }}" style="font-size:22px"><i class="ti ti-pencil"></i></a>
                <a href="{{ url('membres/fiche/'.$personne->idUser) }}" style="font-size:22px;color:black"><i class="ti ti-article"></i></a>


              
                <form id="delete-form-{{ $personne->idUser }}" class="d-inline"
      action="{{ url('membres/supprimer/' . $personne->idUser) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}

    <button type="button" class="btn btn-sm bg-transparent p-0 delete-btn" data-id="{{ $personne->idUser }}">
        <i class="ti ti-trash" style="color:red; font-size:20px;"></i>
    </button>
</form>
                <a href="{{ url('dons/ajouter/'.$personne->idUser  ) }}" style="color:#c00000">
                
            
                         
                              <span id="addDonBtn">
                  <i class="ti ti-droplet goutte"></i>
                  <i class="ti ti-plus addGout"></i>
              </span>
                </a>

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
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <!-- AJAX pour récupérer les communes dynamiquement -->
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
                let personneId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + personneId);

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