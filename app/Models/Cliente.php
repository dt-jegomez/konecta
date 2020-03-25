<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [
        'nombre',
        'documento',
        'correo',
        'direccion'
    ];

    static function registar($data){
        return Cliente::create($data);
    }

    static function actualizar($data,$id){
        return Cliente::where('id',$id)->update($data);
    }

    static function listar(){
        return Cliente::all();
    }
    
    static function eliminar($id){
        return Cliente::where('id',$id)->delete();
    }
}
