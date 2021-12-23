<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        //dd($request->all());
        //dd($request->all(['email', 'password'])); // para recuperar parâmetro específico, passar por array por ex: $request->all(['email', 'password']);
        
        $credenciais = $request->all(['email', 'password']);

        //objetivo autenticação (email e senha)
        $token = auth('api')->attempt($credenciais); //Framework tenta atraves do attempt fazer uma autenticação, utilizando o guard api (especificado em config/auth.php no array guards, usando o provider users)
        //dd($token); // caso dê certo a autenticaçao acima, o resultado da operação acima retornará um token.

        if($token) { //usuário autenticado com sucesso
            return response()->json(['token' => $token], 200);
        } else { //erro de usuário ou senha
            return response()->json(['erro' => 'Usuário ou senha inválido'], 403);
            // 403 = forbidden -> proibido (login inválido)
            // 401 = Unauithorized -> não autorizado (referente a Autorização e não autenticação)
        }
        
        //retornar um Json Web Token (JWT), após autenticado
        return 'login';
    }

    public function logout() {
        return 'logout';
    }

    public function refresh() {
        return 'refresh';
    }

    public function me() {
        return 'me';
    }


}
