<?php

namespace documentos;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 
                        'password', 'telefone', 'cpf', 'nivel', 'ativo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function getClientes($pesquisa = '') {
        if ($pesquisa == '') {
            return $this->where('nivel', 1)->where('ativo', 1)-> orderBy('created_at', 'desc')->paginate(7);
        }

        return $this->where('name', 'like', '%' . $pesquisa . '%')->where('nivel', 1)->where('ativo', 1)-> orderBy('created_at', 'desc')->paginate(7);

    }

    public function getAdmins($paginate = true) {
        if($paginate) {
            return $this->where('nivel', 2)->where('ativo', 1)->orderBy('created_at', 'desc')->paginate(7);
        }

        return $this->where('nivel', 2)->where('ativo', 1)->orderBy('created_at', 'desc')->get();
    }

    public function updateCliente($data) {
        $email = $data['email'];
        $senha = $data['password'];
        $resultado = $this->where('email', $email)->where('id', '!=', $data['userid'])->get();
        if(count($resultado) > 0) {
            return false;
        }

        //Configura o password
        if (trim($senha) == '') {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }


        $userFind = $this->find($data['userid']);
        $userFind->update($data);

    }

}



