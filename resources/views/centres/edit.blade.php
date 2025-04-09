@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">
    <form   class="card-title fw-semibold mb-4" action="{{ route('centres') }}">
        Modifier le centre
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary">Liste</button>
    </form> 

    <form id="editCentreForm" action="{{ route('centres.update', $centre->id) }}" method="POST"  enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $centre->nom) }}" required>
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        {{-- Affichage de l'image actuelle --}}
        <div class="mb-3">
            <label class="form-label">Image actuelle :</label><br>
            @if($centre->imageUrl)
                <img src="{{ asset('storage/' . $centre->imageUrl) }}" alt="Image du centre" class="img-thumbnail" width="200">
            @else
                <p>Aucune image disponible</p>
            @endif
        </div>

        {{-- Input pour choisir une nouvelle image --}}
        <div class="mb-3">
            <label for="image" class="form-label">Nouvelle image</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Adresse</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $centre->address) }}" required>
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
                        <option value="{{ $wilaya->id }}" {{ old('wilaya', $centre->wilaya) == $wilaya->id ? 'selected' : '' }}>{{ $wilaya->name_ascii }}</option>
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
            <input type="text" class="form-control @error('numeroTlp1') is-invalid @enderror" id="numeroTlp1" name="numeroTlp1" value="{{ old('numeroTlp1', $centre->numeroTlp1) }}" required>
            @error('numeroTlp1')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="numeroTlp2" class="form-label">Numéro de tel 02</label>
            <input type="text" class="form-control @error('numeroTlp2') is-invalid @enderror" id="numeroTlp2" name="numeroTlp2" value="{{ old('numeroTlp2', $centre->numeroTlp2) }}">
            @error('numeroTlp2')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <!-- Champs Google Maps et coordonnées -->
<div class="mb-3">
            <label for="link" class="form-label">Lien de Google Maps</label>
            <input type="text" class="form-control" id="link"  value="https://maps.app.goo.gl/kgk7BY3e2g5goBbY6" required>
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $centre->latitude) }}" readonly>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $centre->longitude) }}" readonly>
        </div>

        <!-- Button pour récupérer les coordonnées -->
        <button type="button" class="btn btn-outline-primary" id="getCoordinatesBtn">Obtenir les coordonnées</button>

        <br><br>
        <button type="button" class="btn btn-outline-primary" onclick="window.history.back();">Annuler</button>
        <button type="button" class="btn btn-primary mx-3" id="confirm-update">
            Mettre à jour</button>
      
    </form>
</div>
 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('confirm-update').addEventListener('click', function () {
            let form = document.getElementById('editCentreForm' );
            Swal.fire({
                title: "Confirmer la mise à jour",
                text: "Voulez-vous vraiment mettre à jour ces informations ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, mettre à jour",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    form.submit();
                }
            });
        });
    });
</script>
<!-- AJAX pour récupérer les communes dynamiquement -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadCommunes(wilaya_id, selected_commune = null) {
        if (!wilaya_id) {
            $('#commune').html('<option value="">Sélectionner une wilaya d\'abord</option>');
            return;
        }
        var wilaya_code = wilaya_id.toString().padStart(2, '0'); // Formate le code wilaya

        $('#commune').html('<option value="">Chargement...</option>');
        $.ajax({
            url: '/api/communes/' + wilaya_code,
            type: 'GET',
            success: function(data) {
                $('#commune').html('<option value="">Sélectionner une commune</option>');
                $.each(data, function(index, commune) {
                    let selected = (selected_commune == commune.id) ? 'selected' : '';
                    $('#commune').append('<option value="' + commune.id + '" ' + selected + '>' + commune.commune_name + '</option>');
                });
            }
        });
    }

    // Charger les communes si une wilaya est déjà sélectionnée (édition)
    var selectedWilaya = $('#wilaya').val();
    var selectedCommune = "{{ old('commune', $centre->commune) }}";
    if (selectedWilaya) {
        loadCommunes(selectedWilaya, selectedCommune);
    }

    $('#wilaya').change(function() {
        loadCommunes($(this).val());
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
