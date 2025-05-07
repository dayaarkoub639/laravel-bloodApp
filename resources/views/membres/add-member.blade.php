@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">

    <form class="card-title fw-semibold mb-4" action="{{route('membres')}}">
        Ajouter un membre
        <button type="submit" class="mx-2 btn btn-sm btn-outline-primary"> Liste</button>
    </form>

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
   <!-- resources/views/membres/create.blade.php -->

<form class="row g-3" method="POST" action="{{ route('membres.store') }}">
    @csrf
    <div class="col-md-8 pe-5">
        <div class="card">
            <div class="card-header">Informations générales</div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}">
                        @error('nom') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}">
                        @error('prenom') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                </div><br>

                <div class="row">
                    <div class="form-group col">
                        <label for="birthdaydate" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="birthdaydate" name="birthdaydate" value="{{ old('birthdaydate') }}">
                        @error('birthdaydate') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col">
                        <label for="lieuNaissance" class="form-label">Lieu de naissance</label>
                        <input type="text" id="lieuNaissance" name="lieuNaissance" class="form-control" value="{{ old('lieuNaissance') }}">
                        @error('lieuNaissance') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                </div><br>

                <div class="row">
                    <div class="form-group col">
                        <label for="gender" class="form-label">Civilité *</label>
                        <select id="gender" name="gender" class="form-select">
                            <option value="">Sélectionner la civilité *</option>
                            <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Homme</option>
                            <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('gender') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col" id="epousede_field" style="display: none;">
                        <label for="epousede" class="form-label">Épouse de</label>
                        <input type="text" id="epousede" name="epouseDe" class="form-control" value="{{ old('epouseDe') }}">
                        @error('epouseDe') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                </div><br>

                <div class="row">
                    <div class="form-group col">
                        <label for="phone01" class="form-label">Téléphone principal</label>
                        <input type="number" class="form-control" id="phone01" name="phone01" value="{{ old('phone01') }}">
                        @error('phone01') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col">
                        <label for="phone02" class="form-label">Téléphone secondaire</label>
                        <input type="number" class="form-control" id="phone02" name="phone02" value="{{ old('phone02') }}">
                        @error('phone02') <small class="text text-danger">{{ $message }}</small> @enderror
                    </div>
                </div><br>

                <div class="form-group">
                    <label for="adresseDomicile" class="form-label">Adresse (Domicile)</label>
                    <textarea class="form-control" id="adresseDomicile" name="adresseDomicile">{{ old('adresseDomicile') }}</textarea>
                    @error('adresseDomicile') <small class="text text-danger">{{ $message }}</small> @enderror
                </div><br>

                <div class="row">
                    <div class="form-group col">
                        <label for="wilayaDomicile" class="form-label">Wilaya (Domicile)</label>
                        <select id="wilayaDomicile" name="wilayaDomicile" class="form-select">
                            <option value="">Sélectionner une wilaya</option>
                            @foreach ($listeWilayas as $wilaya)
                                <option value="{{ $wilaya->id }}" {{ old('wilayaDomicile') == $wilaya->id ? 'selected' : '' }}>{{ $wilaya->name_ascii }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="communeDomicile" class="form-label">Commune (Domicile)</label>
                        <select id="communeDomicile" name="communeDomicile" class="form-select">
                            <option value="">Sélectionner une commune</option>
                            <!-- Remplissage dynamique selon la wilaya -->
                        </select>
                    </div>
                </div><br>

                <div class="form-group">
                    <label for="adresseProfessionnelle" class="form-label">Adresse (Professionnelle)</label>
                    <textarea class="form-control" id="adresseProfessionnelle" name="adresseProfessionnelle">{{ old('adresseProfessionnelle') }}</textarea>
                    @error('adresseProfessionnelle') <small class="text text-danger">{{ $message }}</small> @enderror
                </div><br>

                <div class="row">
                    <div class="form-group col">
                        <label for="wilayaProfessionnelle" class="form-label">Wilaya (Professionnelle)</label>
                        <select id="wilayaProfessionnelle" name="wilayaProfessionnelle" class="form-select">
                            <option value="">Sélectionner une wilaya</option>
                            @foreach ($listeWilayas as $wilaya)
                                <option value="{{ $wilaya->id }}" {{ old('wilayaProfessionnelle') == $wilaya->id ? 'selected' : '' }}>{{ $wilaya->name_ascii }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="communeProfessionnelle" class="form-label">Commune (Professionnelle)</label>
                        <select id="communeProfessionnelle" name="communeProfessionnelle" class="form-select">
                            <option value="">Sélectionner une commune</option>
                        </select>
                    </div>
                </div><br>

                <div class="form-group mb-3">
                    <label for="observations" class="form-label">Observations médicales</label>
                    <textarea class="form-control" id="observations" name="observations">{{ old('observations') }}</textarea>
                    @error('observations') <small class="text text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn right btn-primary mb-3 py-2 px-4">Ajouter</button>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Groupage -->
        <div class="card">
            <div class="card-header">Groupage</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="groupage" class="form-label">Groupage</label>
                    <select id="groupage" name="groupage" class="form-select">
                        <option value="">Choisir le groupage</option>
                        @foreach ($listeGroupage as $value)
                            <option value="{{ $value->id }}" {{ old('groupage') == $value->id ? 'selected' : '' }}>{{ $value->type }}</option>
                        @endforeach
                    </select>
                    @error('groupage') <small class="text text-danger">{{ $message }}</small> @enderror
                </div><br>

                @php
                    $phenotypes = [
                        'phenotypeCmaj' => 'C',
                        'phenotypeEmaj' => 'E',
                        'phenotypeCmin' => 'c',
                        'phenotypeEmin' => 'e',
                        'phenotypeKell' => 'Kell'
                    ];
                @endphp

                @foreach ($phenotypes as $name => $label)
                    <div class="form-group">
                        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                        <select id="{{ $name }}" name="{{ $name }}" class="form-select">
                            <option value="">Choisir +/-</option>
                            <option value="1" {{ old($name) == '1' ? 'selected' : '' }}>+</option>
                            <option value="0" {{ old($name) == '0' ? 'selected' : '' }}>-</option>
                        </select>
                        @error($name) <small class="text text-danger">{{ $message }}</small> @enderror
                    </div><br>
                @endforeach
            </div>
        </div>

        <!-- Compte membre -->
        <div class="card">
            <div class="card-header">Compte Membre</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="type_personne" class="form-label">Type de personne</label>
                    <select id="type_personne" name="type_personne" class="form-select">
                        <option value="user" {{ old('type_personne') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ old('type_personne') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        <option value="personnelMedical" {{ old('type_personne') == 'personnelMedical' ? 'selected' : '' }}>Personnel Médical</option>
                    </select>
                    @error('type_personne') <small class="text text-danger">{{ $message }}</small> @enderror
                </div><br>

                <div class="form-group">
                    <label for="motDePasse" class="form-label">Mot de passe</label>
                    <input type="password" id="motDePasse" name="motDePasse" class="form-control">
                    @error('motDePasse') <small class="text-danger">{{ $message }}</small> @enderror
                </div><br>

                <div class="form-group">
                    <label for="motDePasse_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" id="motDePasse_confirmation" name="motDePasse_confirmation" class="form-control">
                    @error('motDePasse_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                </div><br>

                <div class="form-group" id="idCentre-container" style="display: none;">
                    <label for="idCentre" class="form-label">Centre</label>
                    <select id="idCentre" name="idCentre" class="form-select">
                        <option value="">Sélectionner un centre</option>
                        @foreach ($listeCentres as $centre)
                            <option value="{{ $centre->id }}" {{ old('idCentre') == $centre->id ? 'selected' : '' }}>{{ $centre->nom }}</option>
                        @endforeach
                    </select>
                    @error('idCentre') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group" id="pseudo_field" style="display: none;">
                    <br><label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" class="form-control" value="{{ old('pseudo') }}">
                </div>

                <div class="form-group" id="acces_field" style="display: none;">
                    <br><label for="acces" class="form-label">Accès</label>
                    <select id="acces" name="acces" class="form-select">
                        <option value="superviser" {{ old('acces') == 'superviser' ? 'selected' : '' }}>Superviser</option>
                        <option value="autoriser" {{ old('acces') == 'autoriser' ? 'selected' : '' }}>Autoriser</option>
                    </select>
                </div>

                <div class="form-group" id="accesadmin_field" style="display: none;">
                    <br><label for="accesadmin" class="form-label">Accès admin</label>
                    <select id="accesadmin" name="accesadmin" class="form-select">
                        <option value="admin" {{ old('accesadmin') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="sousadmin" {{ old('accesadmin') == 'sousadmin' ? 'selected' : '' }}>Sous-admin</option>
                    </select>
                </div>

                <div class="form-group" id="fonction_field" style="display: none;">
                    <br><label for="fonction" class="form-label">Fonction</label>
                    <select id="fonction" name="fonction" class="form-select">
                        <option value="medecin" {{ old('fonction') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                        <option value="infirmier" {{ old('fonction') == 'infirmier' ? 'selected' : '' }}>Infirmier</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>

</div>
<!-- AJAX pour récupérer les communes dynamiquement -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('#wilayaDomicile').change(function() {
        var wilaya_id = $(this).val();
       // Convertir le code wilaya en format 01, 02, etc.
       var wilaya_code = wilaya_id.padStart(2, '0');
        $('#communeDomicile').html('<option value="">Chargement...</option>');

        if (wilaya_code) {
            $.ajax({
                url: '/api/communes/' + wilaya_code,
                type: 'GET',
                success: function(data) {
                    $('#communeDomicile').html('<option value="">Sélectionner une commune</option>');
                    $.each(data, function(index, commune) {
                        $('#communeDomicile').append('<option value="' + commune.id + '">' + commune.commune_name + '</option>');
                    });
                }
            });
        } else {
            $('#communeDomicile').html('<option value="">Sélectionner une wilaya d\'abord</option>');
        }
    });

    $('#wilayaProfessionnelle').change(function() {
        var wilaya_id = $(this).val();
       // Convertir le code wilaya en format 01, 02, etc.
       var wilaya_code = wilaya_id.padStart(2, '0');
        $('#communeProfessionnelle').html('<option value="">Chargement...</option>');

        if (wilaya_code) {
            $.ajax({
                url: '/api/communes/' + wilaya_code,
                type: 'GET',
                success: function(data) {
                    $('#communeProfessionnelle').html('<option value="">Sélectionner une commune</option>');
                    $.each(data, function(index, commune) {
                        $('#communeProfessionnelle').append('<option value="' + commune.id + '">' + commune.commune_name + '</option>');
                    });
                }
            });
        } else {
            $('#communeProfessionnelle').html('<option value="">Sélectionner une wilaya d\'abord</option>');
        }
    });

        function updateFields() {
            var typePersonne = $('#type_personne').val();

            // Masquer tous les champs d'abord
            $('#pseudo_field, #acces_field, #fonction_field, #accesadmin_field').hide();

            // Afficher le champ correspondant
            if (typePersonne === 'user') {
                $('#pseudo_field').show();
            } else if (typePersonne === 'admin') {

                $('#accesadmin_field').show();
            } else if (typePersonne === 'personnelMedical') {
                $('#fonction_field').show();
                $('#acces_field').show();

            }
        }

        // Exécuter la fonction au chargement si un type est déjà sélectionné
        updateFields();

        // Exécuter la fonction lorsque l'utilisateur change la sélection
        $('#type_personne').on('change', updateFields);
    });

    function toggleEpouseDe() {
        var gender = document.getElementById("gender").value;
        var epouseDeField = document.getElementById("epousede_field");

        if (gender === "0") {
            epouseDeField.style.display = "block";
        } else {
            epouseDeField.style.display = "none";
        }
    }

    // Vérification au chargement de la page (utile si une validation a échoué)
    document.addEventListener("DOMContentLoaded", function() {
        toggleEpouseDe();
    });
   // Get the form elements
    const typePersonneSelect = document.getElementById('type_personne');
    const idCentreContainer = document.getElementById('idCentre-container');

    // Event listener for changes in type_personne dropdown
    typePersonneSelect.addEventListener('change', function () {
        if (this.value === 'user') {
            idCentreContainer.style.display = 'none';
        } else {
            idCentreContainer.style.display = 'block';
        }
    });

    // Initialize the display on page load (in case the form is pre-filled)
    if (typePersonneSelect.value === 'user') {
        idCentreContainer.style.display = 'none';
    }
</script>
@endsection
