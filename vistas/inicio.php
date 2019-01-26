
<!DOCTYPE HTML>
<html class="particles_effect image_background" lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="CacicusTech.Inc">
    <title>Repositorio digital, Archivo Histórico CCA</title>
    <link rel="shortcut icon" href="src/inicial/images/favicon.png">
    <!--<link rel="apple-touch-icon" sizes="57x57" href="src/inicial/images/apple-touch-icon-57.png">-->

    <link href="src/inicial/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/inicial/css/uikit.css" rel="stylesheet" media="all">
    <link href="src/inicial/css/slideshow.min.css" rel="stylesheet" media="all">
    <link href="src/inicial/css/styles.css" rel="stylesheet" media="all">
    <link rel="stylesheet" type="text/css" href="src/inicial/css/adicional.css">
    
    
    <!-- Estilos globales -->
    <link rel="stylesheet" href="src/inicial/css/estilosGlobales.css">
    
    
  </head>
  <body style="background-color: transparent !important">
    <!--<header class="uk-small-hidden header">
      <nav class="uk-navbar header-nav">
        <div class="uk-container uk-container-center">
          <div class="uk-navbar-brand logo_link">
            <img class="uk-responsive-height" src="src/inicial/images/CACICUS-DEMO.png" alt="">
          </div>
          <div class="uk-navbar-flip header_navbar_wrapper">
            <span class="uk-navbar-toggle header_navbar_mobile"></span>
          </div>
          <div class="uk-navbar-flip header_nav_wrapper">
            <ul class="uk-navbar-nav header_nav_list">
              <li class="header_nav_list_item">
                <a class="link_home uk-active header_nav_list_item_link" href="#">Inicio</a>
              </li>
              <li class="header_nav_list_item">
                <a class="link_projects header_nav_list_item_link" href="#">Archivos destacados</a>
              </li>
              <li class="header_nav_list_item">
                <a class="link_about header_nav_list_item_link" href="#">¿Quienes somos?</a>
              </li>
              <li class="header_nav_list_item">
                <a class="link_location header_nav_list_item_link" href="#">Contactos</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
    -->
    <div class="uk-cover-background uk-flex uk-flex-middle uk-position-relative uk-text-center wrapper">
      <div class="row" style="width: 100%">
        <div class="col-md-9">
          <section class="uk-container uk-position-relative main">
            <span id="textoPrincipalPortada" class="uk-h1 uk-margin-large-top uk-text-contrast uk-text-uppercase title">ARCHIVO HISTÓRICO <br>CENTRO CÍVICO CIUDAD ALFARO</span>
            <div class="uk-position-relative uk-margin-top uk-margin-large-bottom uk-width-1-1 counter_button uk-active subtitile_wrapper">
              <div class="uk-text-contrast subtitile"><span id="subtituloPortada">Repositorio digital.</span></div>
            </div>
  
          </section>
        </div>
    
         
        <div class="col-md-3 frm_iniciar_sesion iniciar_sesion_caja">
          <div class="container login-container">
            <div class="formularios card card-login mx-auto mt-md-5">
              <div class="card-header">Iniciar sesión</div>
                <div class="card-body">
                  <form autocomplete="Off" id="FrmIniciarSesion">
                    <div class="form-group">
                      <div class="form-label-group"> 
                        <input type="text" id="correo" name="correo" class="form-control campo" placeholder="Correo electrónico" autofocus="autofocus">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-label-group">
                        <input type="password" id="contrasena" name="contrasena" class="form-control campo" placeholder="Contraseña">
                      </div>
                    </div>
                    <div class="notificacion-ingreso"></div> 
                    <input type="submit" class="btn btn-primary btn-block" id="acceder_login" value="ACCEDER">
                  </form> 
                  <div class="text-center">
                    <a class="d-block small mt-3 registrar_usuario" href="#">Registrarme</a>
                    <a class="d-block small olvido_contrasena" href="#">¿Olvidaste la contraseña?</a>
                  </div> 
                </div>
            </div> 
          </div> 
        </div>
        
         <div class="col-md-3 frm_registrar registrar_usuario_caja" style="display: none;">
          <div class="container login-container">
            <div class="formularios card card-login mx-auto mt-5">
              <div class="card-header">Registrarme</div>
              <div class="card-body">
                <form autocomplete="Off" id="FrmRegistrarUsuario">
                  <div class="form-group"> 
                    <div class="form-label-group">
                      <input type="text" id="apellidos" name="apellidos" class="form-control campo" placeholder="Apellidos*" autofocus="autofocus">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="text" id="nombres" name="nombres" class="form-control campo" placeholder="Nombres*" autofocus="autofocus"  >
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <select class="form-control campo" id="sexo" name="sexo" >
                        <option value="">Género*</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otros</option>
                      </select>
                    </div> 
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <select class="form-control campo" id="ocupacion" name="ocupacion">
                        <option value="">Ocupación*</option>
                      <?php foreach ($this->acceso->MostrarOcupaciones() AS $r){ ?>
                        <option value="<?php echo $r->id; ?>"><?php echo $r->nombre; ?></option>  
                      <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="text" id="organizacion" name="organizacion" class="form-control" placeholder="Organización" autofocus="autofocus">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="tel" id="celular" name="celular" class="form-control" placeholder="Celular" autofocus="autofocus">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="email" id="nuevo_correo" name="nuevo_correo" class="form-control campo" placeholder="Correo electrónico*" autofocus="autofocus">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control campo" placeholder="Contraseña*" autofocus="autofocus" >
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input type="password" id="repetir_contrasena" name="repetir_contrasena" class="form-control campo" placeholder="Repetir contraseña*" autofocus="autofocus">
                    </div> 
                  </div> 
                    
                  <div class="notificacion-ingreso"></div>
                  
                  <input type="submit" class="btn btn-primary btn-block" id="registrarme" value="REGISTRARME"> 
                  <button type="button" class="btn btn-secondary btn-block boton_cancelar_registro">CANCELAR</button>    
                  <button type="button" class="btn btn-primary btn-block boton_iniciar_sesion">INICIA SESION</button> 
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3 frm_restablecer_contrasena contrasena_olvidar_caja" style="display: none;">
          <div class="container login-container">
            <div class="formularios card card-login mx-auto mt-5">
              <div class="card-header">Restablecer contraseña</div>
                <div class="card-body">
                  <form autocomplete="Off" id="FrmRestablecerContrasena">
                    <div class="form-group" style="font-size: 15px;color: #8f8d8b;margin-top: -14px;">
                      <label>Ingresa la dirección de correo electrónico que utilizas para ingresar al sistema. Te enviaremos una clave temporal.</label><br>
                      <div class="form-label-group">
                        <input type="text" id="correo_restablecer" name="correo_restablecer" class="form-control campo" placeholder="Correo electrónico">
                      </div>
                    </div>
                    <div class="notificacion-ingreso"></div>
                    <input type="submit" class="btn btn-primary btn-block" id="restablecer" value="RESTABLECER"> 
                     <button type="button" class="btn btn-secondary btn-block boton_cancelar_olvidar_contrasena">CANCELAR</button> 
                  </form>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="popup_wrapper">
      <div class="popup_link_projects popup">
        <div class="uk-flex uk-flex-middle uk-position-relative uk-text-center popup_overlay">
          <div class="uk-container uk-container-center uk-text-center uk-position-relative popup_container">

            <h3 class="uk-h3 uk-text-contrast animated desc_title">Nuestros proyectos</h3>
            <p class="animated subtext">We develop innovations that are changing the world</p>
            <div class="uk-grid uk-grid-medium">
              <div class="uk-width-medium-1-3 uk-width-large-1-3 animated">
                <a class="uk-display-block uk-position-relative uk-overflow-hidden gallery_block" href="src/inicial/images/1280x720.jpg" data-lightbox-type="image" data-uk-lightbox="{group:'my-group'}">
                  <div class="uk-position-cover gallery_overlay">
                    <div class="uk-text-center gallery_table">
                      <div class="uk-text-center gallery_cell">
                        <div class="uk-text-center gallery_container">
                          <h4 class="uk-h4 uk-text-contrast gallery_caption">Title</h4>
                          <div class="uk-text-contrast gallery_description">Lorem ipsum dolor sit amet, consectetur adipisicing elit Lorem ipsum dolor sit amet, consectetur</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="uk-width-medium-1-3 uk-width-large-1-3 animated">
                <a class="uk-display-block uk-position-relative uk-overflow-hidden gallery_block" href="src/inicial/images/720x1280.jpg" data-lightbox-type="image" data-uk-lightbox="{group:'my-group'}">
                  <div class="uk-position-cover gallery_overlay">
                    <div class="uk-text-center gallery_table">
                      <div class="uk-text-center gallery_cell">
                        <div class="uk-text-center gallery_container">
                          <h4 class="uk-h4 uk-text-contrast gallery_caption">Title</h4>
                          <div class="uk-text-contrast gallery_description">Lorem ipsum dolor sit amet, consectetur adipisicing elit Lorem ipsum dolor sit amet, consectetur</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="uk-width-medium-1-3 uk-width-large-1-3 animated">
                <a class="uk-display-block uk-position-relative uk-overflow-hidden gallery_block" href="src/inicial/images/720x480.jpg" data-lightbox-type="image" data-uk-lightbox="{group:'my-group'}">
                  <div class="uk-position-cover gallery_overlay">
                    <div class="uk-text-center gallery_table">
                      <div class="uk-text-center gallery_cell">
                        <div class="uk-text-center gallery_container">
                          <h4 class="uk-h4 uk-text-contrast gallery_caption">Title</h4>
                          <div class="uk-text-contrast gallery_description">Lorem ipsum dolor sit amet, consectetur adipisicing elit Lorem ipsum dolor sit amet, consectetur</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="popup_link_contacts popup">
        <div class="uk-flex uk-flex-middle uk-position-relative uk-text-center popup_overlay">
          <div class="uk-container uk-container-center uk-text-center uk-position-relative popup_container">

            <h3 class="uk-h3 uk-text-contrast animated desc_title">Comunicar</h3>
            <p class="animated subtext">Si tienes alguna duda o algún requerimiento en específico, comunícate con nosotros.</p>
            <form id="contact" class="uk-form animated form_send_message" autocomplete="off" onsubmit="return false">
              <div class="uk-margin-bottom">
                <input id="name" name="name" class="uk-text-contrast form_input" type="text" placeholder="Nombres">
              </div>
              <div class="uk-margin-bottom">
                <input id="email" name="email" class="uk-text-contrast form_input" type="text" placeholder="Correo">
              </div>
              <div class="uk-margin-bottom">
                <textarea id="message" name="message" class="uk-text-contrast form_textarea" placeholder="Mensaje"></textarea>
              </div>
              <div class="uk-margin-bottom">
                <button id="submit" class="uk-button form_button" type="submit">Enviar mensaje</button>
              </div>

              <!-- IF MAIL SENT SUCCESSFULLY -->
              <div class="success">Your message has been sent successfully.</div>
              <div class="success_response"></div>

              <!-- IF MAIL SENDING UNSUCCESSFULL -->
              <div class="error">E-mail must be valid and message must be longer than 1 character.</div>
              <div class="error_response"></div>
            </form>

          </div>
        </div>
      </div>
      <div class="popup_link_location popup">
        <div class="uk-flex uk-flex-middle uk-position-relative uk-text-center popup_overlay">
          <div class="uk-container uk-container-center uk-text-center uk-position-relative popup_container">

            <div class="location_wrapper">
              <div class="location_text_wrapper uk-active">
                <h3 class="uk-h3 animated uk-text-contrast desc_title">Contactos</h3>
                <p class="animated subtext">If you require any further information, feel free to contact us</p>
                <div class="uk-grid uk-grid-collapse">
                  <div class="uk-width-medium-1-3 animated">
                    <div class="uk-margin-large-bottom location_item">
                      <div class="uk-icon-map-marker location_label"></div>
                      <h4 class="location_title uk-text-contrast">Office location</h4>
                      <div class="location_text">7901 W 79th St, <br>Playa Del Rey, CA 90293</div>
                    </div>
                  </div>
                  <div class="uk-width-medium-1-3 animated">
                    <div class="uk-margin-large-bottom location_item">
                      <div class="uk-icon-phone location_label"></div>
                      <h4 class="location_title uk-text-contrast">Call us</h4>
                      <div class="location_text">t. 1-234-567-890<br>m. 1-234-567-891</div>
                    </div>
                  </div>
                  <div class="uk-width-medium-1-3 animated">
                    <div class="uk-margin-large-bottom location_item">
                      <div class="uk-icon-envelope-o location_label"></div>
                      <h4 class="location_title uk-text-contrast">Email address</h4>
                      <div class="location_text">info@yoursite.com<br>support@yoursite.com</div>
                    </div>
                  </div>
                  
                  <div class="uk-width-medium-3-3 animated">
                    <div class="uk-button uk-button-large uk-border-rounded counter_button_message">Déjanos tu mensaje.</div>
                  </div>
                </div>


              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="popup_link_about popup">
        <div class="uk-flex uk-flex-middle uk-position-relative uk-text-center popup_overlay">
          <div class="uk-container uk-container-center uk-text-center uk-position-relative popup_container">

            <h3 class="uk-h3 uk-text-contrast animated desc_title">About us</h3>
            <p class="animated subtext">We desire to create responsive user friendly websites</p>
            <p class="animated desc_text">Our agency is formed by a young and dynamic team of professionals, where experts from different areas get together to create amazing web design that will help your business grow</p>
            <p class="animated desc_text">Our main focus is on creating modern web apps, mobile iOS and Android apps, and responsive websites</p>
            <p class="animated desc_text">Projects that we create fit the specific needs of each client. We care that our customers are always satisfied and objectives are achieved with the highest standards</p>

          </div>
        </div>
      </div>
    </div>
   <div class="spinner">
      <div class="spinner_wrapper">
        <div class="spinner_folding_cube">
          <div class="spinner_cube1 spinner_cube"></div>
          <div class="spinner_cube2 spinner_cube"></div>
          <div class="spinner_cube4 spinner_cube"></div>
          <div class="spinner_cube3 spinner_cube"></div>
        </div>
      </div>
    </div>
    
    <script src="src/inicial/js/jquery-3.3.1.slim.min.js"></script>
    <script src="src/inicial/js/bootstrap.min.js"></script>
    <script src="src/inicial/js/jquery.min.js"></script>
    <script src="src/inicial/js/particles.min.js"></script>
    <script src="src/inicial/js/scripts.js"></script>
    <script type="module" src="app/inicioSesion.js" ></script>
    <script type="module" src="app/registroUsuario.js"></script>
  </body>
</html>