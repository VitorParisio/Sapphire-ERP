<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Destinatario;

class DestinatarioController extends Controller
{
    public function index()
    {
        $destinatarios = Destinatario::all();
    
        return view('destinatarios.index', compact('destinatarios'));
    }

    function getCliente(Request $request)
    {
      $query              = $request->get('query');
      $output             = '';
      $total_row          = '';
  
      if ($request->ajax())
      {
        if ($query != '')
        {
          $clientes      = Destinatario::where('nome','LIKE','%'.$query.'%')->get(); 
          $total_cliente = $clientes->count();
        }
        else
        {
          $clientes      = Destinatario::orderBy('id', 'ASC')->get();
          $total_cliente = $clientes->count();
        }
  
        $total_row   = $clientes->count();
  
        if ($total_row > 0)
        {
          foreach($clientes as $row)
          {
            $email = $row->email == null ? null : $row->email;
            $telefone = $row->fone == null ? null : $row->fone;
            $complemento = $row->complemento == null ? null : $row->complemento;

            $output .='
              <tr>
                <td data-label="Código">'.$row->id.'</td>
                <td data-label="Cliente">'.ucfirst($row->nome).'</td>
                <td data-label="Telefone">'.$telefone.'</td>
                <td style="display:none;">'.$row->rg_ie.'</td>
                <td style="display:none;">'.$row->cidade.'</td>
                <td style="display:none;">'.$email.'</td>
                <td style="display:none;">'.$row->cpf_cnpj.'</td>
                <td style="display:none;">'.$row->cep.'</td>
                <td style="display:none;">'.$row->rua.'</td>
                <td style="display:none;">'.$row->numero.'</td>
                <td style="display:none;">'.$complemento.'</td>
                <td style="display:none;">'.$row->bairro.'</td>
                <td style="display:none;">'.$row->uf.'</td>
                <td data-label="Detalhes"><a href="javascript:(0);" class="dtls_btn"><i class="fas fa-eye fa-sm" title="Detalhes do cliente"></i></a></td>
                <td data-label="Editar"><a href="javascript:(0);" class="edt_btn" style="color:gray"><i class="fas fa-edit fa-sm" title="Editar cliente"></i></a></td>
                <td data-label="Excluir"><a href="javascript:(0);" class="del_btn" style="color:red"><i class="fas fa-times-circle fa-sm" title="Excluir cliente"></i></a></td>
              </tr>
            ';
          }
        }
        else
        {
          $output ='
            <tr>
              <td colspan="7" style="font-weight:100; font-size: 19px"><i>Cliente não encontrado.</i></td>
            </tr>
          ';
        }
  
        $data = array(
          'output'           => $output,
          'total_client'     => $total_cliente,
        );
  
        return response()->json($data);
      }
    } 

    public function store(Request $request)
    {
      $data      = $request->all();
    
      $validator = Validator::make($data, [
          'nome'        => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ ]*$/|min:2',
          'cpf_cnpj'    => 'nullable|regex:/^[0-9]+$/|min:11|unique:destinatarios,cpf_cnpj',
          'rg_ie'       => 'nullable|numeric|min:7|unique:destinatarios,rg_ie',
          'cep'         => 'nullable|regex:/^[0-9]+$/|max:8',
          'rua'         => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
          'numero'      => 'nullable|numeric',
          'complemento' => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
          'bairro'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]+$/',
          'cidade'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ ]+$/',
          'uf'          => 'nullable|regex:/^[A-Z]+$/|max:2',
          'cibge'       => 'nullable|numeric',
          'fone'        => 'nullable|numeric',
          'email'       => 'nullable|email|unique:destinatarios,email', 
      ],
      [
          'nome.required'     => 'Campo "Cliente" deve ser preenchido.',
          'nome.regex'        => 'Utilize apenas letras no campo "Cliente".',
          'nome.min'          => 'Poucos dígitos no campo "Nome".',
          'cpf_cnpj.regex'    => 'Digitar apenas números no campo "CPF/CNPJ".',
          'cpf_cnpj.min'      => 'Poucos dígitos no campo "CPF/CNPJ".',
          'cpf_cnpj.unique'   => 'CPF ou CNPJ já cadastrado.',
          'rg_ie.unique'      => 'RG ou Insc. Estadual já cadastrada.',
          'rg_ie.numeric'     => 'Digite apenas números no campo "RG/Inscrição Estadual".',
          'rg_ie.min'         => 'Poucos dígitos no campo "RG/Inscrição Estadual".',
          'cep.regex'         => 'Digite apenas números no campo "Cep".',
          'cep.max'           => 'Excedeu o limite de digitos no campo "Cep".',
          'rua.regex'         => 'Digite apenas letras e/ou números no campo "Logradouro".',
          'numero.numeric'    => 'Digite apenas números no campo "Número".',
          'bairro.regex'      => 'Digitar apenas letras e/ou números no campo "Bairro".',
          'complemento.regex' => 'Digitar apenas letras e/ou números no campo "Complemento".',
          'cidade.regex'      => 'Digitar apenas letras no campo "Cidade".',
          'uf.max'            => 'Digite a sigla do estado no campo "UF".',
          'uf.regex'          => 'Digitar apenas a sigla do estado no campo "UF".',
          'cibge.numeric'     => 'Digitar apenas números no campo "cIBGE".',
          'cibge.max'         => 'Máximo de 7 dígitos no campo "cIBGE',
          'telefone.numeric'  => 'Digite apenas números no campo "Telefone".',
          'email.unique'      => 'E-mail já cadastrado.',
          'email.email'       => 'Digite um e-mail válido.'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'error' => $validator->errors()->all()
          ]);
      }
      
      Destinatario::create($data);

      return response()->json(['message' => 'Cliente cadastrado(a) com sucesso.']);
    }

    function update(Request $request, $id)
    {
      if (!$destinatario = Destinatario::find($id))
        return redirect()->back();
        
      $data         = $request->all();
      $destinatario = Destinatario::where('id', $id)
      ->first();
        
       $validator = Validator::make($data, [
            'nome'        => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ ]*$/|min:2',
            'cpf_cnpj'    => 'nullable|regex:/^[0-9]+$/|min:11|unique:destinatarios,cpf_cnpj,'.$destinatario->id,
            'rg_ie'       => 'nullable|numeric|min:7|unique:destinatarios,rg_ie,'.$destinatario->id,
            'cep'         => 'nullable|regex:/^[0-9]+$/|max:8',
            'rua'         => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
            'numero'      => 'nullable|numeric',
            'complemento' => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
            'bairro'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]+$/',
            'cidade'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ ]+$/',
            'uf'          => 'nullable|regex:/^[A-Z]+$/|max:2',
            'cibge'       => 'nullable|numeric',
            'telefone'    => 'nullable|numeric',
            'email'       => 'nullable|email|unique:destinatarios,email,'.$destinatario->id, 
        ],
        [
          'nome.required'     => 'Campo "Cliente" deve ser preenchido.',
          'nome.regex'        => 'Utilize apenas letras no campo "Cliente".',
          'nome.min'          => 'Poucos dígitos no campo "Nome".',
          'cpf_cnpj.regex'    => 'Digitar apenas números no campo "CPF/CNPJ".',
          'cpf_cnpj.min'      => 'Poucos dígitos no campo "CPF/CNPJ".',
          'cpf_cnpj.unique'   => 'CPF ou CNPJ já cadastrado.',
          'rg_ie.unique'      => 'RG ou Insc. Estadual já cadastrada.',
          'rg_ie.numeric'     => 'Digite apenas números no campo "RG/Inscrição Estadual".',
          'rg_ie.min'         => 'Poucos dígitos no campo "RG/Inscrição Estadual".',
          'cep.regex'         => 'Digite apenas números no campo "Cep".',
          'cep.max'           => 'Excedeu o limite de digitos no campo "Cep".',
          'rua.regex'         => 'Digite apenas letras e/ou números no campo "Logradouro".',
          'numero.numeric'    => 'Digite apenas números no campo "Número".',
          'bairro.regex'      => 'Digitar apenas letras e/ou números no campo "Bairro".',
          'complemento.regex' => 'Digitar apenas letras e/ou números no campo "Complemento".',
          'cidade.regex'      => 'Digitar apenas letras no campo "Cidade".',
          'uf.max'            => 'Digite a sigla do estado no campo "UF".',
          'uf.regex'          => 'Digitar apenas a sigla do estado no campo "UF".',
          'cibge.numeric'     => 'Digitar apenas números no campo "cIBGE".',
          'cibge.max'         => 'Máximo de 7 dígitos no campo "cIBGE',
          'telefone.numeric'  => 'Digite apenas números no campo "Telefone".',
          'email.unique'      => 'E-mail já cadastrado.',
          'email.email'       => 'Digite um e-mail válido.'
        ]);
    
      if ($validator->fails()) {
        return response()->json([
                    'error' => $validator->errors()->all()
                ]);
      }

      $destinatario->nome        = $data['nome'];
      $destinatario->cpf_cnpj    = $data['cpf_cnpj'];
      $destinatario->rg_ie       = $data['rg_ie'];
      $destinatario->fone        = $data['fone'];
      $destinatario->email       = $data['email'];
      $destinatario->cep         = $data['cep'];
      $destinatario->rua         = $data['rua'];
      $destinatario->numero      = $data['numero'];
      $destinatario->complemento = $data['complemento'];
      $destinatario->bairro      = $data['bairro'];
      $destinatario->cidade      = $data['cidade'];
      $destinatario->uf          = $data['uf'];

      $destinatario->update();
  
      return response()->json(['message' => 'Cliente atualizado com sucesso!']);
    }

    function destroy($id){

      if (!$destinatario = Destinatario::find($id))
        return redirect()->back();
  
      $destinatario->delete();
  
      return response()->json(['message' => 'Cliente removido com sucesso!']);
    }
}
