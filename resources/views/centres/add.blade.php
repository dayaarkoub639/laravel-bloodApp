@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">
    <form class="card-title fw-semibold mb-4" action="{{ route('centres') }}">
        Ajouter une nouvelle centre
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary">Liste</button>
    </form> 

    <form action="{{ route('centres.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" >
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
    @error('image')
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
            <label for="numeroTlp1" class="form-label">Numéro de tel 01</label>
            <input type="text" class="form-control @error('numeroTlp1') is-invalid @enderror" id="numeroTlp1" name="numeroTlp1" value="{{ old('numeroTlp1') }}" required>
            @error('numeroTlp1')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="numeroTlp2" class="form-label">Numéro de tel 02</label>
            <input type="text" class="form-control @error('numeroTlp2') is-invalid @enderror" id="numeroTlp2" name="numeroTlp2" value="{{ old('numeroTlp2') }}">
            @error('numeroTlp2')
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
