@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">
    <form class="card-title fw-semibold mb-4" action="{{ route('compagnes') }}">
        Modifier une campagne
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary">Liste</button>
    </form> 

    <form id="editCompagneForm" action="{{ route('compagnes.update', $compagne->id) }}" method="POST">
        @csrf
        @method('PUT')
 
    
        <div class="form-group col mb-3">
                <label for="idCentre" class="form-label">Centre</label>
                <select id="idCentre" name="idCentre" class="form-select @error('idCentre') is-invalid @enderror">
                    <option value="">Sélectionner une centre</option>
                    @foreach ($listeCentres as $centre)
                        <option value="{{ $centre->id }}" {{ old('idCentre', $compagne->idCentre) == $centre->id ? 'selected' : '' }}>
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
            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $compagne->address) }}" required>
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
                        <option value="{{ $wilaya->id }}" {{ old('wilaya', $compagne->wilaya) == $wilaya->id ? 'selected' : '' }}>
                            {{ $wilaya->name_ascii }}
                        </option>
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
            <input type="text" class="form-control @error('etablissement') is-invalid @enderror" id="etablissement" name="etablissement" value="{{ old('etablissement', $compagne->etablissement) }}" required>
            @error('etablissement')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
 

<div class="row mb-3">
        <div class="col">
            <label for="dateDebut" class="form-label">Date de début</label>
            <input type="date" class="form-control @error('dateDebut') is-invalid @enderror" id="dateDebut" name="dateDebut" value="{{ old('dateDebut', $compagne->dateDebut) }}" required>
            @error('dateDebut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
    <label for="heureDebut" class="form-label">Heure de début</label>
    <input type="time" class="form-control @error('heureDebut') is-invalid @enderror" id="heureDebut" name="heureDebut" value="{{ old('heureDebut', $compagne->heureDebut) }}" required>
    @error('heureDebut')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
</div>
<!-- Champs Google Maps et coordonnées -->
<div class="mb-3">
            <label for="link" class="form-label">Lien de Google Maps</label>
            <input type="text" class="form-control" id="link"  value="https://maps.app.goo.gl/kgk7BY3e2g5goBbY6" required>
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $compagne->latitude) }}" readonly>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $compagne->longitude) }}" readonly>
        </div>

        <!-- Button pour récupérer les coordonnées -->
        <button type="button" class="btn btn-outline-primary" id="getCoordinatesBtn">Obtenir les coordonnées</button>

        <br><br>
<div class="row mb-3">
        <div class="col">
            <label for="dateFin" class="form-label">Date de fin</label>
            <input type="date" class="form-control @error('dateFin') is-invalid @enderror" id="dateFin" name="dateFin" value="{{ old('dateFin', $compagne->dateFin) }}" required>
            @error('dateFin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
    <label for="heureFin" class="form-label">Heure de fin</label>
    <input type="time" class="form-control @error('heureFin') is-invalid @enderror" id="heureFin" name="heureFin" value="{{ old('heureFin', $compagne->heureFin) }}" required>
    @error('heureFin')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
</div>
        <button type="button" class="btn btn-outline-primary" onclick="window.history.back();">Annuler</button>
        <button type="button" class="btn btn-primary mx-3" id="confirm-update">
            Mettre à jour
        </button>
    </form>
</div>

 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('confirm-update').addEventListener('click', function () {
            let form = document.getElementById('editCompagneForm' );
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
    var selectedCommune = "{{ old('commune', $compagne->commune) }}";
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

