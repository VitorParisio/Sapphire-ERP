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

    public function store(Request $request)
    {
       
        $data      = $request->all();
        $validator = Validator::make($data, [
            'nome'              => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
            'cpf_cnpj'          => 'required|regex:/^[0-9]+$/|min:11|unique:destinatarios,cpf_cnpj',
            'rg_ie'             => 'required|numeric|min:7|unique:destinatarios,rg_ie',
            'cep'               => 'required|regex:/^[0-9]+$/|max:8',
            'rua'               => 'required|regex:/^[a-z A-Z 0-9]*$/',
            'numero'            => 'required|numeric',
            'complemento'       => 'nullable|regex:/^[a-z A-Z 0-9 "-]*$/',
            'bairro'            => 'required|regex:/^[a-z A-Z "-]+$/',
            'cidade'            => 'required|regex:/^[a-z A-Z]+$/',
            'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
            'cibge'             => 'nullable|numeric',
            'telefone'          => 'nullable|numeric',
            'email'             => 'nullable|email|unique:destinatarios,email', 
        ],
        [
            'nome.required'             => 'Campo "Cliente" deve ser preenchido.',
            'nome.regex'                => 'Utilize apenas letras e/ou números no campo "Cliente".',
            'nome.min'                  => 'Poucos dígitos no campo "Nome".',
            'cpf_cnpj.required'         => 'Campo "CPF/CNPJ" deve ser preenchido.',
            'cpf_cnpj.regex'            => 'Digitar apenas números no campo "CPF/CNPJ".',
            'cpf_cnpj.min'              => 'Poucos dígitos no campo "CPF/CNPJ".',
            'cpf_cnpj.unique'           => 'CPF ou CNPJ já cadastrado.',
            'rg_ie.unique'              => 'RG ou Insc. Estadual já cadastrada.',
            'rg_ie.required'            => 'Campo "RG/Inscrição Estadual" deve ser preenchido.',
            'rg_ie.numeric'             => 'Digite apenas números no campo "RG/Inscrição Estadual".',
            'rg_ie.min'                 => 'Poucos dígitos no campo "RG/Inscrição Estadual".',
            'cep.regex'                 => 'Digite apenas números no campo "Cep".',
            'cep.required'              => 'Campo "Cep" deve ser preenchido.',
            'cep.max'                   => 'Excedeu o limite de digitos no campo "Cep".',
            'rua.required'              => 'Campo "Logradouro" deve ser preenchido.',
            'rua.regex'                 => 'Digite apenas letras e/ou números no campo "Logradouro".',
            'numero.required'           => 'Campo "Número" deve ser preenchido.',
            'numero.numeric'            => 'Digite apenas números no campo "Número".',
            'bairro.required'           => 'Campo "Bairro" deve ser preenchido.',
            'bairro.regex'              => 'Digitar apenas letras no campo "Bairro".',
            'cidade.required'           => 'Campo "Cidade" deve ser preenchido.',
            'cidade.regex'              => 'Digitar apenas letras no campo "Cidade".',
            'uf.max'                    => 'Digite a sigla do estado no campo "UF".',
            'uf.regex'                  => 'Digitar apenas a sigla do estado no campo "UF".',
            'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
            'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE',
            'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
            'email.unique'              => 'E-mail já cadastrado.',
            'email.email'               => 'Digite um e-mail válido.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        
        Destinatario::create($data);

        return response()->json(['message' => 'Cliente cadastrado(a) com sucesso.']);
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
          $clientes = Destinatario::where('nome','LIKE','%'.$query.'%')
          ->get(); 
        }
        else
        {
          $clientes = Destinatario::orderBy('id', 'ASC')->get();
        }
  
        $total_row   = $clientes->count();
        $total_cliente = Destinatario::all()->count();
  
        if ($total_row > 0)
        {
          foreach($clientes as $row)
          {
            $email = $row->email == null ? null : $row->email;
            $telefone = $row->fone == null ? null : $row->fone;
            $complemento = $row->complemento == null ? null : $row->complemento;

            $output .='
              <tr>
                <td>'.$row->id.'</td>
                <td>'.ucfirst($row->nome).'</td>
                <td>'.$row->cpf_cnpj.'</td>
                <td>'.$row->rg_ie.'</td>
                <td>'.$row->cidade.'</td>
                <td style="display:none;">'.$email.'</td>
                <td style="display:none;">'.$telefone.'</td>
                <td style="display:none;">'.$row->cep.'</td>
                <td style="display:none;">'.$row->rua.'</td>
                <td style="display:none;">'.$row->numero.'</td>
                <td style="display:none;">'.$complemento.'</td>
                <td style="display:none;">'.$row->bairro.'</td>
                <td style="display:none;">'.$row->uf.'</td>
                <td><a href="#" class="dtls_btn"><i class="fas fa-eye" title="Detalhes do produto"></i></a></td>
                <td><a href="#" class="edt_btn"><i class="fas fa-edit" title="Editar produto"></i></a></td>
                <td><a href="#" class="del_btn"><i class="fas fa-trash" title="Excluir produto"></i></a></td>
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

    function update(Request $request, $id)
    {
      $data         = $request->all();
      $destinatario = Destinatario::where('id', $id)
      ->first();
        
      $validator = Validator::make($data, [
        'nome'              => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
        'cpf_cnpj'          => 'required|regex:/^[0-9]+$/|max:14|min:7|unique:destinatarios,cpf_cnpj,'.$destinatario->id,
        'rg_ie'             => 'required|numeric|unique:destinatarios,rg_ie,'.$destinatario->id,
        'cep'               => 'required|regex:/^[0-9]+$/|max:8',
        'rua'               => 'required|regex:/^[a-z A-Z 0-9]*$/',
        'numero'            => 'required|numeric',
        'complemento'       => 'nullable|regex:/^[a-z A-Z 0-9 "-]*$/',
        'bairro'            => 'required|regex:/^[a-z A-Z "-]+$/',
        'cidade'            => 'required|regex:/^[a-z A-Z]+$/',
        'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
        'cibge'             => 'nullable|numeric',
        'telefone'          => 'nullable|numeric',
        'email'             => 'nullable|email|unique:destinatarios,email,'.$destinatario->id, 
    ],
    [
        'nome.required'             => 'Campo "Cliente" deve ser preenchido.',
        'nome.regex'                => 'Utilize apenas letras e/ou números no campo "Cliente".',
        'nome.min'                  => 'Poucos dígitos no campo "Nome".',
        'cpf_cnpj.required'         => 'Campo "CPF/CNPJ" deve ser preenchido.',
        'cpf_cnpj.regex'            => 'Digitar apenas números no campo "CPF/CNPJ".',
        'cpf_cnpj.min'              => 'Poucos dígitos no campo "CPF/CNPJ".',
        'cpf_cnpj.max'              => 'Excedeu o limite de digitos no campo "CPF/CNPJ".',
        'cpf_cnpj.unique'           => 'CPF ou CNPJ já cadastrado.',
        'rg_ie.unique'              => 'RG ou Insc. Estadual já cadastrada.',
        'rg_ie.required'            => 'Campo "RG/Inscrição Estadual" deve ser preenchido.',
        'rg_ie.numeric'             => 'Digite apenas números no campo "RG/Inscrição Estadual".',
        'cep.regex'                 => 'Digite apenas números no campo "Cep".',
        'cep.required'              => 'Campo "Cep" deve ser preenchido.',
        'cep.max'                   => 'Excedeu o limite de digitos no campo "Cep".',
        'rua.required'              => 'Campo "Logradouro" deve ser preenchido.',
        'rua.regex'                 => 'Digite apenas letras e/ou números no campo "Logradouro".',
        'numero.required'           => 'Campo "Número" deve ser preenchido.',
        'numero.numeric'            => 'Digite apenas números no campo "Número".',
        'bairro.required'           => 'Campo "Bairro" deve ser preenchido.',
        'bairro.regex'              => 'Digitar apenas letras no campo "Bairro".',
        'cidade.required'           => 'Campo "Cidade" deve ser preenchido.',
        'cidade.regex'              => 'Digitar apenas letras no campo "Cidade".',
        'complemento.regex'         => 'Digite apenas letras e/ou números no campo "Complemento".',
        'uf.max'                    => 'Digite a sigla do estado no campo "UF".',
        'uf.regex'                  => 'Digitar apenas a sigla do estado no campo "UF".',
        'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
        'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE',
        'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
        'email.unique'              => 'E-mail já cadastrado.',
        'email.email'               => 'Digite um e-mail válido.'
    ]);
    
      if ($validator->fails()) {
        return response()->json([
                    'error' => $validator->errors()->all()
                ]);
      }

      $destinatario->nome              = $data['nome'];
      $destinatario->cpf_cnpj          = $data['cpf_cnpj'];
      $destinatario->rg_ie             = $data['rg_ie'];
      $destinatario->fone              = $data['fone'];
      $destinatario->email             = $data['email'];
      $destinatario->cep               = $data['cep'];
      $destinatario->rua               = $data['rua'];
      $destinatario->numero            = $data['numero'];
      $destinatario->complemento       = $data['complemento'];
      $destinatario->bairro            = $data['bairro'];
      $destinatario->cidade            = $data['cidade'];
      $destinatario->uf                = $data['uf'];

      $destinatario->update();
  
      return response()->json(['message' => 'Cliente atualizado com sucesso!']);
    }
}
