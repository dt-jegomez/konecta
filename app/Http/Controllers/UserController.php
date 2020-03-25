<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB,Hash;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function create(Request $request)
    {
        try {
            return DB::transaction(function()use($request){
            $request->validate([
                'email' => 'required|email|max:255|unique:users',
            ]);
            return User::registar($request->all());
        },5);
    } catch (Exception $e) {
        return $e;
    }
}

public function updated(int $id, Request $request)
{
    try {
        return DB::transaction(function()use($request,$id){
                $request->validate([
                    'email' => 'required|email|unique:users,email,'.$id,
                ]);
                $data = $this->validatePassword($request->all());
                return User::actualizar($data,$id);
            },5);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function listar()
    {
        try {
            return User::listar();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function eliminar(int $id)
    {
        try {
            return User::eliminar($id);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function validatePassword(array $data){
        if ($data['password'] === null) {
            unset($data['password']);
        } else {
           $data['password'] = Hash::make($data['password']);
        }
        unset($data['password_confirmation']);
        return $data;
    }

    // protected function validator(array $data)
    // {
    //     return;

    // }


}
