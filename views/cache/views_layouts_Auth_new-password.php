<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Sprayer App</title>


    <!-- Bootstrap core CSS -->
    <link href="<?php echo asset('vendors/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('vendors/select2/css/select2.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('vendors/fontawesome/css/font-awesome.min.css') ?>" rel="stylesheet">

    <script src="<?php echo asset('vendors/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        #password-content,
        #password-modal-content {
            position: relative;
        }

        #show-password,
        #show-modal-password {
            position: absolute;
            top: 35%;
            right: 4%;
            cursor: pointer;
            color: lightgray;
        }

        #show-password:hover,
        #show-modal-password:hover {
            color: gray;
        }

        .select2 {
            width: 100%;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="<?php echo asset('css/signin.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('vendors/fontawesome/css/font-awesome.min.css') ?>" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin">
        <div id="resetPasswordAlert"></div>
        <form name="formResetPassword" id="formResetPassword">
            <div>
                <p class="text-secundary">Type a new password</p>
            </div>
            <input type="hidden" name="token" id="token" value="<?php echo $token ?>">

            <div class="form-floating mb-3 mt-3" id="password-content">
                <input type="password" name="password" class="form-control" id="password" placeholder="*******">
                <i id="show-password" class="fa fa-eye"></i>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3 mt-3">
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="*****">
                <label for="confirm_password">Confirm</label>
            </div>
           
            <button class="btn btn-success btn-lg w-100 mt-3" id="btnResetPassword" type="submit">save</button>
            <p>
                <a href="/login">login</a>
            </p>
            <p class="mt-5 mb-3 text-muted">&copy; 2022 - 2023</p>
        </form>
    </main>
    <script src="<?php echo asset('vendors/jquery/jquery.min.js') ?>"></script>

    <script type="text/javascript">
        const showPassword = document.querySelector("#show-password");
        const showModalPassword = document.querySelector("#show-modal-password");
        const inputPassword = document.querySelector("#password");
        const confirmInputPassword = document.querySelector("#confirm_password");
        const inputModalPassword = document.querySelector("#seller_password");
        const loading = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>';
        const paramsRequest = {};
        const contentRequest = document.querySelector("#resultRequest");

        showPassword.addEventListener("click", function() {
            this.classList.toggle("fa-eye-slash");
            let currentType = inputPassword.type;
            inputPassword.type = currentType == "password" ? "text" : "password";
            confirmInputPassword.type = currentType == "password" ? "text" : "password";
        });

        /**
         * Build a alert message based on a type 
         * @param - type the type f the alert
         *  - Example: info, error, success, warning
         */
        function buildAlert(type, message, place = "resetPasswordAlert") {
            let icon = '<i class="fa fa-check"></i>';
            switch (type) {
                case 'success':
                    icon = '<i class="fa fa-2x fa-check-circle"></i>';
                    break;
                case 'error':
                    icon = '<i class="fa fa-2x fa-times-circle"></i>';
                    break;
                case 'info':
                    icon = '<i class="fa fa-2x fa-info-circle"></i>';
                    break;
                default:
                    icon = '<i class="fa fa-2x fa-info-circle"></i>';
                    break;
            }
            let alertType = (type == 'error') ? 'danger' : type;
            let output = `<div class="alert alert-${alertType} row" text-left><div class="col-sm-2"><strong>${icon}</strong></div><div class="col-sm-8">${message}</div><div class="col-sm-2"><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>`;
            document.querySelector("#" + place).innerHTML = output;
        }

        document.querySelector("#btnResetPassword").addEventListener("click", function(event) {


            document.querySelector("#resetPasswordAlert").innerHTML = '';
            event.preventDefault();
            let currentButton = this;
            let _oldText = currentButton.innerHTML;
            currentButton.innerHTML = loading;
            currentButton.disabled = true;
            let form = document.querySelector("#formResetPassword");
            let formData = new FormData(form);
            let url = "<?php echo route('/update-user-password') ?>";

            fetch(url, {
                    method: "POST",
                    body: formData
                }).then(resp => resp.json())
                .then(resp => {
                    buildAlert(resp.status, resp.message, "resetPasswordAlert");
                    if (resp.status == "success") {
                        form.reset();
                    }
                })
                .catch(error => {
                    buildAlert("error", error.message);
                }).finally(() => {
                    currentButton.disabled = false;
                    currentButton.innerHTML = _oldText;
                });
        });
    </script>
</body>

</html>