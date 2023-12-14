<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>Sprayer App</title>


  <!-- Bootstrap core CSS -->
  <link href="{{ asset('vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendors/select2/css/select2.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendors/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet">

  <script src="{{ asset('vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }
    #password-content, #password-modal-content{
      position: relative;
    }
    #show-password, #show-modal-password{
      position:absolute;
      top:35%;
      right: 4%;
      cursor: pointer;
      color: lightgray;
    }
    #show-password:hover, #show-modal-password:hover{
      color:gray;
    }
    .select2{
      width: 100%;
    }
    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>


  <!-- Custom styles for this template -->
  <link href="{{ asset('css/signin.css') }}" rel="stylesheet">
  <link href="{{ asset('vendors/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet">
</head>

<body class="text-center">
  <main class="form-signin">
    <div id="loginAlert"></div>
    <form name="formSignIn" id="formSignIn">
      <img class="mb-4" src={{ asset('technoserve-banner-transparent.png') }} alt="Technoserve Logo" width="300" >

      <div class="form-floating mb-3 mt-3">
        <input type="text" name="username" class="form-control" id="username" placeholder="">
        <label for="username">Username:</label>
      </div>
      <div class="form-floating mb-3 mt-3" id="password-content">
        <input type="password" name="password" class="form-control" id="password" placeholder="">
        <i id="show-password" class="fa fa-eye"></i>
        <label for="password">Password</label>
      </div>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button class="btn btn-primary btn-lg w-100 mt-3" id="btnLogin" type="submit">Login</button>
      <p class="mt-2" style="display: flex; align-items: center; justify-content: space-between">
        <a href="/forgot-password">forgot password?</a>
        <button type="button" class="btn btn-link" onclick="contentRequest.innerHTML=''" data-bs-toggle="modal" data-bs-target="#modalSeller">Register to be seller</button>
      </p>
      <p class="mt-5 mb-3 text-muted">&copy; 2022 - 2023</p>
    </form>
  </main>
<!-- Modal to register a seller -->
<div class="modal fade modal-fullscreen-sm-down" id="modalSeller">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Request to be a seller</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
         <form action="." method="post" name="formSaveRequest" id="formSaveRequest">
          <div id="resultRequest"></div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="first_name" placeholder="Enter first name" name="first_name">
                <label for="first_name">First name</label>
              </div>
            </div>
            <div class="col-sm-6">              
              <div class="form-floating mb-3 mt-3">
                <input type="text" class="form-control" id="last_name" placeholder="Enter last name" name="last_name">
                <label for="last_name">Last name</label>
              </div>
            </div>
          </div>

          <div class="form-floating mb-3 mt-3">
            <input type="tel" class="form-control" id="mobile_number" placeholder="Mobile Number" name="mobile_number">
            <label for="mobile_number">Mobile Number</label>
          </div>

          <div class="form-floating mb-3 mt-3">
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            <label for="email">Email</label>
          </div>

          <div class="form-floating mb-3 mt-3" id="password-modal-content">
            <input type="password" class="form-control" id="seller_password" placeholder="Password" id="seller_password" name="password">
            <i class="fa fa-eye" id="show-modal-password"></i>
            <label for="seller_password">Enter password</label>
          </div>

          <div class="form-floating mb-3 mt-3">
              <select class="form-select" id="province" name="province">
              <option option="-1" disabled></option>
              <option value="Cabo Delgado">Cabo Delgado</option>
              <option value="Zambezia">Zambezia</option>
              <option value="Nampula">Nampula</option>
              </select>
              <label for="sel1" class="form-label">Province:</label>
          </div>

          <div class="form-floating mb-3 mt-3">
              <select class="form-select" id="district" name="district">
              
              </select>
              <label for="sel1" class="form-label">District:</label>
          </div> 
         

          <div class="d-grid">
              <button type="button" id="subtmitRequest" class="btn btn-primary btn-block">Submit</button>
          </div> 
         </form> 
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- Close the Modal to register a seller -->
  <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendors/select2/js/select2.full.min.js') }}"></script>

  <script src="{{ asset('scripts/location.js') }}"></script>
  
  <script type="text/javascript">
    const showPassword =document.querySelector("#show-password");
    const showModalPassword =document.querySelector("#show-modal-password");
    const inputPassword = document.querySelector("#password");
    const inputModalPassword = document.querySelector("#seller_password");
    const loading = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>';
    const paramsRequest = {};
    const contentRequest = document.querySelector("#resultRequest");

    showPassword.addEventListener("click", function(){
      this.classList.toggle("fa-eye-slash");
      let currentType = inputPassword.type;
      inputPassword.type = currentType == "password" ? "text" : "password";
    });

    showModalPassword.addEventListener("click", function(){
      this.classList.toggle("fa-eye-slash");
      let currentType = inputModalPassword.type;
      inputModalPassword.type = currentType == "password" ? "text" : "password";
    });

    /**
     * Build a alert message based on a type 
     * @param - type the type f the alert
     *  - Example: info, error, success, warning
     */
    function buildAlert(type, message, place = "resultRequest"){
      let icon = '<i class="fa fa-check"></i>';
      switch(type){
        case 'success' : icon = '<i class="fa fa-2x fa-check-circle"></i>'; break;
        case 'error' : icon = '<i class="fa fa-2x fa-times-circle"></i>'; break;
        case 'info' : icon = '<i class="fa fa-2x fa-info-circle"></i>'; break;
        default: icon = '<i class="fa fa-2x fa-info-circle"></i>'; break;
      }
      let alertType = (type == 'error') ? 'danger' : type;
      let output =`<div class="alert alert-${alertType} row" text-left><div class="col-sm-2"><strong>${icon}</strong></div><div class="col-sm-8">${message}</div><div class="col-sm-2"><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>`;
      document.querySelector("#"+place).innerHTML = output;
    }

    document.querySelector("#btnLogin").addEventListener("click", function(event){
      event.preventDefault();
      let currentButton = this;
      let _oldText = currentButton.innerHTML;
      currentButton.innerHTML = loading;
      currentButton.disabled = true;
      let form = document.querySelector("#formSignIn");
      let formData = new FormData(form);
      let url = "{{ route('/authanticate') }}";

      fetch(url,{
        method: "POST",
        body: formData
      }).then(resp => resp.json())
        .then(resp => {
          buildAlert(resp.status, resp.message, "loginAlert");
          if(resp.status == "success"){
            form.reset();
            location.href = "{{route('')}}";
          }
        })
        .catch(error => {
          buildAlert(error.status, error.message);
        }).finally(() => {
          currentButton.disabled = false;
          currentButton.innerHTML = _oldText; 
        });            
    });
    

    /*Request a seller profile*/
    
    document.querySelector("#subtmitRequest").addEventListener("click", function(){
      contentRequest.innerHTML = ''
      contentRequest.innerHTML = loading
      let pswd = document.querySelector("seller_password");
      if( pswd == ""){
        buildAlert('info', 'The password is required and must mutch!')
      }
      let currentButton = this;
      let _oldText = currentButton.innerHTML;
      currentButton.innerHTML = loading;
      currentButton.disabled = true;
      let form = document.querySelector("#formSaveRequest");
      let formData = new FormData(form);
      let url = "{{route('/request/seller/profile')}}";

      fetch(url,{
        method: "POST",
        body: formData
      })
      .then(resp => resp.json())
        .then(resp => {
          buildAlert(resp.status, resp.message);
          if(resp.status == "success"){
            form.reset();
            location.href = "{{route('/login')}}";
          }
        })
      .catch(error => {
          buildAlert(error.status, error.message);
        })
      .finally(() => {
        currentButton.disabled = false;
        currentButton.innerHTML = _oldText;   
      })
        
    });
    $(document).ready(function() {
      $("#province").trigger("change");
    });
  </script>
</body>

</html>