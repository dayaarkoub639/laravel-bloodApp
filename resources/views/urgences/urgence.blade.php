@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%;z-index: 0; }
    </style>
    <div class="dashboard_content bg-light-4 container-fluid">
        
            <form class="row g-3"  action="{{ route('dons.search') }}" method="GET">    
            @csrf            
                <div class="col-auto">
                <select id='groupage' name='idGroupage' class="form-select" required>
                <option value=''>Choisir le groupage</option>
                @foreach ($listeGroupage as $key => $value)
                <option value='{!! $value->id !!}'>{!! $value->type !!}</option>
                @endforeach

            </select> 
      </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary mb-3 py-2">Chercher donneurs</button>
                </div>
              </form>   
              <div id="map"></div>
             <!-- <div class="map container-fluid">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3208.0678572157017!2d2.8054148999999993!3d36.480077099999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128f0d049d1732e9%3A0xbc15d0149d816fed!2sBoite%20de%20communication%20TAHAR%20Tech!5e0!3m2!1sen!2sdz!4v1737385673524!5m2!1sen!2sdz"
                  style="width: 100%; height: 450px; border: 0;" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>-->


                <div class="bigcard container-fluid my-1">                  
                    <div class=" mb-4">                       
                       <div class="d-lg-flex justify-content-center row">
                      
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
                                            <td>{{ $donneur->idUser}}</td>
                                            <td>2 Km</td>
                                            <td>{{ $donneur->numeroTlp1}}</td>
                                            <td><img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>
                                            @if($donneur->groupage)
                                                    {{ $donneur->groupage->type}} 
                                                @else
                                                N/A
                                                @endif  </td>
                                            <td>20 min</td>                                           
                                        </tr>                                 
                                       
                                        @endforeach   
                                    </tbody>
                                </table>
                            </div>
                       

                     
                            <div class="card card-body p-4 col-sm-12 col-lg-2 d-flex align-items-center">
                                <table id="datatablesSimple">                                  
                                    <tbody>
                                        <tr>
                                        <td><p class="fw-semibold fs-4 text-center mb-0">{{ count($donneurs)}}</p></td>
                                                                                   
                                        </tr>                                 
                                     
                                        <tr>
                                            <td class="text-center pb-4">Donneurs</td>
                                             
                                        </tr>
                                        <tr>
                                            <td><p class="fw-semibold fs-4 text-center mb-0">{{ $totalDemandes}}</p></td>
                                           
                                           
                                        </tr>
                                   
                                     
                                        <tr>
                                            <td class="text-center">Demandes</td>
                                         
                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                      

                       
                            <div class="card card-body p-4  col-sm-12 col-lg-3">
                                <table id="datatablesSimple d-lg-flex align-items-center justify-content-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Des notifications seront 
                                                envoyées aux donneurs 
                                                pour les informer de 
                                                l'urgence </th>
                                            
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form class="row g-3 d-lg-flex align-items-center justify-content-center">                
                                                <div class="col-auto">
                                                  <button type="submit" class="btn btn-light mb-3 py-2">Annuler</button>
                                                </div>
                                                <div class="col-auto">
                                                  <a href="{{route('ajouter-demande')}}" class="btn btn-primary mb-3 py-2">Envoyer la demande</a>
                                                </div>
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
              
                     
    </div>
       <!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([36.75, 3.06], 8); // Centre sur Algérie

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