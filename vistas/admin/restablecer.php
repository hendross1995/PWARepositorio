<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Formulario actualización de contraseña</title>
    <link href="src/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="src/admin/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="src/inicial/css/estilosGlobales.css">
  </head>
  <body class="bg-dark">
    <div class="container">
      <div class="card card-register mx-auto mt-5">
        <div class="card-header">Formulario de cambio de contraseña <br><small class="text-muted">Para ingresar al sistema debe actualizar su contraseña temporal a una nueva contraseña.</small></div>
        <div class="card-body">
          <form autocomplete="Off" id="FrmActualizarContrasena">
            <div class="form-group">
              <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-label-group">
                      <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario:" value="<?php echo $_SESSION['usuario'] ?>" disabled>
                      <label for="usuario">Usuario:</label>
                    </div>
                  </div>
              </div>
            </div>
            <div class="form-group">
              <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-label-group">
                      <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-control campo" placeholder="Contraseña actual" autofocus="autofocus">
                      <label for="contrasena_actual">* Contraseña temporal actual:</label>
                    </div>
                  </div>
              </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-12">
                      <div class="form-label-group">
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control campo" placeholder="Nueva contraseña">
                        <label for="nueva_contrasena">* Nueva contraseña:</label>
                      </div>
                    </div>
                </div>
            </div> 
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-12">
                      <div class="form-label-group">
                        <input type="password" id="repetir_contrasena" name="repetir_contrasena" class="form-control campo" placeholder="Repetir contraseña">
                        <label for="repetir_contrasena">* Repita la contraseña:</label>
                      </div>
                    </div>
                </div>
            </div> 
            <div class="notificacion-ingreso"></div>
            <input type="submit" class="btn btn-primary btn-block actualizar_contrasena" style="margin-bottom: 10px;" value="ACTUALIZAR"> 
            <small class="text-muted">
                <b>Nota: </b>Recuerder que para mayor seguridad, la nueva contraseña debe contener al menos una letra en mayúscula, letras minúsculas y un dígito.
            </small>
          </form>
        </div>
      </div>
    </div>
    <script src="src/admin/vendor/jquery/jquery.min.js"></script>
    <script src="src/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="app/actualizarContrasena.js" ></script>
    <script src="src/admin/vendor/jquery-easing/jquery.easing.min.js"></script>
  </body>
</html>
