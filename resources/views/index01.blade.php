@extends('layouts.public.master')
@section('content')
<style>
    .card-body{
        margin-right: 8px
    }
</style>
    <div class="dashboard_content bg-light-4 container-fluid">
        
            <form class="row g-3">                
                <div class="col-auto">
                    <select class="form-select py-2" aria-label="Default select example">
                        <option selected disabled>Séléctionner groupage</option>
                        <option value="1">O+</option>
                        <option value="1">A+</option>
                        <option value="2">B+</option>
                        <option value="3">AB+</option>
                        <option value="1">O-</option>
                        <option value="1">A-</option>
                        <option value="2">B-</option>
                        <option value="3">AB-</option>
                      </select>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary mb-3 py-2">Chercher donneurs</button>
                </div>
              </form>   
              
              <div class="map container-fluid">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3208.0678572157017!2d2.8054148999999993!3d36.480077099999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128f0d049d1732e9%3A0xbc15d0149d816fed!2sBoite%20de%20communication%20TAHAR%20Tech!5e0!3m2!1sen!2sdz!4v1737385673524!5m2!1sen!2sdz"
                  style="width: 100%; height: 450px; border: 0;" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>


                <div class="bigcard container-fluid my-1">                  
                    <div class=" mb-4">                       
                       <div class="d-lg-flex justify-content-center row">
                      
                            <div class="card card-body p-4 col-sm-12 col-lg-6">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Distance</th>
                                            <th>Groupage</th>
                                            <th>Temp</th>
                                        </tr>
                                    </thead>
                                   
                                    <tbody>
                                        <tr>
                                            <td>123</td>
                                            <td>2 Km</td>
                                            <td><img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>O+</td>
                                            <td>20 min</td>                                           
                                        </tr>                                 
                                        <tr>
                                            <td>173</td>
                                            <td>30 m</td>
                                            <td><img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>A+</td>
                                            <td>15 min</td>
                                            
                                        </tr>
                                        <tr>
                                            <td>254</td>
                                            <td>100m</td>
                                            <td><img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>AB-</td>
                                            <td>10 min</td>
                                           
                                        </tr>
                                        <tr>
                                            <td>968</td>
                                            <td>445 m</td>
                                            <td><img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>B+</td>
                                            <td>36 min</td>
                                           
                                        </tr>
                                    
                                        <tr>
                                            <td>369</td>
                                            <td>302 m</td>
                                            <td><img width="24" height="24" src="https://img.icons8.com/color/48/drop-of-blood.png" alt="drop-of-blood"/>AB+</td>
                                            <td>42 min</td>
                                           
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                       

                     
                            <div class="card card-body p-4 col-sm-12 col-lg-2 d-flex align-items-center">
                                <table id="datatablesSimple">                                  
                                    <tbody>
                                        <tr>
                                            <td><p class="static text-center mb-0">10</p></td>
                                                                                   
                                        </tr>                                 
                                     
                                        <tr>
                                            <td class="text-center pb-4">Donneurs</td>
                                             
                                        </tr>
                                        <tr>
                                            <td><p class="static text-center mb-0">03</p></td>
                                           
                                           
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
                                                  <button type="submit" class="btn btn-primary mb-3 py-2">Envoyer la demande</button>
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
@endsection