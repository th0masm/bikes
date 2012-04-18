/**
 * 
 * Fichero de carga propio para el proyecto Bikes
 * 
 */





/**
 * Ponemos el datepicker en castellano
 */

jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '<Ant',
		nextText: 'Sig>',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: '',
	    changeMonth: true};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});



/**
 * Muestra un datapicker para las fechas en los campos en los que lo solicitemos.
 * 
 * Cambiar #datepicker por el id del campo del formulario en el que queremos mostrar el datepicker.
 * $(document).ready(function() { $("#datepicker").datepicker(); });
 */
	$(document).ready(function() {
		$("#dtRiderBirth").datepicker();
		$("#dtRaceDate").datepicker();
	});