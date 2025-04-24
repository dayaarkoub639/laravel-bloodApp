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
 
   <div class="container py-4">
    <h4 class="mb-4 text-danger d-flex align-items-center">
        <i class="bi bi-droplet me-2"></i> Demandes acceptées ({{count($donneurs->personnes)}}) 
    </h4>



    @foreach ($donneurs->personnes as $donneur)
        <div class="card shadow-sm mb-3 rounded-4 px-3 py-2 d-flex align-items-center flex-row">
            <img src="{{ $donneur['genre'] == 'femme' ? asset('assets/images/profile/user-1.jpg') : asset('assets/images/profile/user-1.jpg') }}"
                 class="rounded-circle me-3" alt="Avatar" style="width: 50px; height: 50px;">

            <div class="flex-grow-1">
                <h6 class="mb-1 fw-bold"> 
                @if($donneur->user)
                {{ $donneur->user->pseudo  }}
                @else
                              N/A
                            @endif 
                </h6>

                <!-- Étoiles -->
                @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $donneur->user()->value('noteEtoile'))
                                <span style="color: gold; font-size: 20px;">&#9733;</span>   
                            @else
                                <span style="color: lightgray; font-size: 20px;">&#9733;</span>  
                            @endif
                        @endfor

                <!-- Ville -->
                <small class="text-muted">
                    <i class="bi bi-geo-alt-fill text-danger"></i> 
                    <p class="fw-normal"> 

@if($donneur->communeDomicile)
        {{ $donneur->communeDomicile->wilaya_name }} 
    @else
      Aucune wilaya associée 
    @endif  
   
 -
@if($donneur->communeDomicile)
        {{ $donneur->communeDomicile->commune_name }} 
    @else
      Aucune commune associée 
    @endif  
</p>  
                </small>
                <p> <a href="{{ url('membres/fiche/'.$donneur->user->keyIdUser) }}"> Voir la fiche #{{ $donneur->user->keyIdUser }}</a></p>

            </div>

            <!-- Groupe sanguin -->
            <span class="badge rounded-pill border border-danger text-danger px-3 py-2 fs-6">
                
                @if($donneur->groupage)
                                {{ $donneur->groupage->type}} 
                            @else
                              N/A
                            @endif  
            </span>
        </div>
    @endforeach
</div>

                </div>
              </div>
            </div>
                    
      </div> 
 

@endsection