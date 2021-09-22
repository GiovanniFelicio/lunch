<?php

namespace App\Http\Middleware;

use Closure;
use App\Locais;
class CheckUserUniqueAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         /* Verifica se o valor da coluna/sessão "token_access" NÃO é compatível com o valor da sessão que criamos quando o usuário fez login
        */
        if (auth()->user()->token_access != session()->get('access_token')) {
            // Faz o logout do usuário
            \Auth::logout();
    
            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                        ->route('login')
                        ->with('message', 'A sessão deste usuário está ativa em outro local!');
        }
        if (auth()->user()->status == 0) {
            // Faz o logout do usuário
            \Auth::logout();
    
            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                        ->route('login')
                        ->with('message', 'Sua conta foi desativada');
        }
        if (Locais::find(auth()->user()->local_id) == null) {
            // Faz o logout do usuário
            \Auth::logout();
    
            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                        ->route('login')
                        ->with('message', 'Você não pertence a local nenhum');
        }
    
        // Permite o acesso, continua a requisição
        return $next($request);
    }
}
