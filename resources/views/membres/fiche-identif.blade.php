@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<style>
  .fiche{
    border: solid 2px var(--red-color);
    border-radius: 10px;
    z-index: 200000;
    width: 100%;
    overflow-y:auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    border-radius: 8px;
    margin-bottom:40px;
  }
  
  .labelfiche{
    font-weight: bold;
  
  }
  .form3{
    border: solid 3px gray;
    border-radius: 0%;
    padding-top: 0;
    background-color: #fff;
    
  }
  .groupage{
    width: 180px;
    margin-top:200px;
   
    
  }
  .observation{
    border-bottom: 1px solid black;
    border-width: 100%;
  }
  .titre{
    border: 2px solid gray;
    
  }
  .info{
    margin-left:8px;
  }
  .form2{
    padding-top: 40px; 
    margin-top:80px;
  }

</style>

<div class="container-fluid">    

  <form class="card-title fw-semibold mb-4"  action="{{route('membres')}}">
     
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary">Liste </button>
        <a href="{{ url('membres/modifier/'.$personne->idUser) }}"  class="btn btn-sm btn-primary"><i class="ti ti-pencil"></i> Modifier</a>
   </form>
      
 
     
  
      <div class="row fiche">
           <div class="col-8">
      
           <p class="fs-2 mb-0">
              MINISTERE DE LA SANTE DE LA POPULATION
            </p>
            <p class="fs-2 mb-0">
             CENTRE HOSPITALO UNIVERSITAIRE BLIDA
            </p>
            <p class="fs-2">
              CENTRE DE WILAYA DE TRANSFUSION SANGUINE
            </p>

          <div class="position-absolute start-50 translate-middle-x">
            <h5 class="titre text-center p-2"> FICHE D'IDENTIFICATION DU DONNEUR DE SANG</h5>
          </div>

          <form class="row g-3 form2">         
            <div class="mb-3 row">

            <label class="col-sm-2 col-form-label labelfiche">Nom:</label>
             <div class="col-sm-10 col-lg-2">
              <input type="text" readonly class="form-control-plaintext" value="{{ $personne->nom }}">  
             </div>

             <label class="col-sm-2 col-form-label labelfiche">Prénom:</label>
            <div class="col-sm-10 col-lg-2">
            <input type="text" readonly class="form-control-plaintext" value="{{ $personne->prenom }}">
            </div>
            
            <label class="col-sm-2 col-form-label labelfiche">N° de fiche:</label>
            <div class="col-sm-10 col-lg-2">
            <input type="text" readonly class="form-control-plaintext" value="{{ $personne->idUser }}">
            </div>

            </div>

            <div class=" row">
              @if($personne->gender==0)
             <label class="col-sm-2 col-form-label labelfiche">Epouse de:</label>
              <div class="col-sm-10 col-lg-2">
              <input type="text" readonly class="form-control-plaintext" value='{{  $personne->epouseDe }}'>
              </div>
              @endif

              <label class="col-sm-2 col-form-label labelfiche info">Née le:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" value="{{ $personne->dateDeNess }}">
              </div>

              <label class="col-sm-2 col-form-label labelfiche info">à</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" value="{{ $personne->lieuNaissance }}">
              </div>
            
            </div>

            <div class="mb-3 row">


            <label class="col-sm-2 col-form-label labelfiche info">Domicile:</label>
            
              <div class="col-sm-10 col-lg-9">
              {{ $personne->adresse }} 
           
                  @if($personne->communeDomicile)
                          {{ $personne->communeDomicile->commune_name }} 
                      @else
                        Aucune commune associée 
                      @endif  

                  @if($personne->communeDomicile)
                          {{ $personne->communeDomicile->daira_name }} , {{ $personne->communeDomicile->wilaya_code }} 
                      @else
                        Aucune daira associée 
                      @endif  
           

              </div>
           
            <label class="col-sm-2 col-lg-4 col-form-label labelfiche info">Tél principale:</label>
              <div class="col-sm-10 col-lg-4">
                <input type="text" readonly class="form-control-plaintext" value="{{ $personne->numeroTlp1 }}">
              </div>
              
            </div>

            <div class="mb-3 row">
          
            <label class="col-sm-2 col-lg-4 col-form-label labelfiche info">Adresse professionnelle:</label>
             
             {{ $personne->adressePro }}
           
                 @if($personne->communePro)
                         {{ $personne->communePro->commune_name }} 
                     @else
                       Aucune commune associée 
                     @endif  

                 @if($personne->communePro)
                         {{ $personne->communePro->daira_name }}  , {{ $personne->communePro->wilaya_code }} 
                     @else
                       Aucune daira associée 
                     @endif 

            <div class="mb-3 row">
              <label  class="col-sm-2 col-form-label labelfiche info">Tél:</label>
              <div class="col-sm-10 col-lg-4">
                <input type="text" readonly class="form-control-plaintext" value="{{ $personne->numeroTlp2 }}">
              </div>
            </div>
              
             
            </div>
            <div class="mb-3 row">            
  
             
            </div>
            
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label observation">Observations médicales</label>
                <p class="form-control-plaintext" >
                  {{ $personne->observations }}
                </p>
              </div>
          </form>
           </div>

           <div class="col-4">
    <div class="row d-flex flex-column groupage ms-5">
              <div class="col-sm-10 form3">
              <label for="inputState" class="form-label py-2">groupe sanguin:</label>
               <p>
                @if($personne->groupage)
                                Rh:  <strong> {{ $personne->groupage->type}}   </strong>
                            @else
                              N/A
                            @endif  
               </p>
              </div>
              <div class="col-sm-10 form3">
              <label for="inputState" class="form-label py-2">Phénotype:</label>
              <input type="text" class="form-control-plaintext py-0" value='C {{ ($personne->cMaj==1) ? "+":"-" }}'>
              <input type="text" class="form-control-plaintext py-0" value='E {{ ($personne->EMaj==1) ? "+":"-" }}'>
              <input type="text" class="form-control-plaintext py-0" value='c {{ ($personne->cMin==1) ? "+":"-" }}'>
              <input type="text" class="form-control-plaintext py-0" value='e {{ ($personne->eMin==1) ? "+":"-" }}'>
              <input type="text" class="form-control-plaintext py-0" value='Kell {{($personne->kell==1) ? "+":"-" }}'>
              </div>
            </div>
    </div>

      </div>
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

      <div class="prelevement">
        <main>
          <div class="container-fluid">
              <div class="card mb-4">
                  <div class="card-header"  >
                      <p class="text-center">                         
                          PRELEVEMENT
                      </p>                      
                  </div>
                  <div class="card-body table-responsive">
               
                  <table class="table text-nowrap mb-0 align-middle" id="order-listing" >
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Date & Heure</th>
                              <th scope="col">Lieu de don</th>
                              <th scope="col">Pds</th>
                              <th scope="col">N° flacon</th>
                              <th scope="col">C C</th>
                              <th scope="col">TA</th>
                              <th scope="col">Sérologie</th>
                              <th scope="col">Observation</th>
                              <th scope="col">Options</th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach ($dons as $don) 
                            <tr>
                              <th scope="row">{!! $don->idDon !!}</th>
                              <td>{!! $don->date !!}</td>
                              <td>{!! $don->lieuDon !!}</td>
                              <td>{!! $don->Pds !!}Kg</td>
                              <td>{!! $don->numeroFlacon !!}</td>
                              <td>{!! $don->C !!}</td>
                              <td>{!! $don->TA !!}</td>
                              <td> {{ ($don->serologie==1) ? "+":"-" }} </td>
                              <td>{!! $don->obsMedicale !!}</td>
                             
                              <td class="opt">
                              
                                <button  class="btn btn-sm btn-outline-primary">
                                     <a href="{{ url('dons/modifier/'.$don->idDon) }}" ><i class="ti ti-edit text-primary fs-4" 
                                      ></i></a>
                                </button>
                             
                                <form id="delete-form-{{ $don->idDon }}" class="d-inline"
      action="{{ url('dons/supprimer/' . $don->idDon) }}" method="POST">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}

      <button type="button" class="btn btn-sm btn-primary delete-btn" data-id="{{ $don->idDon }}">
          <i class="ti ti-trash" style="color:white; font-size:16px;"></i>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let donId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + donId);

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