@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
<div class="container-fluid">
  <p class="card-title fw-semibold mb-4">
    Ajouter un don pour la fiche
    <a href="{{ url('membres/fiche/'.$idPersonne) }}"> #{{ $idPersonne}}</a>
  </p>
  <div class="prelevement" id="prelevement">
    <form class="row g-3" style="padding-top: 10px" method="POST" action="{{ route('dons.store') }}">
      @csrf

      <div class="col">
        <label for="date" class="form-label">Date</label>
        <input type="datetime-local" class="form-control" name="date" value="{{ old('date') }}">
        @error('dateDon')
        <small class="text text-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="col-md-6 col-lg-3">
        <label for="Pds" class="form-label">Poids</label>
        <input type="text" class="form-control" name="Pds" placeholder="60 Kg" value="{{ old('Pds') }}">
        @error('Pds')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <input type="hidden" class="form-control" name="idDonneur" value="{{ $idPersonne }}">

      <div class="col-md-6 col-lg-3">
        <label class="form-label">N° flacon</label>
        <input type="text" class="form-control" name="numeroFlacon" placeholder="N° flacon" value="{{ old('numeroFlacon') }}">
        @error('numeroFlacon')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col">
        <label for="C" class="form-label">C C</label>
        <input type="text" class="form-control" name="C" value="{{ old('C') }}">
        @error('C')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-3">
        <label for="TA" class="form-label">T A</label>
        <input type="text" class="form-control" name="TA" value="{{ old('TA') }}">
        @error('TA')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-4 col-lg-3">
        <label class="form-label">Sérologie</label>
        <select class="form-select" name="serologie">
          <option value="0" {{ old('serologie') == '0' ? 'selected' : '' }}>Négative</option>
          <option value="1" {{ old('serologie') == '1' ? 'selected' : '' }}>Positive</option>
        </select>
        @error('serologie')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-4 col-lg-3">
        <label class="form-label">Support</label>
        <select name="support" class="form-select">
          <option value="double" {{ old('support') == 'double' ? 'selected' : '' }}>Double</option>
          <option value="triple" {{ old('support') == 'triple' ? 'selected' : '' }}>Triple</option>
          <option value="quadruple" {{ old('support') == 'quadruple' ? 'selected' : '' }}>Quadruple</option>
        </select>
        @error('support')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="row">
        <div class="col mb-4">
          <br>
          <label class="form-label">Source du Don :</label>
          <div class="form-check">
            <input type="radio" id="source_app" name="sourceDon" value="App" class="form-check-input" {{ old('sourceDon') == 'App' ? 'checked' : '' }}>
            <label class="form-check-label" for="source_app">App</label>
          </div>
          <div class="form-check">
            <input type="radio" id="source_ami" name="sourceDon" value="Ami" class="form-check-input" {{ old('sourceDon') == 'Ami' ? 'checked' : '' }}>
            <label class="form-check-label" for="source_ami">Ami</label>
          </div>
          <div class="form-check">
            <input type="radio" id="source_volontaire" name="sourceDon" value="Volontaire" class="form-check-input" {{ old('sourceDon') == 'Volontaire' ? 'checked' : '' }}>
            <label class="form-check-label" for="source_volontaire">Volontaire</label>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Réactions</label>
        <textarea class="form-control" name="reactions" rows="3" placeholder="Perte de connaissance, Malaise, Syncope, Convulsions ou autres .... ?">{{ old('reactions') }}</textarea>
        @error('reactions')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Observations</label>
        <textarea class="form-control" name="observations" rows="3">{{ old('observations') }}</textarea>
        @error('observations')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-12">
        <label for="persMedicale" class="form-label">Autoriser *</label>
        <select id="persMedicale" name="persMedicale" class="form-select">
          <option value="">Sélectionner</option>
          @foreach ($listePersonneMedicale as $persMedicale)
          <option value="{{ $persMedicale->id }}" {{ old('persMedicale') == $persMedicale->id ? 'selected' : '' }}>
            {{ $persMedicale->personnee()->value("nom") }} {{ $persMedicale->personnee()->value("prenom") }} ({{ $persMedicale->fonction }})
          </option>
          @endforeach
        </select>
        @error('persMedicale')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-12">
        <label for="persMedicaleSuperviser" class="form-label">Superviser *</label>
        <select id="persMedicaleSuperviser" name="persMedicaleSuperviser" class="form-select">
          <option value="">Sélectionner</option>
          @foreach ($listePersonneMedicale as $persMedicale)
          <option value="{{ $persMedicale->id }}" {{ old('persMedicaleSuperviser', $personne->persMedicaleSuperviser ?? '') == $persMedicale->id ? 'selected' : '' }}>
            {{ $persMedicale->personnee()->value("nom") }} {{ $persMedicale->personnee()->value("prenom") }} ({{ $persMedicale->fonction }})
          </option>
          @endforeach
        </select>
        @error('persMedicaleSuperviser')<small class="text text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-auto enregsitrerbtn">
        <button type="submit" class="btn btn-primary mb-3 py-2 px-4">Ajouter ce don</button>
      </div>
    </form>
  </div>
</div>
@endsection
