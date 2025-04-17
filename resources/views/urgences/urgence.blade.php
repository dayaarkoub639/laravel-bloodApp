@extends('layouts.public.master')

@section('content')
@include('layouts.public.header')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    #map { height: 500px; width: 100%; z-index: 0; }
</style>

<div class="dashboard_content bg-light-4 container-fluid">
    <!-- Formulaire -->
    <form class="row g-3" action="{{ route('dons.search') }}" method="GET">    
        @csrf            
        <div class="col-auto">
            <label class="form-label">Choisir le groupage</label>
            <select id='groupage' name='idGroupage' class="form-select" required>
                <option value=''>Choisir le groupage</option>
                @foreach ($listeGroupage as $key => $value)
                    <option value='{!! $value->id !!}'>{!! $value->type !!}</option>
                @endforeach
            </select> 
        </div>
        <div class="col-auto">
            <label class="form-label">Nombre Donneurs à rechercher</label>
            <input type="number" class="form-control" name="nbreDonneursDemande" placeholder="Nombre Donneurs à rechercher" value="3">
        </div>
        <div class="col-auto">
            <br> 
            <button type="submit" class="btn btn-primary mb-3 mt-2">Chercher donneurs</button>
        </div>
    </form>   

    <br>
    <!-- Affichage des messages de succès et erreur -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <br>
    <div id="map"></div> 
 
    <div class="bigcard container-fluid my-1">
        <div class="mb-4">
            <div class="d-lg-flex justify-content-center row">
                <!-- Donneurs Table -->
                <div class="card card-body p-4 col-sm-12 col-lg-6">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Distance</th>
                                <th>Tel</th>
                                <th>Groupage</th>
                                <th>Temps</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donneurs as $donneur)
                                <tr>
                                    <td>{{ $donneur->idUser }}</td>
                                    <td>{{ $donneur->distanceKm }} Km</td>
                                    <td>{{ $donneur->numeroTlp1 }}</td>
                                    <td>
                                        <img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>
                                        @if($donneur->groupage)
                                            {{ $donneur->groupage->type}} 
                                        @else
                                            N/A
                                        @endif  
                                    </td>
                                    <td>{{ $donneur->tempsEstime }} min</td>                                           
                                </tr>                                 
                            @endforeach   
                        </tbody>
                    </table>
                </div>

                <!-- Notifications Table -->
                <div class="card card-body p-4 col-sm-12 col-lg-3">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th class="text-center">Des notifications seront envoyées aux donneurs pour les informer de l'urgence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><br>
                                    <form class="row g-3" style="padding-top: 10px" method="POST" action="{{ route('urgences.envoyerDemandes') }}">
                                        @csrf
                                        <input type="hidden" name="donneursList" value="{{ json_encode($donneurs) }}">
                                        <button type="submit" class="btn btn-primary mb-3 py-2">Envoyer la demande</button>
                                    </form>
                                </td>                                 
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    // Point A (fixe, exemple : centre d'urgence ou autre endroit fixe)
    const pointA = [{{ $pointA['lat'] }}, {{ $pointA['lon'] }}];
    const nomCentre = @json($nomCentre);
    // Créer la carte
    const map = L.map('map').setView(pointA, 7);

    // Ajouter la couche OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Icône personnalisée pour les marqueurs
    var redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/images/marker-shadow.png',
        iconSize: [25, 41], 
        iconAnchor: [12, 41], 
        popupAnchor: [1, -34], 
        shadowSize: [41, 41]
    });

    // Ajout du marqueur pour le point A
    L.marker(pointA).addTo(map)
        .bindPopup(nomCentre);

    // Liste des donneurs (injectée par Laravel)
    const donneurs = @json($donneurs);

    // Tracer les itinéraires entre Point A et chaque donneur
    donneurs.forEach(personne => {
        const donneurCoord = [personne.latitude, personne.longitude];

        // Ajouter un marqueur pour chaque donneur
        L.marker(donneurCoord, { icon: redIcon })
            .addTo(map)
            .bindPopup(`<b>${personne.prenom} ${personne.nom}</b><br>Date de naissance: ${personne.dateDeNess}`);

        // URL pour calculer l'itinéraire entre Point A et le donneur
        const url = `https://router.project-osrm.org/route/v1/driving/${pointA[1]},${pointA[0]};${donneurCoord[1]},${donneurCoord[0]}?overview=full&geometries=geojson`;

        // Requête pour obtenir l'itinéraire
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const route = L.geoJSON(data.routes[0].geometry, {
                    style: {
                        color: 'blue',
                        weight: 3,
                        opacity: 0.7
                    }
                }).addTo(map);
            })
            .catch(error => {
                console.error('Erreur lors de la récupération de l’itinéraire :', error);
            });
    });
</script>

@endsection
