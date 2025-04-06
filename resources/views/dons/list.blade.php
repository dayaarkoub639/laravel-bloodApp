@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')
 <!-- Leaflet CSS -->
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; }
    </style>
<div class="container-fluid">
  <p class="card-title fw-semibold mb-4" >
          Liste des dons
           
  </p>
  <div id="map"></div><br>
  <div class="card"></div>
  <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle" id="order-listing" >
        
      
            <thead class="text-dark fs-4">
              <tr>
         
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Nom & Prénom  </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Localisation </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Groupage </h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 small">Dons</h6>
                </th>
                 
               
              
              
              </tr>
            </thead>
            <tbody>
              @foreach ($donneurs as $donneur) 
                <tr>
                  
                    <td class="border-bottom-0">
                      <h6 class="fw-normal">
                    {{ $donneur->nom }}  {{ $donneur->prenom }} 

                      </h6>
                      </td>
                      <td class="border-bottom-0">
                      
                        <p>LAN: {{ $donneur->longitude }}  </p>
                        <p>LAT : {{ $donneur->latitude }}</p>
                  
                   
                      </td>
                      <td class="border-bottom-0">
                      <span class="badge bg-primary rounded-3 fw-semibold"> 
                          @if($donneur->groupage)
                                {{ $donneur->groupage->type}} 
                            @else
                              N/A
                            @endif  
                           </span>
                      </td>
                      <td class="border-bottom-0">
                      <ul>
                            @foreach ($donneur->dons as $don)
                                <li>
                                    <strong>Date :</strong> {{ \Carbon\Carbon::parse($don->date)->format('d/m/Y') }} <br>
                                    <strong>Lieu :</strong> {{ $don->lieuDon }} <br>
                                    <strong>Flacon :</strong> {{ $don->numeroFlacon }}
                                </li>
                            @endforeach
                        </ul>
                      
                  </td>
                   
                       
              </tr> 
            @endforeach                   
            </tbody>
          </table>
        </div>

        </div>







    




    </div> 


    <!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([36.75, 3.06], 7); // Centre sur Algérie

    // Ajouter une carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
  // Définir une icône rouge personnalisée
  var redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/images/marker-shadow.png',
        iconSize: [25, 41], // Taille du marqueur
        iconAnchor: [12, 41], // Position de l'ancre
        popupAnchor: [1, -34], // Position du popup
        shadowSize: [41, 41] // Taille de l'ombre
    });
    // Ajouter les marqueurs des personnes
    var donneurs = @json($donneurs);

    donneurs.forEach(personne => {
        L.marker([personne.latitude, personne.longitude], { icon: redIcon })
            .addTo(map)
            .bindPopup(`<b>${personne.prenom} ${personne.nom}</b><br>Groupe Sanguin: ${personne.idGroupage}`);
    });
</script>
@endsection
 