<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Mexa extends Model {


    protected $connection = 'mongomexa';
    protected $collection = 'denuncia2018';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fotos', 'ubicacion'
    ];
    //Was protected
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_envio'
    ];

}