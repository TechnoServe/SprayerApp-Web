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
        <div id="loginAlert"></div>
        <form name="formSendPasswordCodeOrLink" id="formSendPasswordCodeOrLink">
            <div>
                <p class="text-secundary">Type your email to get the reset code the password</p>
            </div>
            <div class="form-floating mb-3 mt-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="">
                <label for="username">Emails <span class="text-danger">*</span></label>
            </div>
            <button class="btn btn-primary btn-lg w-100 mt-3" id="btnGetLink" type="submit">send</button>

            <p>
                <a href="/login">login</a>
            </p>
            <p class="mt-5 mb-3 text-muted">&copy; 2022 - 2023</p>
        </form>
    </main>
    <script src="<?php echo asset('vendors/jquery/jquery.min.js') ?>"></script>

    <script type="text/javascript">
        const loading = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>';

        /**
         * Build a alert message based on a type 
         * @param - type the type f the alert
         *  - Example: info, error, success, warning
         */
        function buildAlert(type, message, place = "loginAlert") {
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

        document.querySelector("#btnGetLink").addEventListener("click", function(event) {
            document.querySelector("#loginAlert").innerHTML = '';
            event.preventDefault();
            let currentButton = this;
            let _oldText = currentButton.innerHTML;
            currentButton.innerHTML = loading;
            currentButton.disabled = true;
            let form = document.querySelector("#formSendPasswordCodeOrLink");
            let formData = new FormData(form);
            let url = "<?php echo route('/send-password-link') ?>";

            fetch(url, {
                    method: "POST",
                    body: formData
                }).then(resp => resp.json())
                .then(resp => {
                    buildAlert(resp.status, resp.message, "loginAlert");
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