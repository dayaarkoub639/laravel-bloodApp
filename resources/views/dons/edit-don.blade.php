@extends('layouts.public.master')
@section('content')
@include('layouts.public.header')

<div class="container-fluid">
    <p class="card-title fw-semibold mb-4">
        Modifier le don <a href="{{ url('membres/fiche/'.$don->idDonneur) }}"> #{{ $don->idDonneur }}</a>
    </p>

    <div class="prelevement" id="prelevement">
        <form class="row g-3" style="padding-top: 10px" method="POST" action="{{ route('dons.update', $don->idDon) }}" id="form">
            @csrf
            @method('PUT') 

            <div class="col">
                <label class="form-label">Date</label>
                <input type="datetime-local" class="form-control" name="date" value="{{ old('date', $don->date) }}">
                @error('date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!--<div class="col">
                <label class="form-label">Lieu du don</label>
                <input type="text" class="form-control" name="lieuDon" value="{{ old('lieuDon', $don->lieuDon) }}">
                @error('lieuDon')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>-->

            <div class="col-md-6 col-lg-3">
                <label class="form-label">Poids</label>
                <input type="text" class="form-control" name="Pds" placeholder="60 Kg" value="{{ old('Pds', $don->Pds) }}">
                @error('Pds')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6 col-lg-3">
                <label class="form-label">N° flacon</label>
               
                <input type="text" class="form-control" name="numeroFlacon" placeholder="N° flacon" value="{{ old('numeroFlacon', $don->numeroFlacon) }}" >
              @if ($errors->has('numeroFlacon'))
            <small class="text text-danger">
                {{ $errors->first('numeroFlacon') }}
            </small>
          @endif
            </div>

            <div class="col">
                <label class="form-label">C C</label>
                <input type="text" class="form-control" name="C" value="{{ old('C', $don->C) }}">
                @error('C')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">T A</label>
                <input type="text" class="form-control" name="TA" value="{{ old('TA', $don->TA) }}">
                @error('TA')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-4 col-lg-3">
                <label class="form-label">Sérologie</label>
                <select class="form-select" name="serologie">
                    <option value="0" {{ old('serologie', $don->serologie) == 0 ? 'selected' : '' }}>Négative</option>
                    <option value="1" {{ old('serologie', $don->serologie) == 1 ? 'selected' : '' }}>Positive</option>
                </select>
                @error('serologie')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-4 col-lg-3">
                <label class="form-label">Support</label>
                <select name="support" class="form-select">
                    <option value="double" {{ old('support', $don->support) == 'double' ? 'selected' : '' }}>Double</option>
                    <option value="triple" {{ old('support', $don->support) == 'triple' ? 'selected' : '' }}>Triple</option>
                    <option value="quadruple" {{ old('support', $don->support) == 'quadruple' ? 'selected' : '' }}>Quadruple</option>
                </select>
                @error('support')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Réactions</label>
                <textarea class="form-control" name="reactions" rows="3" placeholder="Perte de connaissance, Malaise, Syncope, Convulsions ou autres .... ?">{{ old('reactions', $don->reactions) }}</textarea>
                @error('reactions')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Observations</label>
                <textarea class="form-control" name="observations" rows="3">{{ old('observations', $don->obsMedicale) }}</textarea>
                @error('observations')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Autoriser *</label>
                <select name="persMedicale" class="form-select" name="persMedicale">
                    <option value="">Sélectionner</option>
                    @foreach ($listePersonneMedicale as $persMedicale)
                        <option value="{{ $persMedicale->id }}" {{  $don->idPersonneMedicale	  == $persMedicale->id ? 'selected' : '' }}>
                            {{ $persMedicale->personnee->nom }} {{ $persMedicale->personnee->prenom }} ({{ $persMedicale->fonction }})
                        </option>
                    @endforeach
                </select>
                @error('persMedicale')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12">
    <label class="form-label">Superviser *</label>
    <select name="persMedicaleSuperviser" class="form-select">
        <option value="">Sélectionner</option>
        @foreach ($listePersonneMedicale as $persMedicale)
            <option value="{{ $persMedicale->id }}" 
                {{ $don->idPersonneMedicaleSuperviser == $persMedicale->id ? 'selected' : '' }}>
                {{ $persMedicale->personnee->nom }} {{ $persMedicale->personnee->prenom }} ({{ $persMedicale->fonction }})
            </option>
        @endforeach
    </select>
    @error('persMedicaleSuperviser')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


            <div class="col-auto enregistrerbtn">
 
    <button type="button" class="btn btn-outline-primary" onclick="window.history.back();">Annuler</button>

<button type="button" class="mx-3 btn btn-primary" id="confirm-edit">  Modifier  </button>
</div>

        </form>
    </div>
</div>
 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('confirm-edit').addEventListener('click', function () {
            let form = document.getElementById('form' );
            Swal.fire({
                title: "Confirmer la modification ?",
                text: "Voulez-vous vraiment modifier cet élément ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Oui, modifier",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();// Soumet le formulaire
                }
            });
        });
    });
</script>

@endsection
