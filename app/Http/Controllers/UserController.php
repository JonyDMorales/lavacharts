<?php

namespace App\Http\Controllers;

use App\FiltradoTierra;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use App\FiltradoEventos;
use App\Http\Requests\UserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;
use Khill\Lavacharts\Lavacharts;

class UserController extends Controller{

    public function __construct(){
        $this->middleware('auth');
        Date::setLocale('es_MX');
    }

    public function index(){
        $usuarios = User::where('active', true)
        ->where('web', true)
        ->orderBy('name', 'asc')->get();

        return view('admin.usuarios.index')
            ->with('usuarios', $usuarios);
    }

    public function create(Request $request){
        if($request->isMethod('get')){
            return view('admin.usuarios.create');
        }else{
            return view('shared.complete.404')
                ->with('mensaje','Metodo no aceptado');
        }
    }

    public function store(UserRequest $request){
        if($request->isMethod('post')) {
            $user = new User($request->all());
            $user->name = ucwords($request->name);
            $user->active = (boolean)$request->input('active', false);
            $user->circunscripcion = (int)$request->input('circunscripcion', 1);
            $user->password = bcrypt($request->password);
            $user->web = true;
            $user->estado_id = (int)$this->getStateId($user->estado);
            if($user->save()) {
                return view('shared.complete.200')
                    ->with('mensaje', 'Usuario creado')
                    ->with('destino', 'usuarios');
            }else{
                return view('shared.complete.404')
                    ->with('mensaje','No se creo el usuario');
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje','Metodo no aceptado');
        }
    }

    public function edit(Request $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            return view('admin.usuarios.view')
                ->with('usuario', $user);
        }catch(ModelNotFoundException $e)
        {
            return view('shared.complete.404')
                ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request)
    {
        if($request->isMethod('post'))
        {
            try{
                $id = $request->input('id', null);
                $user = User::findOrFail($id);
                $user->name = ucwords($request->input('name', ''));
                $user->email = $request->input('email', '');
                $user->active = $request->input('active', false);
                $user->perfil = $request->input('perfil', 'staff');
                $user->circunscripcion = $request->input('circunscripcion', 1);
                $user->estado = $request->input('estado', '');
                $user->estado_id = $this->getStateId($user->estado);
                if($user->save())
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'Usuario actualizado')
                        ->with('destino', 'usuarios');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'Usuario no actualizado');
                }

            }catch(ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }

    }
    /**
     * Function to delete a user
     * @param Request $request
     * @param $id
     */
    public function destroy(Request $request, $id)
    {
        if($request->isMethod('get'))
        {
            try{
                $user = User::findOrFail($id);
                $user->active = false;
                $user->save();
                if( $user->delete() )
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'Usuario Eliminado')
                        ->with('destino', 'usuarios');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'El usuario no fue eliminado. ');
                }
            }catch(ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    public function changePassword(Request $request, $id)
    {
        if($request->isMethod('get'))
        {
            try{
                $user = User::findOrFail($id);
                return view('admin.usuarios.changepass')
                    ->with('usuario', $user);
            }catch(ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    public function updatePassword(UserChangePasswordRequest $request)
    {
        if($request->isMethod('post'))
        {
            $newpassword = $request->input('newpassword', '');
            $id = $request->input('id', null);
            try{
                $user = User::findOrFail($id);
                $user->password = bcrypt($newpassword);
                if($user->save())
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'Contraseña de acceso actualizada')
                        ->with('destino', 'usuarios');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'No se actualizó la contraseña');
                }
            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }

        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    protected function getStateId($state) : string{
        $result = '';
        switch ($state)
        {
            case 'Aguascalientes':
                $result = '01';
                break;
            case 'Baja California':
                $result = '02';
                break;
            case 'Baja California Sur':
                $result = '03';
                break;
            case 'Campeche':
                $result = '04';
                break;
            case 'Coahuila de Zaragoza':
                $result = '05';
                break;
            case 'Colima':
                $result = '06';
                break;
            case 'Chiapas':
                $result = '07';
                break;
            case 'Chihuahua':
                $result = '08';
                break;
            case 'Ciudad de Mexico':
                $result = '09';
                break;
            case 'Durango':
                $result = '10';
                break;
            case 'Guanajuato':
                $result = '11';
                break;
            case 'Guerrero':
                $result = '12';
                break;
            case 'Hidalgo':
                $result = '13';
                break;
            case 'Jalisco':
                $result = '14';
                break;
            case 'Estado de Mexico':
                $result = '15';
                break;
            case 'Michoacan de Ocampo':
                $result = '16';
                break;
            case 'Morelos':
                $result = '17';
                break;
            case 'Nayarit':
                $result = '18';
                break;
            case 'Nuevo Leon':
                $result = '19';
                break;
            case 'Oaxaca':
                $result = '20';
                break;
            case 'Puebla':
                $result = '21';
                break;
            case 'Queretaro':
                $result = '22';
                break;
            case 'Quintana Roo':
                $result = '23';
                break;
            case 'San Luis Potosi':
                $result = '24';
                break;
            case 'Sinaloa':
                $result = '25';
                break;
            case 'Sonora':
                $result = '26';
                break;
            case 'Tabasco':
                $result = '27';
                break;
            case 'Tamaulipas':
                $result = '28';
                break;
            case 'Tlaxcala':
                $result = '29';
                break;
            case 'Veracruz de Ignacio de la Llave':
                $result = '30';
                break;
            case 'Yucatan':
                $result = '31';
                break;
            case 'Zacatecas':
                $result = '32';
                break;
            default:
                $result='No se encontro estado';
                break;
        }
        return $result;
    }


    /*
     * Dashboard
     */

    function home(){
        $eventosMORENA = $this->eventosConteoGasto('/.*MORENA|PT|PES/i');
        $eventosPRI = $this->eventosConteoGasto('/.*PRI|PVEM|PANAL/i');
        $eventosPAN = $this->eventosConteoGasto('/.*PAN|PRD|MC/i');

        $eventosGasto = new Lavacharts;
        $eventosGastoGrafica = $eventosGasto->DataTable();

        $eventosGastoGrafica->addStringColumn('Partido')
            ->addNumberColumn('Gasto')
            ->addRow(['MORENA-PT-PES', $eventosMORENA['precio']])
            ->addRow(['PRI-PVEM-PANAL', $eventosPRI['precio']])
            ->addRow(['PAN-PRD-MC', $eventosPAN['precio']]);

        $eventosGasto= \Lava::DonutChart('Gasto de eventos', $eventosGastoGrafica, ['title' => 'Gasto de eventos',
            'pieHole' => 0.40,
            //'legend' => [ 'position' => 'top'],
            'colors' => ['#B3282B', '#008F36', '#063383'],
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosConteo = new Lavacharts;

        $eventosConteoGrafica  = $eventosConteo->DataTable();

        $eventosConteoGrafica->addStringColumn('Partido')
            ->addNumberColumn('Cantidad de eventos')
            ->addRoleColumn('string', 'style')
            ->addRow(['MORENA-PT-PES', $eventosMORENA['conteo'], 'color:#B3282B'])
            ->addRow(['PRI-PVEM-PANAL', $eventosPRI['conteo'],'color:#008F36'])
            ->addRow(['PAN-PRD-MC', $eventosPAN['conteo'], 'color:#063383']);

        $eventosConteo = \Lava::BarChart('Total de eventos', $eventosConteoGrafica, [ 'title' => 'Conteo de eventos',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'fontSize' => 10,
            'height' => 300]);

        $tierraMORENA = $this->tierraConteoGasto('/.*MORENA|PT|PES/i');
        $tierraPRI = $this->tierraConteoGasto('/.*PRI|PVEM|PANAL/i');
        $tierraPAN = $this->tierraConteoGasto('/.*PAN|PRD|MC/i');

        $tierraGasto = new Lavacharts;

        $tierraGastoGrafica  = $tierraGasto->DataTable();

        $tierraGastoGrafica->addStringColumn('Partido')
            ->addNumberColumn('Gasto de tierra')
            ->addRoleColumn('string', 'style')
            ->addRow(['MORENA-PT-PES', $tierraMORENA['precio'], 'color:#B3282B'])
            ->addRow(['PRI-PVEM-PANAL', $tierraPRI['precio'],'color:#008F36'])
            ->addRow(['PAN-PRD-MC', $tierraPAN['precio'], 'color:#063383']);

        $tierraGasto = \Lava::ColumnChart('Gasto de tierra', $tierraGastoGrafica, [ 'title' => 'Gasto de tierra',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'fontSize' => 12,
            'height' => 300]);

        $tierraGastoCategorias = new Lavacharts;

        $tierraCategoriasGrafica  = $tierraGastoCategorias->DataTable();

        $tierraCategoriasGrafica->addStringColumn('Partido')
            ->addNumberColumn('Movil')
            ->addNumberColumn('Fija')
            ->addRoleColumn('string', 'style')
            ->addRoleColumn('string', 'style')
            ->addRow(['MORENA-PT-PES', $tierraMORENA['movil'], $tierraMORENA['fija'], 'color:#B3282B', 'color:#B3282B'])
            ->addRow(['PRI-PVEM-PANAL', $tierraPRI['movil'], $tierraPRI['fija'],'color:#008F36'])
            ->addRow(['PAN-PRD-MC', $tierraPAN['movil'], $tierraPAN['fija'], 'color:#063383']);

        $tierraGastoCategorias = \Lava::ColumnChart('Gasto de categorías', $tierraCategoriasGrafica, [ 'title' => 'Gasto de movil y fija',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'fontSize' => 12,
            'height' => 300]);

        return view('admin.dashboard.home',
            ['eventosGasto' => $eventosGasto],
            ['eventosConteo' => $eventosConteo],
            ['tierraGasto' => $tierraGasto],
            ['tierraCategorias' => $tierraGastoCategorias]);
    }

    function eventosConteoGasto($partidos){
        $gasto = array( 'precio' => 0, 'conteo' => 0);
        try{
            $precios = FiltradoEventos::project([ 'precio' => 1])->where('partido', 'regex', $partidos)->get();
            $gasto['conteo'] = $precios->count();
            foreach ($precios as $precio){
                $gasto['precio'] += $precio->precio;
            }
        } catch (ModelNotFoundException $e) {
            return $gasto;
        }

        return $gasto;
    }

    function tierraConteoGasto($partidos){
        $gasto = array( 'precio' => 0, 'movil' => 0, 'fija' => 0);
        try{
            $precios = FiltradoTierra::project(['precio' => 1, 'categoria' => 1])->where('partido', 'regex', $partidos)->get();
            foreach ($precios as $precio){
                $gasto['precio'] += $precio->precio;
                if( $precio->categoria =  'movil')
                    $gasto['movil'] += $precio->precio;
                if( $precio->categoria =  'fija')
                    $gasto['fija'] += $precio->precio;
            }
        } catch (ModelNotFoundException $e) {
            return $gasto;
        }

        return $gasto;
    }

    function partido(Request $request){
        $eventosCategoria = $this->eventoCategoriasGasto('/.*'.$request->partido.'/i');

        $eventoGastoCategorias = new Lavacharts;
        $eventoGastoCategoriasGrafica = $eventoGastoCategorias->DataTable();

        $eventoGastoCategoriasGrafica->addStringColumn('Categoria')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['Estructura', $eventosCategoria['estructura'], 'color:#a72525'])
            ->addRow(['Animación', $eventosCategoria['animacion']], 'color:#a74525')
            ->addRow(['Transporte', $eventosCategoria['transporte'], 'color:#a76625'])
            ->addRow(['Producción', $eventosCategoria['produccion'], 'color:#25A727'])
            ->addRow(['Espectacular', $eventosCategoria['espectacular'], 'color:#6625A7'])
            ->addRow(['Utilitario', $eventosCategoria['utilitario'], 'color:#87a725']);



        $eventoGastoCategorias= \Lava::ColumnChart('Gasto por Categoría', $eventoGastoCategoriasGrafica, ['title' => 'Gasto por Categoría',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);


        $estados = '/.*CIUDAD DE MEXICO|GUERRERO|MORELOS|PUEBLA|TLAXCALA/i';

        $eventosEstados = $this->eventoEstados('/.*'.$request->partido.'/i', $estados);

        $eventosConteoEstados = new Lavacharts;
        $eventoConteoEstadosGrafica = $eventosConteoEstados->DataTable();

        $eventoConteoEstadosGrafica->addStringColumn('Estado')
            ->addNumberColumn('Cantidad')
            ->addRoleColumn('string', 'style')
            ->addRow(['CIUDAD DE MEXICO', $eventosEstados['CIUDAD DE MEXICO']['cantidad'], 'color:#a72525'])
            ->addRow(['GUERRERO', $eventosEstados['GUERRERO']['cantidad'], 'color:#a74525'])
            ->addRow(['MORELOS', $eventosEstados['MORELOS']['cantidad'], 'color:#a76625'])
            ->addRow(['PUEBLA', $eventosEstados['PUEBLA']['cantidad'], 'color:#25A727'])
            ->addRow(['TLAXCALA', $eventosEstados['TLAXCALA']['cantidad'], 'color:#6625A7']);

        $eventosConteoEstados= \Lava::DonutChart('Cantidad por estado', $eventoConteoEstadosGrafica, ['title' => 'Cantidad por estado',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosGastoEstados = new Lavacharts;
        $eventoGastosEstadosGrafica = $eventosGastoEstados->DataTable();

        $eventoGastosEstadosGrafica->addStringColumn('Estado')
            ->addNumberColumn('Cantidad')
            ->addRoleColumn('string', 'style')
            ->addRow(['CIUDAD DE MEXICO', $eventosEstados['CIUDAD DE MEXICO']['gasto'], 'color:#a72525'])
            ->addRow(['GUERRERO', $eventosEstados['GUERRERO']['gasto'], 'color:#a74525'])
            ->addRow(['MORELOS', $eventosEstados['MORELOS']['gasto'], 'color:#a76625'])
            ->addRow(['PUEBLA', $eventosEstados['PUEBLA']['gasto'], 'color:#25A727'])
            ->addRow(['TLAXCALA', $eventosEstados['TLAXCALA']['gasto'], 'color:#6625A7']);

        $eventosGastoEstados= \Lava::DonutChart('Gasto por estado', $eventoGastosEstadosGrafica, ['title' => 'Gasto por estado',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $subcategorias = $this->eventoSubcategoriasGasto('/.*'.$request->partido.'/i');

        $eventosSubcategoriaAnimacion = new Lavacharts;
        $eventosSubcategoriaAnimacionGrafica = $eventosSubcategoriaAnimacion->DataTable();

        $eventosSubcategoriaAnimacionGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['Animación', $subcategorias['animacion']['animacion'], 'color:#a72525'])
            ->addRow(['Edecanes', $subcategorias['animacion']['edecanes'], 'color:#a74525'])
            ->addRow(['Grupos musicales/djs', $subcategorias['animacion']['grupos musicales / djs'], 'color:#a76625'])
            ->addRow(['Otros ', $subcategorias['animacion']['otros'], 'color:#25A727']);

        $eventosSubcategoriaAnimacion= \Lava::DonutChart('Gasto en animación', $eventosSubcategoriaAnimacionGrafica, ['title' => 'Gasto en animación',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosSubcategoriaEspectacular = new Lavacharts;
        $eventosSubcategoriaEspectacularGrafica = $eventosSubcategoriaEspectacular->DataTable();

        $eventosSubcategoriaEspectacularGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['Inflable', $subcategorias['espectacular']['inflable'], 'color:#a72525'])
            ->addRow(['Lonas', $subcategorias['espectacular']['lonas'], 'color:#a74525'])
            ->addRow(['Otros', $subcategorias['espectacular']['otros'], 'color:#a76625'])
            ->addRow(['Pendones', $subcategorias['espectacular']['pendones'], 'color:#25A727']);

        $eventosSubcategoriaEspectacular= \Lava::DonutChart('Gasto en espectacular', $eventosSubcategoriaEspectacularGrafica, ['title' => 'Gasto en espectacular',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosSubcategoriaEstructura = new Lavacharts;
        $eventosSubcategoriaEstructuraGrafica = $eventosSubcategoriaEstructura->DataTable();

        $eventosSubcategoriaEstructuraGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['banner', $subcategorias['estructura']['banner'], 'color:#a72525'])
            ->addRow(['baños publicos', $subcategorias['estructura']['baños publicos'], 'color:#a74525'])
            ->addRow(['carpas', $subcategorias['estructura']['carpas'], 'color:#a76625'])
            ->addRow(['escenario', $subcategorias['estructura']['escenario'], 'color:#25A727'])
            ->addRow(['gradas', $subcategorias['estructura']['gradas'], 'color:#a76625'])
            ->addRow(['mampara', $subcategorias['estructura']['mampara'], 'color:#a76625'])
            ->addRow(['mesas', $subcategorias['estructura']['mesas'], 'color:#a76625'])
            ->addRow(['otros', $subcategorias['estructura']['otros'], 'color:#a76625'])
            ->addRow(['sillas', $subcategorias['estructura']['sillas'], 'color:#a76625'])
            ->addRow(['sillones', $subcategorias['estructura']['sillones'], 'color:#a76625'])
            ->addRow(['templete', $subcategorias['estructura']['templete'], 'color:#a76625'])
            ->addRow(['vallas', $subcategorias['estructura']['vallas'], 'color:#a76625']);

        $eventosSubcategoriaEstructura= \Lava::DonutChart('Gasto en estructura', $eventosSubcategoriaEstructuraGrafica, ['title' => 'Gasto en estructura',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosSubcategoriaProduccion = new Lavacharts;
        $eventosSubcategoriaProduccionGrafica = $eventosSubcategoriaProduccion->DataTable();

        $eventosSubcategoriaProduccionGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['camaras de video', $subcategorias['produccion']['camaras de video'], 'color:#a72525'])
            ->addRow(['computadoras', $subcategorias['produccion']['computadoras'], 'color:#a74525'])
            ->addRow(['consola de audio', $subcategorias['produccion']['consola de audio'], 'color:#a76625'])
            ->addRow(['drone', $subcategorias['produccion']['drone'], 'color:#25A727'])
            ->addRow(['equipo de audio', $subcategorias['produccion']['equipo de audio'], 'color:#a76625'])
            ->addRow(['estructura del partido', $subcategorias['produccion']['estructura del partido'], 'color:#a76625'])
            ->addRow(['gruas de camara', $subcategorias['produccion']['gruas de camara'], 'color:#a76625'])
            ->addRow(['luces', $subcategorias['produccion']['luces'], 'color:#a76625'])
            ->addRow(['microfonos', $subcategorias['produccion']['microfonos'], 'color:#a76625'])
            ->addRow(['muro de video (mas de 2 pantallas)', $subcategorias['produccion']['muro de video (mas de 2 pantallas)'], 'color:#a76625'])
            ->addRow(['otros', $subcategorias['produccion']['otros'], 'color:#a76625'])
            ->addRow(['pantallas', $subcategorias['produccion']['pantallas'], 'color:#a76625'])
            ->addRow(['personal de seguridad', $subcategorias['produccion']['personal de seguridad'], 'color:#a76625'])
            ->addRow(['plantas de luz', $subcategorias['produccion']['plantas de luz'], 'color:#a76625'])
            ->addRow(['proyectores', $subcategorias['produccion']['proyectores'], 'color:#a76625'])
            ->addRow(['servicio medico', $subcategorias['produccion']['servicio medico'], 'color:#a76625']);

        $eventosSubcategoriaProduccion= \Lava::DonutChart('Gasto en producción', $eventosSubcategoriaProduccionGrafica, ['title' => 'Gasto en producción',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosSubcategoriaTransporte = new Lavacharts;
        $eventosSubcategoriaTransporteGrafica = $eventosSubcategoriaTransporte->DataTable();

        $eventosSubcategoriaTransporteGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['automoviles', $subcategorias['transporte']['automoviles'], 'color:#a72525'])
            ->addRow(['camiones', $subcategorias['transporte']['camiones'], 'color:#a74525'])
            ->addRow(['camionetas', $subcategorias['transporte']['camionetas'], 'color:#a76625'])
            ->addRow(['combi/microbus', $subcategorias['transporte']['combi/microbus'], 'color:#25A727'])
            ->addRow(['otros', $subcategorias['transporte']['otros'], 'color:#a76625'])
            ->addRow(['taxi', $subcategorias['transporte']['taxi'], 'color:#a76625']);

        $eventosSubcategoriaTransporte= \Lava::DonutChart('Gasto en transporte', $eventosSubcategoriaTransporteGrafica, ['title' => 'Gasto en transporte',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $eventosSubcategoriaUtilitario = new Lavacharts;
        $eventosSubcategoriaUtilitarioGrafica = $eventosSubcategoriaUtilitario->DataTable();

        $eventosSubcategoriaUtilitarioGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['abanicos', $subcategorias['utilitario']['abanicos'], 'color:#a72525'])
            ->addRow(['aguas', $subcategorias['utilitario']['aguas'], 'color:#a74525'])
            ->addRow(['banderas', $subcategorias['utilitario']['banderas'], 'color:#a76625'])
            ->addRow(['bolsas', $subcategorias['utilitario']['bolsas'], 'color:#25A727'])
            ->addRow(['botones', $subcategorias['utilitario']['botones'], 'color:#a76625'])
            ->addRow(['camisas', $subcategorias['utilitario']['camisas'], 'color:#a76625'])
            ->addRow(['chaleco', $subcategorias['utilitario']['chaleco'], 'color:#a76625'])
            ->addRow(['chamarras', $subcategorias['utilitario']['chamarras'], 'color:#a76625'])
            ->addRow(['cobija', $subcategorias['utilitario']['cobija'], 'color:#a76625'])
            ->addRow(['gorras', $subcategorias['utilitario']['gorras'], 'color:#a76625'])
            ->addRow(['impermeable', $subcategorias['utilitario']['impermeable'], 'color:#a76625'])
            ->addRow(['lonches', $subcategorias['utilitario']['lonches'], 'color:#a76625'])
            ->addRow(['mandiles', $subcategorias['utilitario']['mandiles'], 'color:#a76625'])
            ->addRow(['mangas', $subcategorias['utilitario']['mangas'], 'color:#a76625'])
            ->addRow(['mantas (igual o mayor a 12 mts)', $subcategorias['utilitario']['mantas (igual o mayor a 12 mts)'], 'color:#a76625'])
            ->addRow(['mantas (menores a 12 mts)', $subcategorias['utilitario']['mantas (menores a 12 mts)'], 'color:#a76625'])
            ->addRow(['microperforados', $subcategorias['utilitario']['microperforados'], 'color:#a76625'])
            ->addRow(['otros', $subcategorias['utilitario']['otros'], 'color:#a76625'])
            ->addRow(['paliacates', $subcategorias['utilitario']['paliacates'], 'color:#a76625'])
            ->addRow(['playeras', $subcategorias['utilitario']['playeras'], 'color:#a76625'])
            ->addRow(['pulseras', $subcategorias['utilitario']['pulseras'], 'color:#a76625'])
            ->addRow(['refrescos', $subcategorias['utilitario']['refrescos'], 'color:#a76625'])
            ->addRow(['sombrillas', $subcategorias['utilitario']['sombrillas'], 'color:#a76625'])
            ->addRow(['stickers', $subcategorias['utilitario']['stickers'], 'color:#a76625'])
            ->addRow(['sudadera', $subcategorias['utilitario']['sudadera'], 'color:#a76625'])
            ->addRow(['tortilleros', $subcategorias['utilitario']['tortilleros'], 'color:#a76625'])
            ->addRow(['vasos', $subcategorias['utilitario']['vasos'], 'color:#a76625'])
            ->addRow(['vinilonas', $subcategorias['utilitario']['vinilonas'], 'color:#a76625'])
            ->addRow(['volantes', $subcategorias['utilitario']['volantes'], 'color:#a76625']);

        $eventosSubcategoriaUtilitario= \Lava::DonutChart('Gasto en utilitario', $eventosSubcategoriaUtilitarioGrafica, ['title' => 'Gasto en utilitario',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);
        /********************   Comienza Tierra   ********************/

        $tierraCategorias = $this->tierraCategoriasGasto('/.*'.$request->partido.'/i', $estados);

        $tierraGastoCategorias = new Lavacharts;
        $tierraGastoCategoriasGrafica = $tierraGastoCategorias->DataTable();

        $tierraGastoCategoriasGrafica->addStringColumn('Categoria')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['Movil', $tierraCategorias['movil'], 'color:#6625A7'])
            ->addRow(['Fija', $tierraCategorias['fija'], 'color:#a72525']);

        $tierraGastoCategorias= \Lava::ColumnChart('Gasto de tierra por Categoría', $tierraGastoCategoriasGrafica, ['title' => 'Gasto por Categoría',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $tierraEstados = $this->tierraEstados('/.*'.$request->partido.'/i', $estados);

        $tierraConteoEstados = new Lavacharts;
        $tierraConteoEstadosGrafica = $tierraConteoEstados->DataTable();

        $tierraConteoEstadosGrafica->addStringColumn('Estado')
            ->addNumberColumn('Cantidad')
            ->addRoleColumn('string', 'style')
            ->addRow(['CIUDAD DE MEXICO', $tierraEstados['CIUDAD DE MEXICO']['cantidad'], 'color:#a72525'])
            ->addRow(['GUERRERO', $tierraEstados['GUERRERO']['cantidad'], 'color:#a74525'])
            ->addRow(['MORELOS', $tierraEstados['MORELOS']['cantidad'], 'color:#a76625'])
            ->addRow(['PUEBLA', $tierraEstados['PUEBLA']['cantidad'], 'color:#25A727'])
            ->addRow(['TLAXCALA', $tierraEstados['TLAXCALA']['cantidad'], 'color:#6625A7']);

        $tierraConteoEstados= \Lava::DonutChart('Tierra cantidad por estado', $tierraConteoEstadosGrafica, ['title' => 'Cantidad por estado',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $tierraGastoEstados = new Lavacharts;
        $tierraGastosEstadosGrafica = $tierraGastoEstados->DataTable();

        $tierraGastosEstadosGrafica->addStringColumn('Estado')
            ->addNumberColumn('Cantidad')
            ->addRoleColumn('string', 'style')
            ->addRow(['CIUDAD DE MEXICO', $tierraEstados['CIUDAD DE MEXICO']['gasto'], 'color:#a72525'])
            ->addRow(['GUERRERO', $tierraEstados['GUERRERO']['gasto'], 'color:#a74525'])
            ->addRow(['MORELOS', $tierraEstados['MORELOS']['gasto'], 'color:#a76625'])
            ->addRow(['PUEBLA', $tierraEstados['PUEBLA']['gasto'], 'color:#25A727'])
            ->addRow(['TLAXCALA', $tierraEstados['TLAXCALA']['gasto'], 'color:#6625A7']);

        $tierraGastoEstados= \Lava::DonutChart('Tierra gasto por estado', $tierraGastosEstadosGrafica, ['title' => 'Gasto por estado',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $tierraSubcategorias = $this->tierraSubcategoriasGasto('/.*'.$request->partido.'/i', $estados);

        $tierraSubcategoriaFija = new Lavacharts;
        $tierraSubcategoriaFijaGrafica = $tierraSubcategoriaFija->DataTable();

        $tierraSubcategoriaFijaGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['bardas', $tierraSubcategorias['fija']['bardas'], 'color:#a72525'])
            ->addRow(['buzones', $tierraSubcategorias['fija']['buzones'], 'color:#a74525'])
            ->addRow(['caja de luz', $tierraSubcategorias['fija']['caja de luz'], 'color:#a76625'])
            ->addRow(['carteles', $tierraSubcategorias['fija']['carteles'], 'color:#25A727'])
            ->addRow(['espectaculares', $tierraSubcategorias['fija']['espectaculares'], 'color:#a76625'])
            ->addRow(['espectaculares de pantallas digitales', $tierraSubcategorias['fija']['espectaculares de pantallas digitales'], 'color:#a76625'])
            ->addRow(['kioscos', $tierraSubcategorias['fija']['kioscos'], 'color:#a76625'])
            ->addRow(['lonas', $tierraSubcategorias['fija']['lonas'], 'color:#a76625'])
            ->addRow(['mantas (igual o mayor a 12 mts)', $tierraSubcategorias['fija']['mantas (igual o mayor a 12 mts)'], 'color:#a76625'])
            ->addRow(['mantas (menores a 12 mts)', $tierraSubcategorias['fija']['mantas (menores a 12 mts)'], 'color:#a76625'])
            ->addRow(['marquesinas', $tierraSubcategorias['fija']['marquesinas'], 'color:#a76625'])
            ->addRow(['muebles urbanos', $tierraSubcategorias['fija']['muebles urbanos'], 'color:#a76625'])
            ->addRow(['pantallas fijas', $tierraSubcategorias['fija']['pantallas fijas'], 'color:#a76625'])
            ->addRow(['parabuses', $tierraSubcategorias['fija']['parabuses'], 'color:#a76625'])
            ->addRow(['pendones', $tierraSubcategorias['fija']['pendones'], 'color:#a76625'])
            ->addRow(['propaganda en columnas', $tierraSubcategorias['fija']['propaganda en columnas'], 'color:#a76625'])
            ->addRow(['puente', $tierraSubcategorias['fija']['puente'], 'color:#a76625'])
            ->addRow(['valla digital', $tierraSubcategorias['fija']['valla digital'], 'color:#a76625'])
            ->addRow(['volantes', $tierraSubcategorias['fija']['volantes'], 'color:#a76625']);

        $tierraSubcategoriaFija= \Lava::DonutChart('Gasto en fija', $tierraSubcategoriaFijaGrafica, ['title' => 'Gasto en fija',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        $tierraSubcategoriaMovil = new Lavacharts;
        $tierraSubcategoriaMovilGrafica = $tierraSubcategoriaMovil->DataTable();

        $tierraSubcategoriaMovilGrafica->addStringColumn('Subcategoría')
            ->addNumberColumn('Gasto')
            ->addRoleColumn('string', 'style')
            ->addRow(['bicicletas, bicitaxis, mototaxis', $tierraSubcategorias['movil']['bicicletas, bicitaxis, mototaxis'], 'color:#a72525'])
            ->addRow(['brigadas', $tierraSubcategorias['movil']['brigadas'], 'color:#a74525'])
            ->addRow(['metro', $tierraSubcategorias['movil']['metro'], 'color:#a76625'])
            ->addRow(['perifoneo', $tierraSubcategorias['movil']['perifoneo'], 'color:#25A727'])
            ->addRow(['transporte publico', $tierraSubcategorias['movil']['transporte publico'], 'color:#a76625'])
            ->addRow(['vehiculos particulares', $tierraSubcategorias['movil']['vehiculos particulares'], 'color:#a76625'])
            ->addRow(['vehiculos publicitarios', $tierraSubcategorias['movil']['vehiculos publicitarios'], 'color:#a74525']);

        $tierraSubcategoriaMovil= \Lava::DonutChart('Gasto en movil', $tierraSubcategoriaMovilGrafica, ['title' => 'Gasto en movil',
            'titleTextStyle' => [
                'fontName' => 'Arial',
                'fontColor' => 'black',
                'fontSize' => 30,
            ],
            'height' => 300]);

        return view('admin.dashboard.partido')
            ->with('partido', $request->partido)
            ->with('eventoGastoCategorias', $eventoGastoCategorias)
            ->with('eventoConteoEstados', $eventosConteoEstados)
            ->with('eventoGastoEstados', $eventosGastoEstados)
            ->with('eventosSubcategoriaAnimacion', $eventosSubcategoriaAnimacion)
            ->with('eventosSubcategoriaEspectacular', $eventosSubcategoriaEspectacular)
            ->with('eventosSubcategoriaEstructura', $eventosSubcategoriaEstructura)
            ->with('eventosSubcategoriaProduccion', $eventosSubcategoriaProduccion)
            ->with('eventosSubcategoriaTransporte', $eventosSubcategoriaTransporte)
            ->with('eventosSubcategoriaUtilitario', $eventosSubcategoriaUtilitario)

            ->with('tierraGastoCategorias', $tierraGastoCategorias)
            ->with('tierraConteoEstados', $tierraConteoEstados)
            ->with('tierraGastoEstados', $tierraGastoEstados)
            ->with('tierraSubcategoriaMovil', $tierraSubcategoriaMovil)
            ->with('tierraSubcategoriaFija', $tierraSubcategoriaFija)
            ->with('prueba', $tierraSubcategorias);
    }

    function eventoCategoriasGasto($partido){
        $gasto = array( 'estructura' => 0, 'animacion' => 0, 'transporte' => 0, 'produccion' => 0, 'espectacular' => 0, 'utilitario' => 0);
        try{
            $categorias = FiltradoEventos::project(['estructura.precio' => 1,
                                                    'espectacular.precio' => 1,
                                                    'utilitario.precio' => 1,
                                                    'transporte.precio' => 1,
                                                    'produccion.precio' => 1,
                                                    'animacion.precio' => 1,])->where('partido', 'regex', $partido)->get();
            foreach ($categorias as $categoria){
                if(isset($categoria['estructura'])) {
                    foreach ($categoria['estructura'] as $subcategorias) {
                        $gasto['estructura'] += $subcategorias['precio'];
                    }
                }
                if(isset($categoria['espectacular'])) {
                    foreach ($categoria['espectacular'] as $subcategorias) {
                        $gasto['espectacular'] += $subcategorias['precio'];
                    }
                }
                if(isset($categoria['utilitario'])) {
                    foreach ($categoria['utilitario'] as $subcategorias) {
                        $gasto['utilitario'] += $subcategorias['precio'];
                    }
                }
                if(isset($categoria['transporte'])) {
                    foreach ($categoria['transporte'] as $subcategorias) {
                        $gasto['transporte'] += $subcategorias['precio'];
                    }
                }
                if(isset($categoria['produccion'])) {
                    foreach ($categoria['produccion'] as $subcategorias) {
                        $gasto['produccion'] += $subcategorias['precio'];
                    }
                }
                if(isset($categoria['animacion'])) {
                    foreach ($categoria['animacion'] as $subcategorias) {
                        $gasto['animacion'] += $subcategorias['precio'];
                    }
                }
            }
        } catch (ModelNotFoundException $e) {
            return $gasto;
        }

        return $gasto;
    }

    function eventoEstados($partido, $estados){

        $arrayEstados = array( 'CIUDAD DE MEXICO' => array('gasto' => 0, 'cantidad' =>  0),
            'GUERRERO' => array('gasto' => 0, 'cantidad' =>  0),
            'MORELOS' => array('gasto' => 0, 'cantidad' =>  0),
            'PUEBLA' => array('gasto' => 0, 'cantidad' =>  0),
            'TLAXCALA' => array('gasto' => 0, 'cantidad' =>  0));

        try{
            $gastos = FiltradoEventos::project(['precio' => 1, 'estado' => 1])
                ->where('partido', 'regex', $partido)
                ->where('circunscripcion', 4)
                ->where('estado', 'regex', $estados)
                ->get();

            foreach ($gastos as $gasto){
                if(isset($arrayEstados[$gasto['estado']])) {
                    $arrayEstados[$gasto['estado']]['gasto'] += $gasto['precio'];
                    $arrayEstados[$gasto['estado']]['cantidad'] += 1;
                }
            }
        } catch (ModelNotFoundException $e) {
            return $arrayEstados;
        }

        return $arrayEstados;
    }

    function eventoSubcategoriasGasto($partido){
        $gasto = array( 'animacion' =>['animacion' => 0,
                                       'edecanes' => 0,
                                       'grupos musicales / djs' => 0,
                                       'otros' => 0 ],
                        'espectacular'=>[ 'inflable' => 0,
                                          'lonas' => 0,
                                          'otros' => 0,
                                          'pendones' => 0, ],
                        'estructura'=>[ 'banner' => 0,
                                        'baños publicos' => 0,
                                        'carpas' => 0,
                                        'escenario' => 0,
                                        'gradas' => 0,
                                        'mampara' => 0,
                                        'mesas' => 0,
                                        'otros' => 0,
                                        'sillas' => 0,
                                        'sillones' => 0,
                                        'templete' => 0,
                                        'vallas' => 0, ],
                        'produccion'=>[ 'camaras de video' => 0,
                                        'computadoras' => 0,
                                        'consola de audio' => 0,
                                        'drone' => 0,
                                        'equipo de audio' => 0,
                                        'estructura del partido' => 0,
                                        'gruas de camara' => 0,
                                        'luces' => 0,
                                        'microfonos' => 0,
                                        'muro de video (mas de 2 pantallas)' => 0,
                                        'otros' => 0,
                                        'pantallas' => 0,
                                        'personal de seguridad' => 0,
                                        'plantas de luz' => 0,
                                        'proyectores' => 0,
                                        'servicio medico' => 0 ],
                        'transporte'=>[ 'automoviles' => 0,
                                        'camiones' => 0,
                                        'camionetas' => 0,
                                        'combi/microbus' => 0,
                                        'otros' => 0,
                                        'taxi' => 0 ],
                        'utilitario'=>[ 'abanicos' => 0,
                                        'aguas' => 0,
                                        'banderas' => 0,
                                        'bolsas' => 0,
                                        'botones' => 0,
                                        'camisas' => 0,
                                        'chaleco' => 0,
                                        'chamarras' => 0,
                                        'cobija' => 0,
                                        'gorras' => 0,
                                        'impermeable' => 0,
                                        'lonches' => 0,
                                        'mandiles' => 0,
                                        'mangas' => 0,
                                        'mantas (igual o mayor a 12 mts)' => 0,
                                        'mantas (menores a 12 mts)' => 0,
                                        'microperforados' => 0,
                                        'otros' => 0,
                                        'paliacates' => 0,
                                        'playeras' => 0,
                                        'pulseras' => 0,
                                        'refrescos' => 0,
                                        'sombrillas' => 0,
                                        'stickers' => 0,
                                        'sudadera' => 0,
                                        'tortilleros' => 0,
                                        'vasos' => 0,
                                        'vinilonas' => 0,
                                        'volantes' => 0 ]
        );

        try{
            $categorias = FiltradoEventos::project(['estructura.subcategoria' => 1, 'estructura.precio' => 1,
                'espectacular.subcategoria' => 1, 'espectacular.precio' => 1,
                'utilitario.subcategoria' => 1, 'utilitario.precio' => 1,
                'transporte.subcategoria' => 1, 'transporte.precio' => 1,
                'produccion.subcategoria' => 1, 'produccion.precio' => 1,
                'animacion.subcategoria' => 1, 'animacion.precio' => 1,])->where('partido', 'regex', $partido)->get();
            foreach ($categorias as $categoria){
                if(isset($categoria['estructura'])) {
                    foreach ($categoria['estructura'] as $subcategorias) {
                        if (isset($gasto['estructura'][$subcategorias['subcategoria']])){
                            $gasto['estructura'][$subcategorias['subcategoria']] += $subcategorias['precio'];
                        }
                    }
                }
                if(isset($categoria['espectacular'])) {
                    foreach ($categoria['espectacular'] as $subcategorias) {
                        if (isset($gasto['espectacular'][$subcategorias['subcategoria']])){
                            $gasto['espectacular'][$subcategorias['subcategoria']] += $subcategorias['precio'];
                        }
                    }
                }
                if(isset($categoria['utilitario'])) {
                    foreach ($categoria['utilitario'] as $subcategorias) {
                        if (isset($gasto['utilitario'][$subcategorias['subcategoria']])){
                            $gasto['utilitario'][$subcategorias['subcategoria']] += $subcategorias['precio'];
                        }
                    }
                }
                if(isset($categoria['transporte'])) {
                    foreach ($categoria['transporte'] as $subcategorias) {
                        if (isset($gasto['transporte'][$subcategorias['subcategoria']])){
                            $gasto['transporte'][$subcategorias['subcategoria']] += $subcategorias['precio'];
                        }
                    }
                }
                if(isset($categoria['produccion'])) {
                    foreach ($categoria['produccion'] as $subcategorias) {
                        if (isset($gasto['produccion'][$subcategorias['subcategoria']])){
                            $gasto['produccion'][$subcategorias['subcategoria']] += $subcategorias['precio'];
                        }
                    }
                }
                if(isset($categoria['animacion'])) {
                    foreach ($categoria['animacion'] as $subcategorias) {
                        if (isset($gasto['animacion'][$subcategorias['subcategoria']])){
                            $gasto['animacion'][$subcategorias['subcategoria']] += $subcategorias['precio'];
                        }
                    }
                }
            }
        } catch (ModelNotFoundException $e) {
            return $gasto;
        }

        return  $gasto;
    }

    function tierraCategoriasGasto($partido){
        $gasto = array( 'movil' => 0, 'fija' => 0);
        try{
            $categorias = FiltradoTierra::project(['categoria' => 1, 'precio' => 1])->where('partido', 'regex', $partido)->get();
            foreach ($categorias as $categoria){
                if($categoria['categoria'] == 'movil') {
                    $gasto['movil'] += $categoria['precio'];
                }
                if($categoria['categoria'] == 'fija') {
                    $gasto['fija'] += $categoria['precio'];
                }
            }
        } catch (ModelNotFoundException $e) {
            return $gasto;
        }

        return $gasto;
    }

    function tierraEstados($partido, $estados){

        $arrayEstados = array( 'CIUDAD DE MEXICO' => array('gasto' => 0, 'cantidad' =>  0),
            'GUERRERO' => array('gasto' => 0, 'cantidad' =>  0),
            'MORELOS' => array('gasto' => 0, 'cantidad' =>  0),
            'PUEBLA' => array('gasto' => 0, 'cantidad' =>  0),
            'TLAXCALA' => array('gasto' => 0, 'cantidad' =>  0));

        try{
            $gastos = FiltradoTierra::project(['precio' => 1, 'estado' => 1])
                ->where('partido', 'regex', $partido)
                ->where('circunscripcion', 4)
                ->where('estado', 'regex', $estados)
                ->get();

            foreach ($gastos as $gasto){
                if(isset($arrayEstados[$gasto['estado']])){
                    $arrayEstados[$gasto['estado']]['gasto'] += $gasto['precio'];
                    $arrayEstados[$gasto['estado']]['cantidad'] += 1;
                }
            }
        } catch (ModelNotFoundException $e) {
            return $arrayEstados;
        }

        return $arrayEstados;
    }

    function tierraSubcategoriasGasto($partido){
        $gasto = array(
            'movil' =>['bicicletas, bicitaxis, mototaxis' => 0,
                'brigadas' => 0,
                'metro' => 0,
                'perifoneo' => 0,
                'transporte publico' => 0,
                'vehiculos particulares' => 0,
                'vehiculos publicitarios' => 0],
            'fija'=>[ 'bardas' => 0,
                'buzones' => 0,
                'caja de luz' => 0,
                'carteles' => 0,
                'espectaculares' => 0,
                'espectaculares de pantallas digitales' => 0,
                'kioscos' => 0,
                'lonas' => 0,
                'mantas (igual o mayor a 12 mts)' => 0,
                'mantas (menores a 12 mts)' => 0,
                'marquesinas' => 0,
                'muebles urbanos' => 0,
                'pantallas fijas' => 0,
                'parabuses' => 0,
                'pendones' => 0,
                'propaganda en columnas' => 0,
                'puente' => 0,
                'valla digital' => 0,
                'volantes' => 0, ]
        );

        try{
            $categorias = FiltradoTierra::project(['categoria' => 1, 'subcategoria' => 1, 'precio' => 1])->where('partido', 'regex', $partido)->get();
            foreach ($categorias as $categoria){
                if($categoria['categoria'] == 'movil' && isset($gasto['movil'][$categoria['subcategoria']])) {
                    $gasto['movil'][$categoria['subcategoria']] += $categoria['precio'];
                }
                if($categoria['categoria'] == 'fija' && isset($gasto['fija'][$categoria['subcategoria']])) {
                    $gasto['fija'][$categoria['subcategoria']] += $categoria['precio'];
                }
            }
        } catch (ModelNotFoundException $e) {
            return $gasto;
        }

        return  $gasto;
    }

}
