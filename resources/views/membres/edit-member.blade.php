@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">


    <form class="card-title fw-semibold mb-4" action="{{route('membres')}}">
        Modifier un membre
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

    <form class="row g-3" method="POST" action="{{ route('membres.update', $personne->idUser) }}" id="form">
        @csrf
        @method('PUT')
        <div class="col-md-8 pe-5">
            <div class="card">
                <div class="card-header">
                    Informations générales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="{{ old('nom', $personne->nom) }}">
                            @if ($errors->has('nom'))
                            <small class="text text-danger">
                                {{ $errors->first('nom') }}
                            </small>
                            @endif
                        </div>

                        <div class="form-group col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                value="{{ old('prenom', $personne->prenom) }}">
                            @if ($errors->has('prenom'))
                            <small class="text text-danger">
                                {{ $errors->first('prenom') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="form-group col">
                            <label for="birthdaydate" class="form-label">Date de naissance </label>
                            <input type="date" class="form-control" id="birthdaydate" name="birthdaydate"
                                value="{{ old('birthdaydate',   \Carbon\Carbon::parse($personne->DateDeNess)->format('Y-m-d') ) }}">

                            @if ($errors->has('birthdaydate'))
                            <small class="text text-danger">
                                {{ $errors->first('birthdaydate') }}
                            </small>
                            @endif
                        </div>

                        <div class="form-group col">
                            <label for="lieuNaissance" class="form-label">Lieu de naissance</label>
                            <input type="text" id="lieuNaissance" name="lieuNaissance" class="form-control"
                                value="{{ old('lieuNaissance', $personne->lieuNaissance) }}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group col">
                            <label for="gender" class="form-label">Civilité * </label>
                            <select id="gender" name="gender" class="form-select" onchange="toggleEpouseDe()">
                                <option value="">Sélectionner la civilité *</option>
                                <option value="1" {{ old('gender', $personne->gender) == "1" ? 'selected' : '' }}>Homme
                                </option>
                                <option value="0" {{ old('gender', $personne->gender) == "0" ? 'selected' : '' }}>Femme
                                </option>
                            </select>
                            @if ($errors->has('gender'))
                            <small class="text text-danger">
                                {{ $errors->first('gender') }}
                            </small>
                            @endif
                        </div>

                        <div class="form-group col" id="epousede_field" style="display: none;">
                            <label for="epousede" class="form-label">Épouse de</label>
                            <input type="text" id="epousede" name="epouseDe" class="form-control"
                                value="{{ old('epouseDe', $personne->epouseDe ?? '') }}">
                        </div>

                    </div>

                    <br>
                    <div class="row">
                        <div class="form-group col">
                            <label for="phone01" class="form-label">Téléphone principale</label>
                            <input type="number" class="form-control" id="phone01" name="phone01"
                                value="{{ old('numeroTlp1', $personne->numeroTlp1) }}">
                            @if ($errors->has('phone01'))
                            <small class="text text-danger">
                                {{ $errors->first('phone01') }}
                            </small>
                            @endif
                        </div>

                        <div class="form-group col">
                            <label for="phone02" class="form-label">Téléphone secondaire </label>
                            <input type="number" class="form-control" id="phone02" name="phone02"
                                value="{{ old('numeroTlp2', $personne->numeroTlp2) }}">
                            @if ($errors->has('phone02'))
                            <small class="text text-danger">
                                {{ $errors->first('phone02') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <br>


                    <div class="form-group">
                        <label for="adresseDomicile" class="form-label">Adresse (Domicile)</label>
                        <textarea class="form-control" id="adresseDomicile" name="adresseDomicile">
                            {{ $personne->adresse }}
                            </textarea>
                        @if ($errors->has('adresseDomicile'))
                        <small class="text text-danger">
                            {{ $errors->first('adresseDomicile') }}
                        </small>
                        @endif
                    </div>
                    <br>
                    <div class="row">
                        <!-- Sélection de la Wilaya -->
                        <div class="form-group col">
                            <label for="wilayaDomicile" class="form-label">Wilaya (Domicile)</label>
                            <select id="wilayaDomicile" name="wilayaDomicile" class="form-select">
                                <option value="">Sélectionner une wilaya</option>
                                @foreach ($listeWilayas as $wilaya)

                                <option value='{!! $wilaya->id !!}' {!! $wilaya->id ==
                                    $personne->wilaya_domicile_id ? 'selected' : ''!!}
                                    >{!! $wilaya->name_ascii !!}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection de la Commune -->
                        <div class="form-group col">
                            <label for="communeDomicile" class="form-label">Commune (Domicile)</label>
                            <select id="communeDomicile" name="communeDomicile" class="form-select">
                                <option value="">Sélectionner une commune</option>

                            </select>
                        </div>
                        <script>
                            // Définir la variable JavaScript depuis Blade
                            var selectedCommuneDomicile = {{ $personne->commune_domicile_id ?? 'null' }};
                            var selectedWilayaDomicile = {{ $personne->wilaya_domicile_id ?? 'null' }};
                            var selectedCivilite = {{ $personne->gender ?? 'null' }};

                        </script>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="adresseProfessionnelle" class="form-label">Adresse (Professionnelle)</label>
                        <textarea class="form-control" id="adresseProfessionnelle" name="adresseProfessionnelle">
                            {{ $personne->adressePro }}
                            </textarea>
                        @if ($errors->has('adresseProfessionnelle'))
                        <small class="text text-danger">
                            {{ $errors->first('adresseProfessionnelle') }}
                        </small>
                        @endif
                    </div>
                    <br>

                    <div class="row">
                        <!-- Sélection de la Wilaya -->
                        <div class="form-group col">
                            <label for="wilayaProfessionnelle" class="form-label">Wilaya (Professionnelle)</label>
                            <select id="wilayaProfessionnelle" name="wilayaProfessionnelle" class="form-select ">
                                <option value="">Sélectionner une wilaya</option>
                                @foreach ($listeWilayas as $wilaya)
                                <option value='{!! $wilaya->id !!}' {!! $wilaya->id ==
                                    $personne->wilaya_prof_id ? 'selected' : ''!!}
                                    >{!! $wilaya->name_ascii !!}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection de la Commune -->
                        <div class="form-group col">
                            <label for="communeProfessionnelle" class="form-label">Commune (Professionnelle)</label>
                            <select id="communeProfessionnelle" name="communeProfessionnelle" class="form-select">
                                <option value="">Sélectionner une commune</option>
                            </select>
                        </div>
                    </div>
                    <!-- Définir l'ID de la commune sélectionnée depuis Blade -->

                    <script>
                        // Définir la variable JavaScript depuis Blade
                        var selectedCommuneProfessionnelle = {{ $personne->commune_prof_id ?? 'null' }};
                        var selectedWilayaProfessionnelle = {{ $personne->wilaya_prof_id ?? 'null' }};

                    </script>
                    <br>


                    <div class="form-group mb-3">
                        <label for="observations" class="form-label">Observations médicales</label>
                        <textarea class="form-control" id="observations" name="observations">{{ $personne->observations }}  </textarea>
                        @if ($errors->has('observations'))
                        <small class="text text-danger">
                            {{ $errors->first('observations') }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-outline-primary" onclick="window.history.back();">Annuler</button>
                <button type="button" class="btn right btn-primary mx-3 " id="confirm-update">
                    Enregistrer les modifications</button>
            </div>
        </div>
        <div class="col-md-4  ">
            <div class="col">
                <div class="card">
                    <div class="card-header"> Groupage </div>
                    <div class="card-body">

                        <div class="form-group {{ $errors->has('groupage') ? 'has-error' : '' }} ">
                            <label for="groupage" class="form-label text-center">Groupage</label>
                            <select id='groupage' name='groupage' class="form-select">
                                <option value=''>Choisir le groupage</option>
                                @foreach ($listeGroupage as $key => $value)
                                <option {!! $value->id == $personne->idGroupage ? 'selected' : ''!!} value='{!!
                                    $value->id !!}'>{!! $value->type !!}</option>
                                @endforeach

                            </select>
                            @if ($errors->has('groupage'))
                            <small class="text text-danger">
                                {{ $errors->first('groupage') }}
                            </small>
                            @endif
                        </div>
                        <br> <br>
                        <label class="form-label text-center">Phénotype</label>


                        <div class="form-group {{ $errors->has('phenotypeCmaj') ? 'has-error' : '' }} ">
                            <label for="phenotypeCmaj" class="form-label text-center">C</label>
                            <select id='phenotypeCmaj' name='phenotypeCmaj' class="form-select">
                                <option value=''>Choisir +/-</option>
                                <option {!! $personne->cMaj == 1 ? 'selected' : ''!!} value="1" >+</option>
                                <option {!! $personne->cMaj == 0 ? 'selected' : ''!!} value="0" >-</option>
                            </select>

                            @if ($errors->has('phenotypeCmaj'))
                            <small class="text text-danger">
                                {{ $errors->first('phenotypeCmaj') }}
                            </small>
                            @endif
                        </div>

                        <br>
                        <div class="form-group {{ $errors->has('phenotypeEmaj') ? 'has-error' : '' }} ">
                            <label for="phenotypeEmaj" class="form-label text-center">E</label>
                            <select id='phenotypeEmaj' name='phenotypeEmaj' class="form-select">
                                <option value=''>Choisir +/-</option>
                                <option value="1" {!! $personne->eMaj == 1 ? 'selected' : ''!!} >+</option>
                                <option value="0" {!! $personne->eMaj == 0 ? 'selected' : ''!!} >-</option>
                            </select>

                            @if ($errors->has('phenotypeEmaj'))
                            <small class="text text-danger">
                                {{ $errors->first('phenotypeEmaj') }}
                            </small>
                            @endif
                        </div>
                        <br>
                        <div class="form-group {{ $errors->has('phenotypeCmin') ? 'has-error' : '' }} ">
                            <label for="phenotypeCmin" class="form-label text-center">c</label>
                            <select id='phenotypeCmin' name='phenotypeCmin' class="form-select">
                                <option value=''>Choisir +/-</option>
                                <option value="1" {!! $personne->cMin == 1 ? 'selected' : ''!!} >+</option>
                                <option value="0" {!! $personne->cMin == 0 ? 'selected' : ''!!} >-</option>
                            </select>

                            @if ($errors->has('phenotypeCmin'))
                            <small class="text text-danger">
                                {{ $errors->first('phenotypeCmin') }}
                            </small>
                            @endif
                        </div>
                        <br>
                        <div class="form-group {{ $errors->has('phenotypeEmin') ? 'has-error' : '' }} ">
                            <label for="phenotypeEmin" class="form-label text-center">e</label>
                            <select id='phenotypeEmin' name='phenotypeEmin' class="form-select">
                                <option value=''>Choisir +/-</option>
                                <option value="1" {!! $personne->eMin == 1 ? 'selected' : ''!!} >+</option>
                                <option value="0" {!! $personne->eMin == 0 ? 'selected' : ''!!} >-</option>
                            </select>

                            @if ($errors->has('phenotypeEmin'))
                            <small class="text text-danger">
                                {{ $errors->first('phenotypeEmin') }}
                            </small>
                            @endif
                        </div>
                        <br>
                        <div class="form-group {{ $errors->has('phenotypeKell') ? 'has-error' : '' }} ">
                            <label for="phenotypeKell" class="form-label text-center">Kell</label>
                            <select id='phenotypeKell' name='phenotypeKell' class="form-select">
                                <option value=''>Choisir +/-</option>
                                <option value="1" {!! $personne->kell == 1 ? 'selected' : ''!!} >+</option>
                                <option value="0" {!! $personne->kell == 0 ? 'selected' : ''!!} >-</option>
                            </select>

                            @if ($errors->has('phenotypeKell'))
                            <small class="text text-danger">
                                {{ $errors->first('phenotypeKell') }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        Compte Membre
                    </div>
                    <div class="card-body">

                        <div class="form-group ">
                            <label for="type_personne" class="form-label">Type de personne</label>
                            <select id="type_personne" name="type_personne" class="form-select">

                                <option value="user" {!! $personne->typePersonne=="user" ? 'selected' : ''
                                    !!}>Utilisateur</option>
                                <option value="admin" {!! $personne->typePersonne=="admin" ? 'selected' : ''
                                    !!}>Administrateur</option>
                                <option value="personnelMedical" {!! $personne->typePersonne=="personnelMedical" ?
                                    'selected' : '' !!}>Personnel Médical</option>

                            </select>
                            @if ($errors->has('type_personne'))
                            <small class="text text-danger">
                                {{ $errors->first('type_personne') }}
                            </small>
                            @endif
                        </div>
                        <br>
                        <!-- Nouveau champ Mot de passe -->
                        <div class="form-group">
                            <label for="motDePasse" class="form-label">Mot de passe</label>
                            <input type="password" id="motDePasse" name="motDePasse"
                                class="form-control @error('motDePasse') is-invalid @enderror" required>

                            @error('motDePasse')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <!-- Confirmation du mot de passe -->
                        <div class="form-group">
                            <label for="motDePasse_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" id="motDePasse_confirmation" name="motDePasse_confirmation"
                                class="form-control @error('motDePasse_confirmation') is-invalid @enderror" required>

                            @error('motDePasse_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="form-group col mb-3" id="idCentre-container">
                            <label for="idCentre" class="form-label">Centre</label>
                            <select id="idCentre" name="idCentre"
                                class="form-select @error('idCentre') is-invalid @enderror">
                                <option value="">Sélectionner un centre</option>
                                @foreach ($listeCentres as $centre)
                                <option value="{{ $centre->id }}" {{ old('idCentre', $sousPersonne->idCentre) ==
                                    $centre->id ? 'selected' : '' }}>
                                    {{ $centre->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('idCentre')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Champs dynamiques -->
                        <div class="form-group" id="pseudo_field" style="display: none;">
                            <br>
                            <label for="pseudo" class="form-label">Pseudo</label>
                            <input type="text" id="pseudo" name="pseudo" class="form-control"
                                value="{{ old('pseudo', $sousPersonne->pseudo ?? '') }}">
                        </div>

                        <div class="form-group" id="acces_field" style="display: none;">
                            <br>
                            <label for="acces" class="form-label">Accès</label>
                            <select id="acces" name="acces" class="form-select">
                                <option value="superviser" {{ old('acces', $sousPersonne->role) == 'superviser' ?
                                    'selected' : '' }}>Superviser</option>
                                <option value="autoriser" {{ old('acces', $sousPersonne->role) == 'autoriser' ?
                                    'selected' : '' }}>Autoriser</option>
                            </select>
                        </div>

                        <div class="form-group" id="accesadmin_field" style="display: none;">
                            <br>
                            <label for="accesadmin" class="form-label">Accès admin</label>
                            <select id="accesadmin" name="accesadmin" class="form-select">
                                <option value="admin" {{ old('accesadmin', $sousPersonne->acces) == 'admin' ? 'selected'
                                    : '' }}>Admin</option>
                                <option value="sousadmin" {{ old('accesadmin', $sousPersonne->acces) == 'sousadmin' ?
                                    'selected' : '' }}>Sous-admin</option>
                            </select>
                        </div>

                        <div class="form-group" id="fonction_field" style="display: none;">
                            <br>
                            <label for="fonction" class="form-label">Fonction</label>
                            <select id="fonction" name="fonction" class="form-select">
                                <option value="medecin" {{ old('fonction', $sousPersonne->fonction) == 'medecin' ?
                                    'selected' : '' }}>Médecin</option>
                                <option value="infirmier" {{ old('fonction', $sousPersonne->fonction) == 'infirmier' ?
                                    'selected' : '' }}>Infirmier</option>
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


    function loadCivilites(selectedCivilite,   targetSelect) {


        if (selectedCivilite=="0") {

            document.getElementById("epousede_field").value = "epouseField";

        }
    }
    function loadCommunes(wilaya_id, selectedCommune, targetSelect) {
        if (wilaya_id) {
            var wilaya_code = wilaya_id.toString().padStart(2, '0');

            $.ajax({
                url: '/api/communes/' + wilaya_code,
                type: 'GET',
                success: function(data) {
                    $(targetSelect).html('<option value="">Sélectionner une commune</option>');

                    $.each(data, function(index, commune) {

                        var option = $('<option></option>')
                            .attr('value', commune.id)
                            .text(commune.commune_name);

                        if (commune.id == selectedCommune) {

                            option.attr('selected', 'selected');
                        }

                        $(targetSelect).append(option);
                    });
                }
            });
        }
    }
    // Chargement des communes au démarrage si une wilaya est déjà sélectionnée
    if (selectedWilayaProfessionnelle) {

        loadCommunes(selectedWilayaProfessionnelle, selectedCommuneProfessionnelle, '#communeProfessionnelle');
    }
    if (selectedCivilite) {

      loadCivilites(selectedCivilite,  '#epousede_field');
  }
  // Chargement des communes au démarrage si une wilaya est déjà sélectionnée
  if (selectedWilayaDomicile) {

      loadCommunes(selectedWilayaDomicile, selectedCommuneDomicile, '#communeDomicile');
  }


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

});

    $(document).ready(function () {
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

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('confirm-update').addEventListener('click', function () {
            let form = document.getElementById('form' );
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

    function toggleEpouseDe() {
        var gender = document.getElementById("gender").value;
        var epouseDeField = document.getElementById("epousede_field");

        if (gender === "0") {
            epouseDeField.style.display = "block";
        } else {
            epouseDeField.style.display = "none";
            document.getElementById("epousede").value = ""; // Vider le champ si "Homme" est sélectionné
        }
    }

    function resetForm() {
        document.getElementById("gender").value = ""; // Réinitialise gender
        document.getElementById("epousede").value = ""; // Vide épouseDe
        toggleEpouseDe(); // Cache le champ épouseDe
    }

    function toggleEpouseDe() {
        let gender = document.getElementById("gender").value;
        let epouseField = document.getElementById("epousede_field");
  // Chargement des communes au démarrage si une wilaya est déjà sélectionnée


        if (gender === "0") { // Si "Femme" est sélectionné
            epouseField.style.display = "block";

        } else {
            epouseField.style.display = "none";
             // Efface la valeur si caché
        }
    }


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
