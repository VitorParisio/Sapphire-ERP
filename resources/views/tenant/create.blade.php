@extends('tenant.layout')

@section('header')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Adicionar cliente</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div> 

@stop

@section('content')
    <div class="add_cliente_admin">
       <div class="inputs_clientes_add">
            <form action="{{route('tenant.store')}}">
                <div class="mb-4" style="display: flex; align-items: center; justify-content: space-between; gap:3px;">
                    <div style="display: flex; flex-direction:column">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div style="display: flex; flex-direction:column">
                        <label for="dominio">Domínio:</label>
                        <input type="text" id="dominio" name="dominio" required>
                    </div>
                    <div style="display: flex; flex-direction:column">
                        <label for="host">Host:</label>
                        <input type="text" id="host" name="db_hostname" required>
                    </div>
                </div>
                <div class="mb-4" style="display: flex; align-items: center; justify-content: space-between; gap:3px;">
                    <div style="display: flex; flex-direction:column">
                        <label for="database">Banco de Dados:</label>
                        <input type="text" id="database" name="db_database" required>
                    </div>
                    <div style="display: flex; flex-direction:column">
                        <label for="usuario">Usuário:</label>
                        <input type="text" id="usuario" name="db_username" required>
                    </div>
                    <div style="display: flex; flex-direction:column">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="db_password">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="status">Status</label>
                    <input type="checkbox" id="status" name="status" checked><br>
                </div>
                <input type="submit" value="Adicionar">
            </form>
       </div>
    </div>
@stop