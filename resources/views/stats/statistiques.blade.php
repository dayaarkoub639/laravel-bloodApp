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
                        <i class="ti ti-heartbeat text-danger" style=" font-size: 3rem !important;"></i>
                    </div>
                    <h3 class="fw-bold">5,634</h3>
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
                    <h3 class="fw-bold">5,634</h3>
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
                    <h3 class="fw-bold">5,634</h3>
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
                    <h3 class="fw-bold">5,634</h3>
                    <p class="mb-0 fw-semibold">Dons complétés</p>
                </div>
            </div>
        </div>
   
    </div>

  
    <div class="row">
            <div class="col-sm-6 col-md-4 mb-4">
            
                        @php
                            $groupes = [
                                ['type' => 'A+', 'nombre' => '1,380'],
                                ['type' => 'B-', 'nombre' => '640'],
                                ['type' => 'B+', 'nombre' => '1,420'],
                                ['type' => 'AB+', 'nombre' => '780'],
                                ['type' => 'AB-', 'nombre' => '280'],
                                ['type' => 'O+', 'nombre' => '1,620'],
                            ];
                        @endphp

                        <div class="card shadow-sm">
                            <div class="card-header ">
                            <h5>  Donneurs par groupe</h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0 table-striped text-center">
                                    <tbody>
                                        @foreach ($groupes as $groupe)
                                            <tr>
                                                <td>{{ $groupe['type'] }}</td>
                                                <td>{{ $groupe['nombre'] }}</td>
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
                    <div class="card-body"> </div>
                </div>
            </div>
        
        </div>

                <h4 class="my-4">Demandes et utilisateurs</h4>
                <div class="row">
                    <div class="col  mb-4">
                        <canvas id="myBarChart" width="100%" ></canvas> 
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
    // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

 
// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin","Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"],
    datasets: [{
      label: "Revenue",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: [4215, 5312, 6251, 7841, 9821, 14984,5312,7141,4915,18987,2312,8841],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 12
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 15000,
          maxTicksLimit: 5
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});

 

</script>
@endsection
