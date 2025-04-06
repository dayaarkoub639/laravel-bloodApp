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

                        @if($personne->communeDomicile)
                                {{ $personne->communeDomicile->wilaya_name }} 
                            @else
                              Aucune wilaya associée 
                            @endif  
      </h6>   
                     
                      <span class="fw-normal small">
                      @if($personne->communeDomicile)
                                {{ $personne->communeDomicile->commune_name }} 
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
<script>
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