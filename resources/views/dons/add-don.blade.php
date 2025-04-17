@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
<div class="container-fluid">
  <p class="card-title fw-semibold mb-4" >
          Ajouter un don pour la fiche  
          <a href="{{ url('membres/fiche/'.$idPersonne) }}"> #{{ $idPersonne}}</a>    
  </p>
  <div class="prelevement" id="prelevement">           
      <form class="row g-3" style="padding-top: 10px"  method="POST"  action="{{ route('dons.store') }}">
      @csrf

          <div class="col">
            <label for="inputPassword4" class="form-label">Date</label>
            <input type="datetime-local" class="form-control" name="date">
            @if ($errors->has('dateDon'))
            <small class="text text-danger">
                {{ $errors->first('dateDon') }}
            </small>
          @endif
         </div>
     
       <!--   <div class="col">
            <label for="lieuDon" class="form-label">Lieu don</label>
            <input type="text" class="form-control" id="lieuDon" name="lieuDon">
            @if ($errors->has('lieuDon'))
            <small class="text text-danger">
                {{ $errors->first('lieuDon') }}
            </small>
          @endif
          </div>
-->
          <div class="col-md-6 col-lg-3">
              <label for="inputState" class="form-label">Poids</label>
              <input type="text" class="form-control" name="Pds" placeholder="60 Kg">
              @if ($errors->has('Pds'))
            <small class="text text-danger">
                {{ $errors->first('Pds') }}
            </small>
          @endif
            </div>
             
              
                <input type="hidden" class="form-control" name="idDonneur"  value="{{ $idPersonne }}" >
            
          
            <div class="col-md-6 col-lg-3">
                <label class="form-label">N° flacon</label>
               
                <input type="text" class="form-control" name="numeroFlacon" placeholder="N° flacon" >
              @if ($errors->has('numeroFlacon'))
            <small class="text text-danger">
                {{ $errors->first('numeroFlacon') }}
            </small>
          @endif
            </div>
          <div class="col">
            <label for="inputAddress2" class="form-label">C C</label>
            <input type="text" class="form-control" name="C" >
            @if ($errors->has('C'))
            <small class="text text-danger">
                {{ $errors->first('C') }}
            </small>
          @endif
          </div>
          
          <div class="col-md-3">
            <label for="inputCity" class="form-label">T A</label>
            <input type="text" class="form-control" name="TA">
            @if ($errors->has('TA'))
            <small class="text text-danger">
                {{ $errors->first('TA') }}
            </small>
          @endif
          </div>
          <div class="col-md-4 col-lg-3">
            <label for="inputState" class="form-label">Sérologie</label>
            <select id="inputState" class="form-select" name="serologie">
              <option value="0" selected>Négative</option>
              <option value="1">Positive</option>
            </select>
            @if ($errors->has('serologie'))
            <small class="text text-danger">
                {{ $errors->first('serologie') }}
            </small>
          @endif
          </div>
          <div class="col-md-4 col-lg-3">
            <label for="inputState" class="form-label">Support</label>
            <select name="support" class="form-select">
              <option value="double" selected>Double</option>
              <option value="triple">Triple</option>
              <option value="quadruple">Quadruple</option>
            </select>
            @if ($errors->has('support'))
            <small class="text text-danger">
                {{ $errors->first('support') }}
            </small>
          @endif
          </div>
        <br>
          <div class="row">
 


         <div class="col mb-4">
         
            <label class="block text-gray-700 font-bold mb-2">Source du Don :</label>

            <div class="flex items-center mb-2">
                <input type="radio" id="source_app" name="sourceDon" value="App" class="mr-2">
                <label for="source_app" class="cursor-pointer">App</label>
            </div>

            <div class="flex items-center mb-2">
                <input type="radio" id="source_ami" name="sourceDon" value="Ami" class="mr-2">
                <label for="source_ami" class="cursor-pointer">Ami</label>
            </div>

            <div class="flex items-center">
                <input type="radio" id="source_volontaire" name="sourceDon" value="Volontaire" class="mr-2">
                <label for="source_volontaire" class="cursor-pointer">Volontaire</label>
            </div>
 

         </div>
          </div>
          <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Réactions</label>
              <textarea class="form-control" name="reactions" placeholder="Perte de connaissance, Malaise, Syncope, Convulsions ou autres .... ?" rows="3">
              </textarea>
              @if ($errors->has('reactions'))
            <small class="text text-danger">
                {{ $errors->first('reactions') }}
            </small>
          @endif
            </div>
            
            <div class="mb-3">
              <label for="exampleFormControlTextarea1" class="form-label">Observations</label>
              <textarea class="form-control" name="observations" rows="3"></textarea>
              @if ($errors->has('observations'))
            <small class="text text-danger">
                {{ $errors->first('observations') }}
            </small>
          @endif
            </div>
            <div class="col-md-12">
            <label for="persMedicale" class="form-label">Autoriser *</label>
            <select id="persMedicale" name="persMedicale" class="form-select ">
            <option value="">Sélectionner  </option>
              @foreach ($listePersonneMedicale as $persMedicale)
              <option value='{!! $persMedicale->id !!}'>
                  {!! $persMedicale->personnee()->value("nom") !!}
                  {!! $persMedicale->personnee()->value("prenom") !!} ({!! $persMedicale->fonction !!})
              </option>
              @endforeach

          </select>
          @if ($errors->has('persMedicale'))
            <small class="text text-danger">
                {{ $errors->first('persMedicale') }}
            </small>
            @endif
          </div>
          <div class="col-md-12">
    <label for="persMedicaleSuperviser" class="form-label">Superviser *</label>
    <select id="persMedicaleSuperviser" name="persMedicaleSuperviser" class="form-select">
        <option value="">Sélectionner</option>
        @foreach ($listePersonneMedicale as $persMedicale)
            <option value="{{ $persMedicale->id }}" 
                {{ old('persMedicaleSuperviser', $personne->persMedicaleSuperviser ?? '') == $persMedicale->id ? 'selected' : '' }}>
                {{ $persMedicale->personnee()->value("nom") }}
                {{ $persMedicale->personnee()->value("prenom") }} 
                ({{ $persMedicale->fonction }})
            </option>
        @endforeach
    </select>
    
    @if ($errors->has('persMedicaleSuperviser'))
        <small class="text text-danger">
            {{ $errors->first('persMedicaleSuperviser') }}
        </small>
    @endif
</div>

          
              <div class="col-auto enregsitrerbtn">
                  <button type="submit" class="btn btn-primary mb-3 py-2 px-4">
                      Ajouter ce don</button>
              </div>
          
        </form>
  </div>
</div> 
@endsection
 