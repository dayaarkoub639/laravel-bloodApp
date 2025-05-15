@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')


<div class="dashboard_content bg-light-4 container-fluid">



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

            <!--ICi recherche-->
    <div class="d-flex align-items-stretch">



    <div class="card w-100 container">
      <div class="card-body p-4 row">
              <h1>  Statistiques   </h1>
 
              <div class="container my-4">
  

    <div class="row text-center mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card text-center shadow-sm p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="ti ti-user text-danger" style=" font-size: 3rem !important;"></i>
                    </div>
                    <h3 class="fw-bold">{{$nbrUtilisateurs}}</h3>
                    <p class="mb-0 fw-semibold">Utilisateurs enregistrés</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center shadow-sm p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="ti ti-droplet text-danger" style=" font-size: 3rem !important;"></i>
                    </div>
                    <h3 class="fw-bold">{{$nbrDonneurs}}</h3>
                    <p class="mb-0 fw-semibold">Donneurs enregistrés</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center shadow-sm p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="ti ti-list text-danger" style=" font-size: 3rem !important;"></i>
                    </div>
                    <h3 class="fw-bold">{{$nbrUtilisateurs}}</h3>
                    <p class="mb-0 fw-semibold">Demandes de don</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center shadow-sm p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="ti ti-heartbeat text-danger" style=" font-size: 3rem !important;"></i>
                    </div>
                    <h3 class="fw-bold">{{$nbrDons}}</h3>
                    <p class="mb-0 fw-semibold">Dons complétés</p>
                </div>
            </div>
        </div>
   
    </div>

  
    <div class="row">
            <div class="col-sm-6 col-md-4 mb-4">
            
                       

                        <div class="card shadow-sm">
                            <div class="card-header ">
                            <h5>  Donneurs par groupe</h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0 table-striped text-center">
                                    <tbody>
                                     
                                        @foreach($groupes as $groupe)
                                            <tr>
                                                <td>{{ $groupe->type }}</td>
                                                <td>{{ $groupe->personnes_count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                
            </div>
            <div class="col-sm-6 col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">Statistiques géographiques par année</div>
                    <div class="card-body"> 
                    <canvas id="statChart" width="600" height="300"></canvas>
                    </div>
                </div>
            </div>
        
        </div>

                <h4 class="my-4">Demandes et utilisateurs</h4>
                <div class="row">
                    <div class="col  mb-4">
                        <canvas id="donChart" width="100%" ></canvas> 
                    </div>   
                </div> 
            </div>
        </div>
        </div>
    </div>

    </div>


      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
     
        <script>
        const ctx = document.getElementById('donChart').getContext('2d');

        const donChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [
                    {
                        label: 'Série 1',
                        data: [20, 27, 24, 35, 36, 30, 33, 29, 25, 28, 34, 32],
                        borderColor: 'cyan',
                        backgroundColor: 'cyan',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Série 2',
                        data: [12, 20, 20, 30, 30, 28, 31, 27, 23, 26, 30, 29],
                        borderColor: '#36a2eb',
                        backgroundColor: '#36a2eb',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 50
                    }
                }
            }
        });
   
        const ctx2 = document.getElementById('statChart').getContext('2d');

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                    'Utilisateur enregistrés',
                    'Donneurs enregistrés',
                    'Demandes de don',
                    'Dons complétés'
                ],
                datasets: [
                    {
                        label: 'Année 2023',
                        data: [3, 7, 15, 20],
                        backgroundColor: '#4bc0c0'
                    },
                    {
                        label: 'Année 2024',
                        data: [6, 9, 17, 24],
                        backgroundColor: '#36a2eb'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 25
                    }
                }
            }
        });
    </script>
@endsection
