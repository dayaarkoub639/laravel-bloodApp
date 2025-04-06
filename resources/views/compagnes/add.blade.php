@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">
    <form class="card-title fw-semibold mb-4" action="{{ route('compagnes') }}">
        Ajouter une nouvelle campagne
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary">Liste</button>
    </form> 

    <form id="createCompagneForm" action="{{ route('compagnes.store') }}" method="POST">
        @csrf

        <div class="form-group col mb-3">
                <label for="idCentre" class="form-label">Centre</label>
                <select id="idCentre" name="idCentre" class="form-select @error('idCentre') is-invalid @enderror">
                    <option value="">Sélectionner une centre</option>
                    @foreach ($listeCentres as $centre)
                        <option value="{{ $centre->id }}">
                            {{ $centre->nom }}
                        </option>
                    @endforeach
                </select>
                @error('idCentre')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


        <div class="mb-3">
            <label for="address" class="form-label">Adresse</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <!-- Sélection de la Wilaya -->
            <div class="form-group col">
                <label for="wilaya" class="form-label">Wilaya</label>
                <select id="wilaya" name="wilaya" class="form-select @error('wilaya') is-invalid @enderror">
                    <option value="">Sélectionner une wilaya</option>
                    @foreach ($listeWilayas as $wilaya)
                        <option value="{{ $wilaya->id }}" {{ old('wilaya') == $wilaya->id ? 'selected' : '' }}>{{ $wilaya->name_ascii }}</option>
                    @endforeach
                </select>
                @error('wilaya')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Sélection de la Commune -->
            <div class="form-group col">
                <label for="commune" class="form-label">Commune</label>
                <select id="commune" name="commune" class="form-select @error('commune') is-invalid @enderror">
                    <option value="">Sélectionner une commune</option>
                </select>
                @error('commune')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="etablissement" class="form-label">Établissement</label>
            <input type="text" class="form-control @error('etablissement') is-invalid @enderror" id="etablissement" name="etablissement" value="{{ old('etablissement') }}" required>
            @error('etablissement')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="localisation" class="form-label">Localisation</label>
            <input type="text" class="form-control @error('localisation') is-invalid @enderror" id="localisation" name="localisation" value="{{ old('localisation') }}" required>
            @error('localisation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="dateDebut" class="form-label">Date de début</label>
            <input type="date" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" name="dateDebut" value="{{ old('dateDebut') }}" required>
            @error('dateDebut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="dateFin" class="form-label">Date de fin</label>
            <input type="date" class="form-control @error('dateFin') is-invalid @enderror" id="dateFin" name="dateFin" value="{{ old('dateFin') }}" required>
            @error('dateFin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button> 
    </form>
</div>


<!-- AJAX pour récupérer les communes dynamiquement -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#wilaya').change(function() {
        var wilaya_id = $(this).val();
        var wilaya_code = wilaya_id.toString().padStart(2, '0'); // Formate le code wilaya

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
});
</script>
@endsection