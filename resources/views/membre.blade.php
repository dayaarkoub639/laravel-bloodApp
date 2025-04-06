@extends('layouts.public.master')
@section('content')

<style>
  /* Styles pour l'effet d'opacité */
  .overlay, .overlay2 {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.5s ease, visibility 0.5s ease;
  }

  .overlay.active {
      opacity: 1;
      visibility: visible;
  }

  .membreform {
      background: white;
      position: fixed;
      top: 50%;
      left: 55%;
      transform: translate(-50%, -50%) scale(0.9);
      z-index: 1000000;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 70%;
      max-height: 90vh;
      overflow-y: auto;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s ease;
  }

  .membreform.active {
      transform: translate(-50%, -50%) scale(1);
      opacity: 1;
      visibility: visible;
  }

  .close-btn {
      float: right;
      cursor: pointer;
      color: red;
      font-size: 20px;
      padding-left: 4px;
      padding-right: 4px;
  }
  .close-btn:hover {
      background-color: #fae0de;
      border-radius: 5px;
  }

  .enregsitrerbtn {
      margin-left: auto; 
  }

  .fiche {
      display: none; /* Cache la fiche par défaut */
      position: fixed; /* Fixe la fiche à la fenêtre */
      top: 50px; /* Positionne la fiche en haut de la page */
      left: 25%; /* Centre horizontalement */
     
      width: 60%; /* Largeur de la fiche */
      max-height: 90vh; /* Hauteur maximale */
      overflow-y: auto; /* Ajoute un défilement si nécessaire */
      background: white; /* Fond blanc */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre */
      z-index: 1000000; /* Assure que la fiche est au-dessus des autres éléments */
      padding: 20px; /* Espacement interne */
      border-radius: 8px; /* Coins arrondis */
  }



  .close-fiche-btn {
      float: right;
      cursor: pointer;
      color: red;
      font-size: 20px;
      padding-left: 4px;
      padding-right: 4px;
  }
  .close-fiche-btn:hover {
      background-color: #fae0de;
      border-radius: 5px;
  }

#search-container {
  margin: 1em 0;
}
#search-container input {
  background-color: transparent;
  width: 30%;
  padding: 0.8em 0.2em;
  border-color: transparent;
  border-bottom: 2px solid #777373;
  margin-left: 10px

}
#search-container input:focus {
  border-bottom-color: #de2e23;
}
#search-container button {
  padding: 0.6em 1.3em;
  margin-left: 0.8em;
  background-color: #de2e23;
  color: #ffffff;
  border-radius: 15px;
  margin-top: 0.5em;
  border-color: transparent;
}
#search-container button:hover{
  background-color: #ec3326;
}
.button-value {
  border: 2px solid #de2e23;
  padding: 0.4em 0.8em;
  border-radius: 1em;
  background-color: transparent;
  color: #de2e23;
  cursor: pointer;
}

.selected {
    background-color: #de2e23 !important;
    color: white !important;
    outline: none !important;
    border: none !important;
}
.cherchemembre{
  font-size: 14px;
}
.searchbtn{
  font-size: 14px;

}

.prelevement {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1000;
        }

  .fiche{
    border: solid 2px var(--red-color);
    border-radius: 10px;
    z-index: 200000;
  }
  
  .labelfiche{
    font-weight: bold;
  
  }
  .form3{
    border: solid 3px gray;
    border-radius: 0%;
    height: 230px;
    padding-top: 0;
    background-color: #fff;
  }

</style>

<div class="dashboard_content bg-light-4 container-fluid">
    <form class="row g-3 justify-content-end">
        <div class="col-auto">
            <button type="button" class="btn btn-primary mb-3 py-2 px-4" id="openFormBtn">
                <img width="25" height="25" src="https://img.icons8.com/ios/f8f9fa/25/plus-math--v1.png" alt="plus-math--v1"/>
                Ajouter
            </button>
        </div>
    </form> 

   <div class="pb-5 d-flex flex-column align-items-center justify-content-evenly">
    <div id="search-container" class="d-flex align-items-center justify-content-center">
     
      <input class="cherchemembre" style="width: 100%"
        type="search"
        id="search-input"
        placeholder="Chercher dans la liste..."/>
      <button class="searchbtn" id="search">Chercher</button>
    </div>
    <div id="buttons">
      
        <select id="inputState search-container" class="button-value">
        <option selected disabled>Groupage</option>
        <option>A+</option>
        <option>B+</option>
        <option>O+</option>
        <option>A-</option>
        <option>B-</option>
        <option>AB+</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option disabled selected>C</option>
        <option>C+</option>
        <option>C-</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option disabled selected>E</option>
        <option>E+</option>
        <option>E-</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option disabled selected>c</option>
        <option>c+</option>
        <option>c-</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option disabled selected>e</option>
        <option>e+</option>
        <option>e-</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option disabled selected>Kell</option>
        <option>Kell+</option>
        <option>Kell-</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option selected>Wilaya</option>
        <option>...</option>
      </select>

      <select id="inputState search-container" class="button-value">
        <option selected>Commune</option>
        <option>...</option>
      </select>
   
    </div>
   </div>

    <div class="row"></div>

    <div id="layoutSidenav_content">
      <main>
          <div class="container-fluid px-4">
              <div class="card mb-4">
                  <div class="card-header d-flex justify-content-between" style="color: #909090">
                      <div>
                          <img width="25" height="25" src="https://img.icons8.com/ios/25/list.png" alt="list"/>
                          Liste des membres
                      </div>
                      <a href="" style="color: #909090">
                          <div>                         
                              <li class="nav-item dropdown">
                                <a class="nav-link" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <!-- Image inside Dropdown (Align Right)-->
                                    <img width="20" height="20" src="https://img.icons8.com/ios/20/horizontal-settings-mixer--v1.png" alt="horizontal-settings-mixer--v1"/>
                                     Trier
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#!">Nom</a></li>
                                    <li><a class="dropdown-item" href="#!">Wilaya</a></li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li><a class="dropdown-item" href="#!">Date</a></li>
                                </ul>
                            </li>
                          </div>
                      </a>
                  </div>
                  <div class="card-body">
                      <table class="table table-striped">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Pseudo</th>
                              <th scope="col">Date de naissance</th>
                              <th scope="col">Commune</th>
                              <th scope="col">Groupage</th>
                              <th scope="col">Numéro de téléphone</th>
                              <th scope="col">Options</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">1</th>
                              <td>User123</td>
                              <td>15/03/1991</td>
                              <td>Boufarik</td>
                              <td>A+</td>
                              <td><p>0557896935</p><p>0557896935</p></td>
                              <td class="opt">
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/28A745/20/edit--v1.png" alt="edit--v1"/></a>
                                  <a href=""><img data-member-id="1" class="search-more ms-1" width="20" height="20" src="https://img.icons8.com/ios/F8C039/20/search-more.png" alt="search-more"/></a>
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                                  <a href="#"><img class="ms-1 plus-math" width="20" height="20" src="https://img.icons8.com/ios-glyphs/007BFF/30/plus-math.png" alt="plus-math"/></a>
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">2</th>
                              <td>User123</td>
                              <td>15/03/1991</td>
                              <td>Boufarik</td>
                              <td>A+</td>
                              <td><p>0557896935</p><p>0557896935</p></td>
                              <td class="opt">
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/28A745/20/edit--v1.png" alt="edit--v1"/></a>
                                  <a href=""><img data-member-id="2" class="search-more ms-1" width="20" height="20" src="https://img.icons8.com/ios/F8C039/20/search-more.png" alt="search-more"/></a>
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                                  <a href="#"><img class="ms-1 plus-math" width="20" height="20" src="https://img.icons8.com/ios-glyphs/007BFF/30/plus-math.png" alt="plus-math"/></a>
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">3</th>
                              <td>User123</td>
                              <td>15/03/1991</td>
                              <td>Boufarik</td>
                              <td>A+</td>
                              <td><p>0557896935</p><p>0557896935</p></td>
                              <td class="opt">
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/28A745/20/edit--v1.png" alt="edit--v1"/></a>
                                  <a href=""><img data-member-id="3" class="search-more ms-1" width="20" height="20" src="https://img.icons8.com/ios/F8C039/20/search-more.png" alt="search-more"/></a>
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                                  <a href="#"><img class="ms-1 plus-math" width="20" height="20" src="https://img.icons8.com/ios-glyphs/007BFF/30/plus-math.png" alt="plus-math"/></a>
                              </td>
                            </tr>
                          </tbody>
                      </table>
                      <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                              <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">1</a></li>
                          <li class="page-item"><a class="page-link" href="#">2</a></li>
                          <li class="page-item"><a class="page-link" href="#">3</a></li>
                          <li class="page-item"><a class="page-link" href="#">4</a></li>
                          <li class="page-item"><a class="page-link" href="#">5</a></li>
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                            </a>
                          </li>
                        </ul>
                      </nav>
                  </div>
              </div>

              <div class="overlay" id="overlay"></div>
              <div class="membreform" id="membreForm">
                  <span class="close-btn" id="closeFormBtn" style="padding-top: 0px">X</span>
                  <form class="row g-3" style="padding-top: 40px">
                      <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="inputEmail4">
                      </div>
                      <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="inputPassword4">
                      </div>
                      <div class="col-4">
                        <label for="inputAddress" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="inputAddress" placeholder="1234 Main St">
                      </div>
                      <div class="col-md-6 col-lg-4">
                          <label for="inputState" class="form-label">Wilaya de naissance</label>
                          <select id="inputState" class="form-select">
                              <option selected>...</option>
                              <option>...</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-4">
                          <label for="inputPassword4" class="form-label">Commune de naissance</label>
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
                          <div class="col-auto enregsitrerbtn">
                              <button type="submit" class="btn btn-success mb-3 py-2 px-4">
                                  Enregistrer</button>
                          </div>
                      </form> 
                    </form>
              </div>
              <div class="overlay2" id="overlay2"></div>
              <div class="prelevement" id="prelevement">
                  <span class="close-btn" id="closeFormBtnPrelevement" style="padding-top: 0px">X</span>
                  <form class="row g-3" style="padding-top: 10px">
                    <h4 class="text-center mb-4">Nouveau don</h4>
                      <div class="col-md-3">
                        <label for="inputEmail4" class="form-label">ID</label>
                        <input type="text" class="form-control" id="inputEmail4">
                      </div>
                      <div class="col-md-3">
                        <label for="inputPassword4" class="form-label">Date</label>
                        <input type="text" class="form-control" id="inputPassword4">
                      </div>
                      <div class="col-3">
                        <label for="inputAddress" class="form-label">Lieu don</label>
                        <input type="date" class="form-control" id="inputAddress" placeholder="1234 Main St">
                      </div>
                      <div class="col-md-6 col-lg-3">
                          <label for="inputState" class="form-label">Pds</label>
                          <select id="inputState" class="form-select">
                              <option selected>...</option>
                              <option>...</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-3">
                          <label for="inputPassword4" class="form-label">N° flacon</label>
                          <select id="inputState" class="form-select">
                              <option selected>...</option>
                              <option>...</option>
                            </select>
                        </div>
                      <div class="col-3">
                        <label for="inputAddress2" class="form-label">C C</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="Cité...">
                      </div>
                      <div class="col-md-3">
                        <label for="inputCity" class="form-label">T A</label>
                        <input type="number" class="form-control" id="inputCity">
                      </div>
                      <div class="col-md-4 col-lg-3">
                        <label for="inputState" class="form-label">Sérologie</label>
                        <select id="inputState" class="form-select">
                          <option selected>-</option>
                          <option>+</option>
                        </select>
                      </div>
                      <div class="mb-3">
                          <label for="exampleFormControlTextarea1" class="form-label">Observation</label>
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                     
                      <form class="row g-3" method="GET" action="{{ route('newmembre') }}">
                          @csrf
                          <div class="col-auto enregsitrerbtn">
                              <button type="submit" class="btn btn-success mb-3 py-2 px-4">
                                  Enregistrer</button>
                          </div>
                      </form> 
                    </form>
              </div>
          </div>
      </main>
  </div>

    <div class="fiche p-4">
      <button id="closeFicheBtn" class="close-fiche-btn">Fermer</button>
      <h5 class="text-center">Fiche d'identification du donneur de sang</h5>
      <div class="row">
        <div class="col-12">
          <form class="row g-3" style="padding-top: 40px">

            <div class="mb-3 row">
              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Nom:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="BOUROUBA">
              </div>

              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Prénom:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Salim">
              </div>

              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">N° de fiche:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="1689">
              </div>
            </div>

            <div class="mb-3 row">
              
              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Epouse de:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="">
              </div>

              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Née le:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="10/10/1990">
              </div>

              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">à:</label>
              <div class="col-sm-10 col-lg-2">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Blida">
              </div>

            </div>


            <div class="mb-3 row">
              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Domicile:</label>
              <div class="col-sm-10 col-lg-4">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Cite bananier Bt 10 N° 02 Blida">
              </div>

              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Tél:</label>
              <div class="col-sm-10 col-lg-4">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="0770.00.00.00">
              </div>
            </div>

            <div class="mb-3 row">
              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Adresse professionnelle:</label>
              <div class="col-sm-10 col-lg-4">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="Hôpital Franz Fanoun Blida">
              </div>

              <label for="staticEmail" class="col-sm-2 col-form-label labelfiche">Tél:</label>
              <div class="col-sm-10 col-lg-4">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value=" 025.20.00.00">
              </div>
            </div>
        
            <div class="row d-flex justify-content-evenly">
              <div class="col-sm-10 col-lg-4 form3">
              <label for="inputState" class="form-label">groupe sanguin:</label>
                <input type="text" class="form-control-plaintext" id="staticEmail" value="Rh: A+">
              </div>
              <div class="col-sm-10 col-lg-4 form3">
              <label for="inputState" class="form-label">Phénotype:</label>
                <input type="text" class="form-control-plaintext" id="staticEmail" value="C">
                <input type="text" class="form-control-plaintext" id="staticEmail" value="E">
                <input type="text" class="form-control-plaintext" id="staticEmail" value="c">
                <input type="text" class="form-control-plaintext" id="staticEmail" value="e">
                <input type="text" class="form-control-plaintext" id="staticEmail" value="Kell">
              </div>
            </div>
            
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label observation">Observations médicales</label>
                <textarea class="form-control-plaintext" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
          </form>
        </div>
      </div>

      <div class="prelevement">
        <main>
          <div class="container-fluid">
              <div class="card mb-4">
                  <div class="card-header" style="color: #909090">
                      <p class="text-center">                         
                          PRELEVEMENT
                      </p>                      
                  </div>
                  <div class="card-body">
                      <table class="table table-striped">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Date</th>
                              <th scope="col">Lieu de don</th>
                              <th scope="col">Pds</th>
                              <th scope="col">N° flacon</th>
                              <th scope="col">C C</th>
                              <th scope="col">TA</th>
                              <th scope="col">Sérologie</th>
                              <th scope="col">Observation</th>
                              <th scope="col">Options</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">1</th>
                              <td>15/03/1991</td>
                              <td>Blida</td>
                              <td>78</td>
                              <td>15</td>
                              <td>...</td>
                              <td>...</td>
                              <td>...</td>
                              <td>...</td>
                              <td class="opt">
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/3D9BFF/20/edit--v1.png" alt="edit--v1"/></a>
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                              </td>
                            </tr>

                            <tr>
                              <th scope="row">2</th>
                              <td>15/03/1991</td>
                              <td>Blida</td>
                              <td>78</td>
                              <td>15</td>
                              <td>...</td>
                              <td>...</td>
                              <td>...</td>
                              <td>...</td>
                              <td class="opt">
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/3D9BFF/20/edit--v1.png" alt="edit--v1"/></a>
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                              </td>
                            </tr>

                            <tr>
                              <th scope="row">3</th>
                              <td>15/03/1991</td>
                              <td>Blida</td>
                              <td>78</td>
                              <td>15</td>
                              <td>...</td>
                              <td>...</td>
                              <td>...</td>
                              <td>...</td>
                              <td class="opt">
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/3D9BFF/20/edit--v1.png" alt="edit--v1"/></a>
                                  <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                              </td>
                            </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </main>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const openFormBtn = document.getElementById('openFormBtn');
        const membreForm = document.getElementById('membreForm');
        const overlay = document.getElementById('overlay');
        const closeFormBtn = document.getElementById('closeFormBtn');
        const searchMoreButtons = document.querySelectorAll('.search-more');
        const closeFicheBtn = document.getElementById('closeFicheBtn');

        const openForm = () => {
            membreForm.classList.add('active');
            overlay.classList.add('active');
        };

        const closeForm = () => {
            membreForm.classList.remove('active');
            overlay.classList.remove('active');
        };

        const closeFiche = () => {
            document.querySelector('.fiche').style.display = 'none'; // Cache la fiche
            overlay.classList.remove('active'); // Cache l'overlay
        };

        openFormBtn.addEventListener('click', openForm);
        overlay.addEventListener('click', closeForm);
        closeFormBtn.addEventListener('click', closeForm);
        closeFicheBtn.addEventListener('click', closeFiche);

        searchMoreButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien

                const memberId = this.getAttribute('data-member-id');

                const memberData = {
                    1: {
                        nom: "BOUROUBA",
                        prenom: "Salim",
                        dateNaissance: "10/10/1990",
                        lieuNaissance: "Blida",
                        telephone: "0770.00.00.00",
                    },
                    2: {
                        nom: "DUPONT",
                        prenom: "Jean",
                        dateNaissance: "15/05/1985",
                        lieuNaissance: "Alger",
                        telephone: "0555.00.00.00",
                    },
                };

                if (memberData[memberId]) {
                    document.querySelector('#staticEmail').value = memberData[memberId].nom; // Nom
                    // Remplissez les autres champs de la fiche avec les données du membre
                }

                document.querySelector('.fiche').style.display = 'block'; // Affichez la fiche
                overlay.classList.add('active'); // Affichez l'overlay
            });
        });
    });

    document.querySelectorAll(".button-value").forEach(select => {
    select.addEventListener("change", function() {
        // Applique la classe "selected" pour changer le style
        this.classList.add("selected");
    });
});


document.querySelectorAll('.plus-math').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                document.querySelector('.prelevement').style.display = 'block';
                document.querySelector('.overlay2').style.display = 'block';
            });
        });

        document.getElementById('closeFormBtnPrelevement').addEventListener('click', () => {
            document.querySelector('.prelevement').style.display = 'none';
            document.querySelector('.overlay2').style.display = 'none';
        });

        document.getElementById('overlay2').addEventListener('click', () => {
            document.querySelector('.prelevement').style.display = 'none';
            document.querySelector('.overlay2').style.display = 'none';
        });

    </script>

@endsection