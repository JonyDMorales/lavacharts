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

        return view('admin.dashboard.partido')
            ->with('partido', $request->partido)
            ->with('eventoGastoCategorias', $eventoGastoCategorias)
            ->with('prueba', $eventosEstados);
    }

    function eventoCategoriasGasto($partido){
        $gasto = array( 'estructura' => 0, 'animacion' => 0, 'transporte' => 0, 'produccion' => 0, 'espectacular' => 0, 'utilitario' => 0);
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
                $arrayEstados[$gasto['estado']]['gasto'] += $gasto['precio'];
                $arrayEstados[$gasto['estado']]['cantidad'] += 1;
            }
        } catch (ModelNotFoundException $e) {
            return $arrayEstados;
        }

        return $arrayEstados;
    }
}
