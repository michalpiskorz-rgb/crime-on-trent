<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Login — xcrime</title>

    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

</head>

<body class="d-flex flex-column">

<div class="page page-center">

    <div class="container container-tight py-4">

        <div class="text-center mb-4">

            <h1 class="navbar-brand navbar-brand-autodark">
                xcrime
            </h1>

        </div>


        <div class="card card-md">

            <div class="card-body">

                <h2 class="h2 text-center mb-4">
                    Login to your account
                </h2>


                <!-- ERROR MESSAGE -->

                <div class="alert alert-danger" role="alert">

                    <div>
                        Invalid username or password.
                    </div>

                </div>


                <form method="post"
                      action="login.php"
                      autocomplete="off">


                    <!-- USERNAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            placeholder="Your username"
                            autocomplete="username"
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-2">

                        <label class="form-label">

                            Password

                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Your password"
                            autocomplete="current-password"
                        >

                    </div>


                    <!-- LOGIN -->

                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            Login

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <div class="text-center text-secondary mt-3">

            xcrime administration panel

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>

</body>

</html>
