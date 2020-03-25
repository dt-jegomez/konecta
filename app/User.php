<?php

namespace App;

use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
class User extends Authenticatable implements JWTSubject //, MustVerifyEmail
{
    use Notifiable;

    
    protected $fillable = [
        'name', 'email', 'password','id_rol',
    ];

   
    protected $hidden = [
        'password', 'remember_token',
    ];

   
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

   
    protected $appends = [
        'photo_url',
    ];

    
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
    }

    
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

  
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

   
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    
    public function getJWTCustomClaims()
    {
        return [];
    }

    static function registar($data){
        unset($data['password_confirmation']);
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    static function actualizar($data,$id){
        return User::where('id',$id)->update($data);
    }

    static function listar() {
        return User::join('roles', 'roles.id', '=', 'users.id_rol')
            ->select('users.*', 'roles.nombre as rol')
            ->get();
    }
    
    static function eliminar($id){
        return User::where('id',$id)->delete();
    }

    public function rol () {
        return $this->belongsTo(Rol::class,'id_rol')->select('id','nombre');
    }
}
