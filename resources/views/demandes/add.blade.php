@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
<div class="container-fluid">   
   <form class="card-title fw-semibold mb-4" action="{{route('demandes.liste')}}">
        Ajouter une nouvelle demande
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary">Liste</button>
   </form>

    <form action="{{ route('demandes.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="dateDemande" class="form-label">Date de la demande</label>
            <input type="date" class="form-control" id="dateDemande" name="dateDemande" required>
        </div>

    

        <div class="mb-3">
            <label for="lieuDemande" class="form-label">Lieu de la demande</label>
            <input type="text" class="form-control" id="lieuDemande" name="lieuDemande" required>
        </div>

        <div class="mb-3">
            <label for="serviceMedical" class="form-label">Service médical</label>
            <input type="text" class="form-control" id="serviceMedical" name="serviceMedical" required>
        </div>

        <div class="mb-3"> 
      
           <div class="form-group {{ $errors->has('groupage') ? 'has-error' : '' }} ">
            <label for="groupage" class="form-label text-center">Groupage sanguin</label>
            <select id='groupage' name='groupageDemande' class="form-select" required>
                <option value=''>Choisir le groupage</option>
                @foreach ($listeGroupage as $key => $value)
                <option value='{!! $value->id !!}'>{!! $value->type !!}</option>
                @endforeach

            </select> 
            @if ($errors->has('groupage'))
            <small class="text text-danger">
                {{ $errors->first('groupage') }}
            </small>
            @endif
        </div>
        </div>

        <div class="mb-3">
            <label for="quantiteDemande" class="form-label">Quantité demandée</label>
            <input type="number" class="form-control" id="quantiteDemande" name="quantiteDemande" required>
        </div>

        

        <div class="mb-3">
            <label for="typeMaladie" class="form-label">Type de maladie</label>
            <input type="text" class="form-control" id="typeMaladie" name="typeMaladie" required>
        </div>

        <div class="mb-3">
            <label for="idDemandeur" class="form-label">Demandeur</label>
        
            <select id="idDemandeur" name="idDemandeur" class="form-select ">
                        <option value="">Sélectionner  </option>
                          @foreach ($users as $user)
                          <option value='{!! $user->id !!}'>
                             {!! $user->personne()->value("nom") !!}
                             {!! $user->personne()->value("prenom") !!} 
                          </option>
                          @endforeach
           
                      </select>
        </div>

        <div class="mb-3">
            <label for="numeroDossierMedical" class="form-label">Numéro de dossier médical</label>
            <input type="text" class="form-control" id="numeroDossierMedical" name="numeroDossierMedical">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div> 
@endsection
 










