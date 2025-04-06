@extends('layouts.public.master')
@section('content')

<style>
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
.satut1, .satut2, .satut3{
    border-radius: 5px;
    padding: 4px;
    color: #fff;
    
}
.satut1{
    background-color: #3AC47D;
}
.satut2{
    background-color: #3D9BFF;
}
.satut3{
    background-color: #F8C039;
}
</style>

<div class="dashboard_content bg-light-4 container-fluid" style="height: 100%">
    <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-sm-12 col-lg-5 static2 d-flex align-items-center justify-content-center">
                    <div>
                        <img width="24" height="24" src="https://img.icons8.com/material-rounded/3AC47D/24/long-arrow-left.png" alt="long-arrow-left" class="iconstatic"/>
                        <img width="60" height="60" src="https://img.icons8.com/pastel-glyph/3AC47D/64/--bloodbag.png" alt="--bloodbag" class="iconstatic">
                    </div>
                    <div class="my-2 mt-4 boxstatic">
                        <p class="textstatic numstatic1">310</p>
                        <p style="text-align: left">Demandes envoyées</p>
                    </div>
                </div>
        
                <div class="col-sm-12 col-lg-5 static2 d-flex align-items-center justify-content-center">
                    <div>
                        <img width="45" height="45" src="https://img.icons8.com/ios-filled/de2e23/50/leave.png" alt="leave"/>
        
                    </div>
                    <div class="my-2 mt-4 boxstatic ps-2">
                        <p class="textstatic numstatic2">310</p>
                        <p style="text-align: left">demandes nécessitant un don</p>
                    </div>
                </div>
            </div>
        
            <div class="row mt-3">
                <div class="col-sm-12 col-lg-5 static2 d-flex align-items-center justify-content-center">
                    <div>
                        <img width="45" height="45" src="https://img.icons8.com/ios-filled/F8C039/50/batch-assign.png" alt="batch-assign"/>
                    </div>
                    <div class="my-2 mt-4 boxstatic ps-2">
                        <p class="textstatic numstatic3">540</p>
                        <p style="text-align: left">donneurs actifs</p>
                    </div>
                </div>
        
                <div class="col-sm-12 col-lg-5 static2 d-flex align-items-center justify-content-center">
                    <div>
                        <img width="24" height="24" src="https://img.icons8.com/material-rounded/3D9BFF/24/long-arrow-right.png" alt="long-arrow-right"/>
                        <img width="60" height="60" src="https://img.icons8.com/pastel-glyph/3D9BFF/64/--bloodbag.png" alt="--bloodbag" class="iconstatic">
                    </div>
                    <div class="my-2 mt-4 boxstatic">
                        <p class="textstatic numstatic4">50</p>
                        <p style="text-align: left">Demandes reçues </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-5">
            <img src="{{ asset('image/Blood-donation-pana.png') }}" alt="" style="height: 270px; width:430px" class="imgdemande">
        </div>
    </div>

    <div class="py-5 d-flex flex-column align-items-center justify-content-evenly">
        <div id="search-container" class="d-flex align-items-center justify-content-center">
         
          <input class="cherchemembre" style="width: 100%"
            type="search"
            id="search-input"
            placeholder="Chercher dans la liste..."/>
          <button id="search">Chercher</button>
        </div>

        <div id="buttons">
          
            <select id="inputState search-container" class="button-value">
            <option selected>A+</option>
            <option>B+</option>
            <option>O+</option>
            <option>A-</option>
            <option>B-</option>
            <option>AB+</option>
    
    
          </select>
    
          <select id="inputState search-container" class="button-value">
            <option selected>Phénotype</option>
            <option>...</option>
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


    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid pb-5" style="width: 1090px">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between" style="color: #909090">
                        <div>
                            <img width="25" height="25" src="https://img.icons8.com/ios/25/list.png" alt="list"/>
                            Liste des demandes
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
                                <th scope="col">Date du demande</th>
                                <th scope="col">Nom demandeur</th>
                                <th scope="col">Groupage demandé</th>
                                <th scope="col">Hopital/Lieu</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Options</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th scope="row">1</th>
                                <td>15/03/1991</td>
                                <td>User123</td>
                                <td>A+</td>
                                <td>Frantz fanon</td>
                                <td><span class="satut1">Complété</span></td>
                                <td>0778 98 74 14</td>
                                <td class="opt">
                                    <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/3D9BFF/20/edit--v1.png" alt="edit--v1"/></a>
                                    <a href=""><img data-member-id="1" class="search-more" width="20" height="20" src="https://img.icons8.com/ios/F8C039/20/search-more.png" alt="search-more"/></a>
                                    <a href=""><img width="20" height="20" src="https://img.icons8.com/material-rounded/F8C039/24/appointment-reminders.png" alt="appointment-reminders"/></a>
                                    <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                                </td>
                              </tr>

                              <tr>
                                <th scope="row">2</th>
                                <td>15/03/1991</td>
                                <td>User123</td>
                                <td>A+</td>
                                <td>Frantz fanon</td>
                                <td><span class="satut2">En attente</span></td>
                                <td>0778 98 74 14</td>
                                <td class="opt">
                                    <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/3D9BFF/20/edit--v1.png" alt="edit--v1"/></a>
                                    <a href=""><img data-member-id="1" class="search-more" width="20" height="20" src="https://img.icons8.com/ios/F8C039/20/search-more.png" alt="search-more"/></a>
                                    <a href=""><img width="20" height="20" src="https://img.icons8.com/material-rounded/F8C039/24/appointment-reminders.png" alt="appointment-reminders"/></a>
                                    <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                                </td>
                              </tr>

                              <tr>
                                <th scope="row">3</th>
                                <td>15/03/1991</td>
                                <td>User123</td>
                                <td>A+</td>
                                <td>Frantz fanon</td>
                                <td><span class="satut3">En cours</span></td>
                                <td>0778 98 74 14</td>
                                <td class="opt">
                                    <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-filled/3D9BFF/20/edit--v1.png" alt="edit--v1"/></a>
                                    <a href=""><img data-member-id="1" class="search-more" width="20" height="20" src="https://img.icons8.com/ios/F8C039/20/search-more.png" alt="search-more"/></a>
                                    <a href=""><img width="20" height="20" src="https://img.icons8.com/material-rounded/F8C039/24/appointment-reminders.png" alt="appointment-reminders"/></a>
                                    <a href=""><img class="opticon" width="20" height="20" src="https://img.icons8.com/ios-glyphs/de2e23/20/filled-trash.png" alt="filled-trash"/></a>
                                </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>

                
            </div>
        </main>
    </div>

    
        
             
</div>

@endsection

