<?php

Route::get('/', 'HomeController@index')->name('home');
Auth::routes();


/**
 * Rutas del adminsitrador
 */
Route::prefix('admin')->middleware('auth')->group(function(){
    Route::prefix('usuarios')->group(function(){
        Route::get('/', 'UserController@index')->name('usuarios');
        Route::get('crear', 'UserController@create')->name('crearusuario');
        Route::post('nuevo', 'UserController@store')->name('guardausuario');
        Route::get('ver/{id}', 'UserController@edit')->name('verusuario');
        Route::post('guardar', 'UserController@update')->name('actualizausuario');
        Route::get('eliminar/{id}', 'UserController@destroy')->name('borrausuario');
        Route::get('acceso/{id}', 'UserController@changePassword')->name('cambiapassword');
        Route::post('password', 'UserController@updatePassword')->name('guardanuevopassword');
    });

    Route::prefix('dashboard')->group(function(){
        Route::get('/', 'UserController@home')->name('home');
    });
});

/**
 * Rutas para la gente de staff que revisa la información enviada desde las app moviles
 */
Route::prefix('staff')->middleware(['auth'])->group(function(){
    /**
     * Rutas para la seccion de tierra
     */
    Route::prefix('tierra')->group(function(){
        Route::post('subcategorias', 'StaffController@subcategoriasTierra')->name('staffSubcategoriasTierra');
        Route::post('reclasifica', 'StaffController@tierraReclasifica')->name('staffTierraReclasifica');
        Route::get('index/{rechazado?}', 'StaffController@tierraIndex')->name('staffTierraIndex');
        Route::get('detalle', 'StaffController@tierraDetalles')->name('staffTierraDetalles');
        Route::get('detalleRechaza', 'StaffController@tierraDetalleRechaza')->name('staffTierraDetalleRechaza');
        Route::get('completar/{id?}', 'StaffController@tierraCompletar')->name('staffTierraCompletar');
        Route::post('completar', 'StaffController@tierraGuardaCambios')->name('staffTierraGuardar');
        Route::post('rechazar', 'StaffController@tierraRechazar')->name('staffTierraRechazar');
        Route::get('contabiliza', 'StaffController@tierraContabilizaGral')->name('staffTierraContabiliza');
        Route::post('municipios', 'StaffController@municipiosTierra')->name('staffMunicipios');
    });
    /**
     * Rutas para la seccion de eventos
     */
    Route::prefix('evento')->group(function(){
        //Obtiene las subcategorias
        Route::post('subcategorias', 'StaffController@subcategoriasEvento')->name('staffSubcategoriasEvento');
        //Reclasifica un reporte
        Route::post('reclasifica', 'StaffController@eventoReclasifica')->name('staffEventoReclasifica');
        //Muestra los eventos
        Route::get('index/{rechazado?}', 'StaffController@eventoIndex')->name('staffEventoIndex');
        //Muestra el detalle de los eventos en el index
        Route::get('detalle', 'StaffController@eventoDetalles')->name('staffEventoDetalles');
        //Muestra toda la informacion del evento para completar los datos
        Route::get('completar/{id?}', 'StaffController@eventoCompletar')->name('staffEventoCompletar');
        //Obtiene los datos de reporte de una categoria
        Route::get('datosCategoria', 'StaffController@eventoDetalleCategoria')->name('staffEventoDetalleCategoria');
        //Guarda los datos generales de un evento
        Route::post('salvaGenerales', 'StaffController@guardaEventoGenerales')->name('staffEventoGuardaGenerales');
        //Guarda los datos de un reporte de un evento
        Route::post('salvaCategoriaDetalles', 'StaffController@guardaEventoDetalle')->name('staffEventoGuardaDetalles');
        //Manda revisar el evento
        Route::post('mandaRevisar', 'StaffController@mandaEventoRevisar')->name('staffEventoRevisar');
        //Rechaza el evento
        Route::post('rechaza', 'StaffController@rechazaEvento')->name('staffEventoRechaza');
    });
});

Route::prefix('digital')->middleware(['auth'])->group(function(){
    /**
     * Rutas para la seccion de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Rechaza un reporte al equipo de staff
        Route::post('guardaTierra', 'DigitalController@tierraGuardar')->name('digitalTierraGuarda');
    });
    /**
     * Rutas para la seccion de eventos
     */
    Route::prefix('evento')->group(function(){
        //Muestra todos los eventos enviados
        Route::get('/', 'DigitalController@eventoIndex')->name('digitalEventoIndex');

        //Muestra el detalle del evento
        Route::get('detalle/{id}', 'DigitalController@eventoDetalle')->name('digitalEventoDetalles');
        //Nuevo Evento
        Route::get('nuevo', 'DigitalController@eventoNuevo')->name('digitalNuevoEvento');

        Route::get('nuevoreporte', 'DigitalController@reporteNuevo')->name('digitalNuevoReporte');

        Route::post('salvaNuevoEvento', 'DigitalController@eventoGuardaNuevo')->name('digitalSalvaNuevoEvento');
        //Muestra los datos de un reporte
        Route::get('datosCategoria', 'DigitalController@eventoDetalleCategoria')->name('digitalEventoDetalleCategoria');
        //Guarda la informacion de un reporte
        Route::post('salvaCategoriaDetalles', 'DigitalController@guardaEventoDetalle')->name('digitalEventoGuardaDetalles');
        //Guarda elementos adicionales que agrega al evento
        Route::post('elementoAdicional', 'DigitalController@agregaElementoAdicional')->name('digitalEventoAdicional');
        //Aprueba el evento para su contabilizacion
        Route::post('apruebaEvento', 'DigitalController@aprobarEvento')->name('digitalEventoAprueba');
        //Rechaza un evento y lo manda al staff
        Route::post('rechazaEvento', 'DigitalController@rechazaEvento')->name('digitalEventoRechaza');
        //Guarda el precio de la sede del evento
        Route::post('precioSede', 'DigitalController@asignaPrecioSedeEvento')->name('digitalEventoPrecioSede');
        //Guarda elementos adicionales que agrega al evento
        Route::post('elementoAdicional', 'DigitalController@agregaElementoAdicional')->name('digitalEventoAdicional');
        //No se
        Route::post('municipios', 'DigitalController@municipiosTierra')->name('digitalMunicipios');

        Route::post('subcategoriaEventos', 'Digitalcontroller@subcategoriasEvento')->name('digitalSubcategoriasEventos');
    });

});

/**
 * Rutas para los coordinadores que aprueban
 */
Route::prefix('coordinador')->middleware(['auth'])->group(function(){
    //Route::get('precios', 'CoordinadorController@obtienePrecios')->name('coordinadorObtienePrecios');
    /**
     * Rutas para la seccion de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Muestra los reportes enviados
        Route::get('/', 'CoordinadorController@tierraIndex')->name('coordinadorTierraIndex');
        //Muestra el detalle de un reporte
        Route::get('detalle/{id}', 'CoordinadorController@tierraDetalles')->name('coordinadorTierraDetalles');
        //Aprueba el reporte para su contabilizacion
        Route::post('detalle', 'CoordinadorController@tierraGuardar')->name('coordinadorTierraGuardar');
        //Rechaza un reporte al equipo de staff
        Route::post('rechaza', 'CoordinadorController@tierraRechaza')->name('coordinadorTierraRechaza');
    });
    /**
     * Rutas para la seccion de eventos
     */
    Route::prefix('evento')->group(function(){
        //Muestra todos los eventos enviados
        Route::get('/', 'CoordinadorController@eventoIndex')->name('coordinadorEventoIndex');
        //Muestra el detalle del evento
        Route::get('detalle/{id}', 'CoordinadorController@eventoDetalle')->name('coordinadorEventoDetalles');
        //Muestra los datos de un reporte
        Route::get('datosCategoria', 'CoordinadorController@eventoDetalleCategoria')->name('coordinadorEventoDetalleCategoria');
        //Guarda la informacion de un reporte
        Route::post('salvaCategoriaDetalles', 'CoordinadorController@guardaEventoDetalle')->name('coordinadorEventoGuardaDetalles');
        //Aprueba el evento para su contabilizacion
        Route::post('apruebaEvento', 'CoordinadorController@aprobarEvento')->name('coordinadorEventoAprueba');
        //Rechaza un evento y lo manda al staff
        Route::post('rechazaEvento', 'CoordinadorController@rechazaEvento')->name('coordinadorEventoRechaza');
        //Guarda el precio de la sede del evento
        Route::post('precioSede', 'CoordinadorController@asignaPrecioSedeEvento')->name('coordinadorEventoPrecioSede');
        //Guarda elementos adicionales que agrega al evento
        Route::post('elementoAdicional', 'CoordinadorController@agregaElementoAdicional')->name('coordinadorEventoAdicional');
    });

});

/**
 * Rutas para los coordinadores que aprueban
 */
Route::prefix('fiscaliza')->middleware(['auth'])->group(function(){
    //Route::get('precios', 'CoordinadorController@obtienePrecios')->name('coordinadorObtienePrecios');
    /**
     * Rutas para la seccion de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Muestra los reportes enviados
        Route::get('/', 'FiscaController@tierraIndex')->name('fiscaTierraIndex');
        //Muestra el detalle de un reporte
        Route::get('detalle/{id}', 'FiscaController@tierraDetalles')->name('fiscaTierraDetalles');
        //Aprueba el reporte para su contabilizacion
        Route::post('detalle', 'FiscaController@tierraGuardar')->name('fiscaTierraGuardar');
        //Rechaza un reporte al equipo de staff
        Route::post('rechaza', 'FiscaController@tierraRechaza')->name('fiscaTierraRechaza');
    });
    /**
     * Rutas para la seccion de eventos
     */
    Route::prefix('evento')->group(function(){
        //Muestra todos los eventos enviados
        Route::get('/', 'FiscaController@eventoIndex')->name('fiscaEventoIndex');
        //Muestra el detalle del evento
        Route::get('detalle/{id}', 'FiscaController@eventoDetalle')->name('fiscaEventoDetalles');
        //Muestra los datos de un reporte
        Route::get('datosCategoria', 'FiscaController@eventoDetalleCategoria')->name('fiscaEventoDetalleCategoria');
        //Guarda la informacion de un reporte
        Route::post('salvaCategoriaDetalles', 'FiscaController@guardaEventoDetalle')->name('fiscaEventoGuardaDetalles');
        //Aprueba el evento para su contabilizacion
        Route::post('apruebaEvento', 'FiscaController@aprobarEvento')->name('fiscaEventoAprueba');
        //Rechaza un evento y lo manda al staff
        Route::post('rechazaEvento', 'FiscaController@rechazaEvento')->name('fiscaEventoRechaza');
        //Guarda el precio de la sede del evento
        Route::post('precioSede', 'FiscaController@asignaPrecioSedeEvento')->name('fiscaEventoPrecioSede');
        //Guarda elementos adicionales que agrega al evento
        Route::post('elementoAdicional', 'FiscaController@agregaElementoAdicional')->name('fiscaEventoAdicional');
    });

});


/**
 * Rutas del consultor, revisa solo la informacion aprobada por el coordinador
 */
Route::prefix('consultor')->middleware('auth')->group(function(){
    /**
     * Rutas de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Muestra los elementos de tierra aprobados
        Route::get('/', 'ConsultorController@indexTierra')->name('consultorTierraIndex');
        //Filtra los elementos
        Route::post('/', 'ConsultorController@indexTierraFiltro')->name('consultorTierraIndexFiltrado');
        //Muestra el detalle de un reporte
        Route::get('detalle/{id}', 'ConsultorController@tierraDetalles')->name('consultorTierraDetalles');
        //Muestra los respaldos generados
        Route::get('respaldos', 'ConsultorController@respaldoTierraIndex')->name('consultorTierraRespaldos');
    });
    /**
     * Rutas de eventos
     */
    Route::prefix('evento')->group(function(){
        //Muestra los eventos guardados
        Route::get('/', 'ConsultorController@indexEvento')->name('consultorEventoIndex');
        //Filtra los eventos
        Route::post('/', 'ConsultorController@indexEvento')->name('consultorEventoIndexFiltro');
        //Muestra el detalle del evento
        Route::get('detalle/{id}', 'ConsultorController@eventoDetalles')->name('consultorEventoDetalles');
        //Carga el detalle de un reporte del evento
        Route::get('datosCategoria', 'ConsultorController@eventoDetalleCategoria')->name('consultorEventoDetalleCategoria');
        //Muestra los respaldos generados
        Route::get('respaldos', 'ConsultorController@respaldoEventoIndex')->name('consultorEventoRespaldos');
    });
    //Permite realizar la exportacion y respaldo de la informacion del dia
    Route::post('exportar', 'ConsultorController@exportar')->name('consultorExportar');

    /**
     * Rutas para la generación de reportes
     */
    Route::prefix('reportes')->group(function(){
        Route::get('/', 'ConsultorController@indexReportes')->name('consultorReportesIndex');
        Route::get('eventoPdf/{id}', 'ConsultorController@eventoPdf')->name('consultorReportesEventoPdf');
    });
});


$router->group(['prefix' => 'mexa'], function() use($router){
    $router->get('/lista', ['uses' => 'MexaController@generarToken']);
    $router->post('/insertar', ['uses' => 'MexaController@insertDataMexa']);
});
