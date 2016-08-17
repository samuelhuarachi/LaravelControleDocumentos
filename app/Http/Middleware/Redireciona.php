<?php

namespace documentos\Http\Middleware;

use Closure;
use Auth;

class Redireciona
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
        
        $user = Auth::user();
        $nivel = $user->nivel;
        
        $rotaName = $request->route()->getName();
        
        $rotaExplode = explode(".", $rotaName);
        $first = $rotaExplode[0];
        if(strtolower($first) == 'admin' && $nivel != 2) {
            return redirect()->route('clientes.index', [0, 0]);
        }


        if(strtolower($first) == 'clientes' && $nivel != 1) {
            return redirect()->route('admin.index');
        }

        return $next($request);
    }
}
