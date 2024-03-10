<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Tenant\ManagerTenant;

class SwitchDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $manager_tenant = app(ManagerTenant::class);
        $tenant         = $this->getTenant($request->getHost());
        
        if ($manager_tenant->domainIsMain())
            return $next($request);
        
        if (!$tenant && $request->url() != route('404_error'))
        {
            return redirect()->route('404_error');

        } else if ($request->url() != route('404_error') && !$manager_tenant->domainIsMain())
        {   

            $manager_tenant->setConnection($tenant);
        }

        return $next($request);
    }

    public function getTenant($host)
    {
        return Tenant::where('dominio', $host)->first();
    }
}
