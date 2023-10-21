<?php

namespace App\Http\Controllers;

use App\Events\TenantEvents\TenantCreated;
use App\Events\TenantEvents\DatabaseCreated;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{

    private $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function create()
    {
        return view('tenant.create');
    }

    public function store(Request $request)
    {
     
        $tenant =  $this->tenant->create($request->all());

        if ($request->has('status'))
            event(new TenantCreated($tenant));
        else
            event(new DatabaseCreated($tenant));
        
        return redirect()->route('tenant.create');
    }
}
