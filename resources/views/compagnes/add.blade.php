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
                    <option v<alue="">Sélectionner une centre</option>
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

            <div class="form-group col mb-3" id="idCentre-container" style="display: none;">
    <label for="idCentre" class="form-label">Centre</label>
    <select id="idCentre" name="idCentre" class="form-select @error('idCentre') is-invalid @enderror">
        <option value="">Sélectionner un centre</option>
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
    <label for="link" class="form-label">Lien de Google Maps</label>
    <input type="text" class="form-control" id="link" value="https://maps.app.goo.gl/kgk7BY3e2g5goBbY6" required>
</div>

<div class="mb-3">
    <label for="latitude" class="form-label">Latitude</label>
    <input type="text" class="form-control" id="latitude"  name="latitude" readonly>
</div>

<div class="mb-3">
    <label for="longitude" class="form-label">Longitude</label>
    <input type="text" class="form-control" id="longitude" name="longitude" readonly>
</div>

<!-- Button pour récupérer les coordonnées -->
<button type="button" class="btn btn-outline-primary" id="getCoordinatesBtn">Obtenir les coordonnées</button>

<br><br>

        <div class="row mb-3">
        <div class="col">
            <label for="dateDebut" class="form-label">Date de début</label>
            <input type="date" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" name="dateDebut" value="{{ old('dateDebut') }}" required>
            @error('dateDebut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
    <label for="heureDebut" class="form-label">Heure de début</label>
    <input type="time" class="form-control @error('heureDebut') is-invalid @enderror" id="heureDebut" name="heureDebut" value="{{ old('heureDebut') }}" required>
    @error('heureDebut')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
 
</div>
<div class="row mb-3">
        <div class="col">
     
            <label for="dateFin" class="form-label">Date de fin</label>
            <input type="date" class="form-control @error('dateFin') is-invalid @enderror" id="dateFin" name="dateFin" value="{{ old('dateFin') }}" required>
            @error('dateFin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
    <label for="heureFin" class="form-label">Heure de fin</label>
    <input type="time" class="form-control @error('heureFin') is-invalid @enderror" id="heureFin" name="heureFin" value="{{ old('heureFin') }}" required>
    @error('heureFin')
        <div class="text-danger">{{ $message }}</div>
    @enderror
    </div>
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

 

  // Écouter le clic du bouton
  document.getElementById('getCoordinatesBtn').addEventListener('click', function() {
            var link = document.getElementById('link').value;

            // Faire une requête AJAX vers Laravel pour obtenir les coordonnées
            fetch('/resolve-google-map-url?url=' + encodeURIComponent(link))
                .then(response => response.json())
                .then(data => {
                    if (data.latitude && data.longitude) {
                        // Mettre à jour les champs Latitude et Longitude
                        document.getElementById('latitude').value = data.latitude;
                        document.getElementById('longitude').value = data.longitude;
                    } else {
                        alert("Aucune coordonnée trouvée.");
                    }
                })
                .catch(error => {
                    console.error("Erreur:", error);
                    alert("Une erreur est survenue.");
                });
        });

</script>
@endsection