<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if(isset($_GET['index'])){
  if($_GET['index'] == 'src' ||
     $_GET['index'] == 'vistas' ||
     $_GET['index'] == 'modelos' ||
     $_GET['index'] == 'controladores' ||
     $_GET['index'] == 'app' ||
     $_GET['index'] == 'db' ||
     $_GET['index'] == 'assets'){
    header("Refresh:0; url=/");
  }else{
    switch ($_GET['index']){

      #ACCESOS
      case 'iniciar_sesion': require 'controladores/acceso.controlador.php';
        $acceso = new AccesoControlador(); $acceso->IniciarSesion(); break;
      case 'restablecer_contrasena': require 'controladores/acceso.controlador.php';
        $acceso = new AccesoControlador(); $acceso->RestablecerContrasena(); break;
      case 'actualizar_contrasena': require 'controladores/acceso.controlador.php';
        $acceso = new AccesoControlador(); $acceso->ActualizarContrasena(); break;
      case 'salir': require 'controladores/acceso.controlador.php';
        $acceso = new AccesoControlador(); $acceso->Salir(); break;

      #USUARIOS
      case 'registrar_usuario':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->RegistrarUsuarios(); break;
      case 'usuarios':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->FrmUsuarios(); break;
      case 'mostrar_usuarios':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->MostrarUsuarios(); break;
      case 'actualizar_usuarios':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->ActualizarUsuarios(); break;
      case 'lectores':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->FrmUsuariosLectores(); break;
      case 'mostrar_lectores':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->MostrarUsuariosLectores(); break;
      
      
      case 'perfil':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->FrmPerfil(); break; 
      case 'mostrar_perfil':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->MostrarPerfil(); break;
      case 'actualizar_perfil':require 'controladores/usuario.controlador.php';
        $usuario = new UsuarioControlador(); $usuario->ActualizarPerfil(); break;

      #FONDOS
      case 'fondos':require 'controladores/fondo.controlador.php';
        $fondos = new FondoControlador(); $fondos->FrmFondos(); break;
      case 'mostrar_fondos':require 'controladores/fondo.controlador.php';
        $fondos = new FondoControlador(); $fondos->MostrarFondos(); break;
      case 'actualizar_fondos':require 'controladores/fondo.controlador.php';
        $fondos = new FondoControlador(); $fondos->ActualizarFondos(); break;
      case 'cargar_fondos': require 'controladores/fondo.controlador.php';
        $fondos = new FondoControlador(); $fondos->CargarFondos(); break;

      #COLECCIONES
      case 'colecciones':require 'controladores/coleccion.controlador.php';
        $colecciones = new ColeccionControlador(); $colecciones->FrmColecciones(); break;
      case 'mostrar_colecciones':require 'controladores/coleccion.controlador.php';
        $colecciones = new ColeccionControlador(); $colecciones->MostrarColecciones(); break;
      case 'actualizar_colecciones':require 'controladores/coleccion.controlador.php';
        $colecciones = new ColeccionControlador(); $colecciones->ActualizarColecciones(); break;
      case 'cargar_colecciones':require 'controladores/coleccion.controlador.php';
        $colecciones = new ColeccionControlador(); $colecciones->CargarColecciones(); break;

      #MATERIALES DOCUMENTALES
      case 'materialesdocumentales':require 'controladores/materialdocumental.controlador.php';
        $materialesdocumentales = new MaterialDocumentalControlador(); $materialesdocumentales->FrmMaterialDocumental(); break;
      case 'mostrar_materialesdocumentales':require 'controladores/materialdocumental.controlador.php';
        $materialesdocumentales = new MaterialDocumentalControlador(); $materialesdocumentales->MostrarMaterialDocumental(); break;
      case 'actualizar_materialesdocumentales':require 'controladores/materialdocumental.controlador.php';
        $materialesdocumentales = new MaterialDocumentalControlador(); $materialesdocumentales->ActualizarMaterialDocumental(); break;
      case 'cargar_materialesdocumentales':require 'controladores/materialdocumental.controlador.php';
        $materialesdocumentales = new MaterialDocumentalControlador(); $materialesdocumentales->CargarMaterialDocumental(); break;

      #MATERIAL SOPORTE
      case 'materialessoporte':require 'controladores/materialsoporte.controlador.php';
        $materialessoporte = new MaterialSoporteControlador(); $materialessoporte->FrmMaterialesSoporte(); break;
      case 'mostrar_materialessoporte':require 'controladores/materialsoporte.controlador.php';
        $materialessoporte = new MaterialSoporteControlador(); $materialessoporte->MostrarMaterialesSoporte(); break;
      case 'actualizar_materialessoporte':require 'controladores/materialsoporte.controlador.php';
        $materialessoporte = new MaterialSoporteControlador(); $materialessoporte->ActualizarMaterialesSoporte(); break;
      case 'cargar_materialessoporte':require 'controladores/materialsoporte.controlador.php';
        $materialessoporte = new MaterialSoporteControlador(); $materialessoporte->CargarMaterialesSoporte(); break;

      #ARCHIVOS
      case 'archivos':require 'controladores/archivo.controlador.php';
        $archivos = new ArchivoControlador(); $archivos->FrmArchivos(); break;
      case 'mostrar_archivos':require 'controladores/archivo.controlador.php';
        $archivos = new ArchivoControlador(); $archivos->MostrarArchivos(); break;
      case 'actualizar_archivos':require 'controladores/archivo.controlador.php';
        $archivos = new ArchivoControlador(); $archivos->ActualizarArchivos(); break;
      case 'cargar_archivos':require 'controladores/archivo.controlador.php';
        $archivos = new ArchivoControlador(); $archivos->CargarArchivos(); break;

      #SECCIONES
      case 'secciones':require 'controladores/seccion.controlador.php';
        $secciones = new SeccionControlador(); $secciones->FrmSecciones(); break;
      case 'mostrar_secciones':require 'controladores/seccion.controlador.php';
        $secciones = new SeccionControlador(); $secciones->MostrarSecciones(); break;
      case 'actualizar_secciones':require 'controladores/seccion.controlador.php';
        $secciones = new SeccionControlador(); $secciones->ActualizarSecciones(); break;
      case 'cargar_secciones':require 'controladores/seccion.controlador.php';
        $secciones = new SeccionControlador(); $secciones->CargarSecciones(); break;

      #NIVELES
      case 'niveles':require 'controladores/nivel.controlador.php';
        $niveles = new NivelControlador(); $niveles->FrmNiveles(); break;
      case 'mostrar_niveles':require 'controladores/nivel.controlador.php';
        $niveles = new NivelControlador(); $niveles->MostrarNiveles(); break;
      case 'actualizar_niveles':require 'controladores/nivel.controlador.php';
        $niveles = new NivelControlador(); $niveles->ActualizarNiveles(); break;
      case 'cargar_niveles':require 'controladores/nivel.controlador.php';
        $niveles = new NivelControlador(); $niveles->CargarNiveles(); break;

      #CONTENEDORES
      case 'contenedores':require 'controladores/contenedor.controlador.php';
        $contenedores = new ContenedorControlador(); $contenedores->FrmContenedores(); break;
      case 'mostrar_contenedores':require 'controladores/contenedor.controlador.php';
        $contenedores = new ContenedorControlador(); $contenedores->MostrarContenedores(); break;
      case 'actualizar_contenedores':require 'controladores/contenedor.controlador.php';
        $contenedores = new ContenedorControlador(); $contenedores->ActualizarContenedores(); break;
      case 'cargar_contenedores':require 'controladores/contenedor.controlador.php';
        $contenedores = new ContenedorControlador(); $contenedores->CargarContenedores(); break;

        #IDIOMAS
        case 'idiomas':require 'controladores/idioma.controlador.php';
          $idiomas = new IdiomaControlador(); $idiomas->FrmIdiomas(); break;
        case 'mostrar_idiomas':require 'controladores/idioma.controlador.php';
          $idiomas = new IdiomaControlador(); $idiomas->MostrarIdiomas(); break;
        case 'actualizar_idiomas':require 'controladores/idioma.controlador.php';
          $idiomas = new IdiomaControlador(); $idiomas->ActualizarIdiomas(); break;
        case 'cargar_idiomas':require 'controladores/idioma.controlador.php';
          $idiomas = new IdiomaControlador(); $idiomas->CargarIdiomas(); break;

      #TOPONIMIA
        case 'toponimia':require 'controladores/toponimia.controlador.php';
          $toponimia = new ToponimiaControlador(); $toponimia->FrmToponimia(); break;
        case 'mostrar_toponimia':require 'controladores/toponimia.controlador.php';
          $toponimia = new ToponimiaControlador(); $toponimia->MostrarToponimia(); break;
        case 'actualizar_toponimia':require 'controladores/toponimia.controlador.php';
          $toponimia = new ToponimiaControlador(); $toponimia->ActualizarToponimia(); break;
        case 'cargar_toponimia':require 'controladores/toponimia.controlador.php';
          $toponimia = new ToponimiaControlador(); $toponimia->CargarToponimia(); break;

      #FORMATOS
      case 'formatos':require 'controladores/formato.controlador.php';
        $formatos = new FormatoControlador(); $formatos->FrmFormatos(); break;
      case 'mostrar_formatos':require 'controladores/formato.controlador.php';
        $formatos = new FormatoControlador(); $formatos->MostrarFormatos(); break;
      case 'actualizar_formatos':require 'controladores/formato.controlador.php';
        $formatos = new FormatoControlador(); $formatos->ActualizarFormatos(); break;
      case 'cargar_formatos':require 'controladores/formato.controlador.php';
        $formatos = new FormatoControlador(); $formatos->CargarFormatos(); break;

      #TIPOS DE MATERIALES DE SOPORTE
      case 'tiposmaterialessoporte':require 'controladores/tipomaterialsoporte.controlador.php';
        $tiposmaterialessoporte = new TipoMaterialSoporteControlador(); $tiposmaterialessoporte->FrmTMS(); break;
      case 'mostrar_tiposmaterialessoporte':require 'controladores/tipomaterialsoporte.controlador.php';
        $tiposmaterialessoporte = new TipoMaterialSoporteControlador(); $tiposmaterialessoporte->MostrarTMS(); break;
      case 'actualizar_tiposmaterialessoporte':require 'controladores/tipomaterialsoporte.controlador.php';
        $tiposmaterialessoporte = new TipoMaterialSoporteControlador(); $tiposmaterialessoporte->ActualizarTMS(); break;
      case 'cargar_tiposmaterialessoporte':require 'controladores/tipomaterialsoporte.controlador.php';
        $tiposmaterialessoporte = new TipoMaterialSoporteControlador(); $tiposmaterialessoporte->CargarTMS(); break;

        #PERSONAJES
        case 'personajesgeneradores':require 'controladores/personajegenerador.controlador.php';
          $personajesgeneradores = new PersonajeGeneradorControlador(); $personajesgeneradores->FrmPersonajesGeneradores(); break;
        case 'mostrar_personajesgeneradores':require 'controladores/personajegenerador.controlador.php';
          $personajesgeneradores = new PersonajeGeneradorControlador(); $personajesgeneradores->MostrarPersonajesGeneradores(); break;
        case 'actualizar_personajesgeneradores':require 'controladores/personajegenerador.controlador.php';
          $personajesgeneradores = new PersonajeGeneradorControlador(); $personajesgeneradores->ActualizarPersonajesGeneradores(); break;
        case 'cargar_personajesgeneradores':require 'controladores/personajegenerador.controlador.php';
          $personajesgeneradores = new PersonajeGeneradorControlador(); $personajesgeneradores->CargarPersonajesGeneradores(); break;
        case 'cargar_personajes':require 'controladores/personajegenerador.controlador.php';
          $personajesgeneradores = new PersonajeGeneradorControlador(); $personajesgeneradores->CargarPersonajes(); break;
        case 'cargar_generadores':require 'controladores/personajegenerador.controlador.php';
          $personajesgeneradores = new PersonajeGeneradorControlador(); $personajesgeneradores->CargarGeneradores(); break;

      #OTROS
      case 'cargar_inicio_usuario':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarInicio(); break;
      case 'cargar_paises':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarPaises(); break;
      case 'cargar_provincias':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarProvincias(); break;
      case 'cargar_cantones':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarCantones(); break;
      case 'cargar_parroquias':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarParroquias(); break;
      case 'cargar_ocupaciones':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarOcupaciones(); break;
      case 'cargar_estadocivil':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarEstadoCivil(); break;
      case 'cargar_roles':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarRoles(); break;
      case 'cargar_estadosconservacion':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->CargarEstadosConservacion(); break;
      case 'mostrar_errores':require 'controladores/otros.controlador.php';
        $otros = new OtrosControlador(); $otros->MostrarErrores(); break;

        #LINEAS INTERES
        case 'lineasinteres':require 'controladores/lineainteres.controlador.php';
          $lineasinteres = new LineaInteresControlador(); $lineasinteres->FrmLineasInteres(); break;
        case 'mostrar_lineasinteres':require 'controladores/lineainteres.controlador.php';
          $lineasinteres = new LineaInteresControlador(); $lineasinteres->MostrarLineasInteres(); break;
        case 'actualizar_lineasinteres':require 'controladores/lineainteres.controlador.php';
          $lineasinteres = new LineaInteresControlador(); $lineasinteres->ActualizarLineasInteres(); break;
        case 'cargar_lineasinteres':require 'controladores/lineainteres.controlador.php';
          $lineasinteres = new LineaInteresControlador(); $lineasinteres->CargarLineasInteres(); break;

        #Documentos Historicos
        case 'documentos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->FrmDocumentos(); break;
        case 'mostrar_documentos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->MostrarDocumentos(); break;
        case 'ver_detalle_documento':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->VerDetalleDocumento(); break;
        case 'ver_documento_modificar':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->VerDocumentoModificar(); break;
        case 'actualizar_documentos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->ActualizarDocumentos(); break;
        case 'cargar_documentos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->CargarDocumentos(); break;
        case 'subir_archivos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->SubirArchivos(); break;
        case 'eliminar_archivos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->EliminarArchivos(); break;
        case 'listar_documentos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->ListarDocumentosPaginados(); break;
        case 'cargar_libro':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->CargarLibro(); break;
        case 'buscar_documentos':require 'controladores/documento.controlador.php';
          $documentos = new DocumentoControlador(); $documentos->BuscarDocumentos(); break;


        #Favoritos
        case 'favoritos':require 'controladores/favorito.controlador.php';
          $favoritos = new FavoritoControlador(); $favoritos->FrmFavoritos(); break;
        case 'mostrar_favoritos':require 'controladores/favorito.controlador.php';
          $favoritos = new FavoritoControlador(); $favoritos->MostrarFavoritos(); break;
        case 'actualizar_favoritos':require 'controladores/favorito.controlador.php';
          $favoritos = new FavoritoControlador(); $favoritos->ActualizarFavoritos(); break;
        case 'cargar_favoritos':require 'controladores/favorito.controlador.php';
          $favoritos = new FavoritoControlador(); $favoritos->CargarFavoritos(); break;

        #pendientes
        case 'pendientes':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->FrmPendientes(); break;
        case 'mostrar_pendientes':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->MostrarPendientes(); break;
        case 'ver_detalle_pendientes':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->VerDetallePendiente(); break;
        case 'aprobar_documentos':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->AprobarDocumentos(); break;
        case 'reprobar_documentos':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->ReprobarDocumentos(); break;
        case 'actualizar_pendientes':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->ActualizarPendientes(); break;
        case 'cargar_pendientes':require 'controladores/pendiente.controlador.php';
          $pendientes = new PendienteControlador(); $pendientes->CargarPendientes(); break;
            
        #filtrar Documentos
        case 'mostrar_documentos_relevantes':require 'controladores/buscador_moderno.controlador.php';
        $filtro = new BuscadorControlador(); $filtro->MostrarDocumentosRelevantes(); break;
        
        case 'filtrar_documentos':require 'controladores/buscador_moderno.controlador.php';
        $filtro = new BuscadorControlador(); $filtro->ListarDocumentos(); break;
         break;
         
      default:
        require 'controladores/inicio.controlador.php';
        $inicio = new InicioControlador(); $inicio->Inicio();
      break;
    }
  }
}else{
  require 'controladores/inicio.controlador.php';
  $inicio = new InicioControlador(); $inicio->Inicio();
}
?>
