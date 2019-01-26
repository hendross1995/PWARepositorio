import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 
(function(){
	$('.__a').html('<i class="fa fa-cog fa-spin"></i>')
    fetch('cargar_inicio_usuario') 
 	.then(response => response.json())
 	.then(data => {
 		if(data[0].estado){
 			$('.cantidad_usuarios').html(data[0].cantidad_usuarios)
      		$('.cantidad_favoritos').html(data[0].cantidad_favoritos)
      		$('.cantidad_documentos').html(data[0].cantidad_documentos)
      		$('.cantidad_pendientes').html(data[0].cantidad_pendientes)
      		if(data[3]){
      			Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
				Chart.defaults.global.defaultFontColor = '#292b2c';
				var ctx = document.getElementById("myAreaChart");
				var myLineChart = new Chart(ctx, {
				  type: 'pie',
				  data: {
				    labels: data[1],
				   	datasets: [{
				        label: "Population (millions)",
				        fill: true,
				        backgroundColor: [dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors()],
				        data: data[2]
				      }]
				  },
				  options: {
				    title: {
				        display: false,
				        text: 'Últimos 10 documentos históricos más vistos:'
				    },
				    responsive: true,
				    legend: {
					    position: 'right',
					},
				   }
				});
      		}
      	}else{
   			$('.__a').html('')
   			avisoFormulario({aviso: "cargarInicio", mensaje: data[0].observacion,alerta:"text-warning"})
   		}
   	})
  	.catch(error => {
        $('.__a').html('')
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
  	});
})()

var dynamicColors = function() {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return "rgb(" + r + "," + g + "," + b + ")";
 };



 



