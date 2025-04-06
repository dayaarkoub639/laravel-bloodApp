@extends('layouts.public.master')
@section('content')

<div class="dashboard_content bg-light-4 container-fluid pb-5">

    <form class="row g-3 justify-content-end">
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3 py-2 px-4"><img width="25" height="25" src="https://img.icons8.com/ios/f8f9fa/25/plus-math--v1.png" alt="plus-math--v1"/>
                Ajouter</button>
        </div>
    </form> 

   <div class="membreform">
    <form class="row g-3">
        
        <div class="col-md-6">
          <label for="inputEmail4" class="form-label">Nom</label>
          <input type="email" class="form-control" id="inputEmail4">
        </div>
        <div class="col-md-6">
          <label for="inputPassword4" class="form-label">Prénom</label>
          <input type="password" class="form-control" id="inputPassword4">
        </div>
        <div class="col-4">
          <label for="inputAddress" class="form-label">Date de naissance</label>
          <input type="date" class="form-control" id="inputAddress" placeholder="1234 Main St">
        </div>
        <div class="col-md-6 col-lg-4">
            <label for="inputEmail4" class="form-label">Wilaya</label>
            <select id="inputState" class="form-select">
                <option selected>...</option>
                <option>...</option>
              </select>
          </div>
          <div class="col-md-6 col-lg-4">
            <label for="inputPassword4" class="form-label">Commune</label>
            <select id="inputState" class="form-select">
                <option selected>...</option>
                <option>...</option>
              </select>
          </div>
        <div class="col-12">
          <label for="inputAddress2" class="form-label">Addresse</label>
          <input type="text" class="form-control" id="inputAddress2" placeholder="Cité...">
        </div>
        <div class="col-md-6">
          <label for="inputCity" class="form-label">Téléphone</label>
          <input type="number" class="form-control" id="inputCity">
        </div>
        <div class="col-md-4 col-lg-6">
          <label for="inputState" class="form-label">groupage</label>
          <select id="inputState" class="form-select">
            <option selected>A+...</option>
            <option>...</option>
          </select>
        </div>
        <div class="col-2">
            <label for="inputState" class="form-label">phénotype C</label>
            <input type="text" class="form-control" id="inputAddress2" placeholder="+/-">
        </div>
        <div class="col-2">
            <label for="inputState" class="form-label">phénotype E</label>
            <input type="text" class="form-control" id="inputAddress2" placeholder="+/-">
        </div>
        <div class="col-2">
            <label for="inputState" class="form-label">phénotype c</label>
            <input type="text" class="form-control" id="inputAddress2" placeholder="+/-">
        </div>
        <div class="col-3">
            <label for="inputState" class="form-label">phénotype e</label>
            <input type="text" class="form-control" id="inputAddress2" placeholder="+/-">  
        </div>
        <div class="col-md-2 col-lg-3">
            <label for="inputZip" class="form-label">Kell</label>
            <input type="text" class="form-control" id="inputZip" placeholder="+/-">
          </div>
        <div class="col-md-3">
          <label for="inputZip" class="form-label">Poids</label>
          <input type="number" class="form-control" id="inputZip">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Remarque</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
          </div>
       
        <form class="row g-3" method="GET" action="{{ route('newmembre') }}">
            @csrf
            <div class="col-auto">
                <button type="submit" class="btn btn-success mb-3 py-2 px-4">
                    Enregistrer</button>
            </div>
        </form> 
        
      </form>
   </div>
    

        
             
</div>

@endsection