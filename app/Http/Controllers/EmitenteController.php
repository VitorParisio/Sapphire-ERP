<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Emitente;
use Illuminate\Support\Facades\Storage;

class EmitenteController extends Controller
{
    public function index()
    {
        return view('emitentes.index');
    }

    function getEmpresa(Request $request)
    {
      $query              = $request->get('query');
      $output             = '';
      $total_row          = '';
  
      if ($request->ajax())
      {
        if ($query != '')
        {
          $empresas       = Emitente::where('razao_social','LIKE','%'.$query.'%')->get(); 
          $total_empresas = $empresas->count();
        }
        else
        {
          $empresas       = Emitente::orderBy('id', 'ASC')->get();
          $total_empresas = $empresas->count();
        }
  
        $total_row   = $empresas->count();
  
        if ($total_row > 0)
        {
          foreach($empresas as $row)
          {
            $im = $row->im == null ? null : $row->im;
            $cnae = $row->cnae == null ? null : $row->cnae;
            $complemento = $row->complemento == null ? null : $row->complemento;

            $output .='
              <tr>
                <td>'.$row->id.'</td>
                <td>'.$row->cnpj.'</td>
                <td>'.ucfirst($row->nome_fantasia).'</td>
                <td>'.$row->ie.'</td>
                <td>'.$row->cidade.'</td>
                <td style="display:none;">'.ucfirst($row->razao_social).'</td>
                <td style="display:none;">'.$im.'</td>
                <td style="display:none;">'.$cnae.'</td>
                <td style="display:none;">'.$row->cep.'</td>
                <td style="display:none;">'.$row->rua.'</td>
                <td style="display:none;">'.$row->numero.'</td>
                <td style="display:none;">'.$complemento.'</td>
                <td style="display:none;">'.$row->bairro.'</td>
                <td style="display:none;">'.$row->uf.'</td>
                <td><a href="#" class="dtls_btn"><i class="fas fa-eye fa-sm" title="Detalhes da empresa"></i></a></td>
                <td><a href="#" class="edt_btn" style="color:gray"><i class="fas fa-edit fa-sm" title="Editar empresa"></i></a></td>
                <td><a href="#" class="del_btn" style="color:red"><i class="fas fa-times-circle fa-sm" title="Deletar empresa"></i></i></a></td>
              </tr>
            ';
          }
        }
        else
        {
          $output ='
            <tr>
              <td colspan="7" style="font-weight:100; font-size: 19px"><i>Unidade não encontrada.</i></td>
            </tr>
          ';
        }
  
        $data = array(
          'output'            => $output,
          'total_empresas'     => $total_empresas,
        );
  
        return response()->json($data);
      }
    } 

    public function store(Request $request)
    {
        $data      = $request->all();
        $validator = Validator::make($data, [
            'cnpj'              => 'required|regex:/^[0-9]+$/|max:14|min:11|unique:emitentes,cnpj',
            'razao_social'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/|min:2',
            'nome_fantasia'     => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/|min:2',
            'ie'                => 'nullable|numeric|unique:emitentes,ie',
            'im'                => 'nullable|numeric|unique:emitentes,im',
            'cnae'              => 'nullable|numeric|unique:emitentes,cnae',
            'cep'               => 'nullable|regex:/^[0-9]+$/|max:8',
            'rua'               => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
            'numero'            => 'nullable|numeric',
            'complemento'       => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
            'bairro'            => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]+$/',
            'cidade'            => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ ]+$/',
            'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
            'cuf'               => 'nullable|numeric',
            'cibge'             => 'nullable|numeric',
            'telefone'          => 'nullable|numeric',
            'certificado_a1'    => 'nullable|mimetypes:application/octet-stream',
        ],
        [
            'cnpj.required'             => 'Campo "CNPJ/CPF" deve ser preenchido.',
            'cnpj.regex'                => 'Digitar apenas números no campo "CNPJ/CPF".',
            'cnpj.max'                  => 'Excedeu o limite de digitos no campo "CNPJ/CPF".',
            'cnpj.min'                  => 'Poucos dígitos no campo "CNPJ/CPF".',
            'cnpj.unique'               => 'CNPJ já cadastrado.',
            'razao_social.regex'        => 'Digitar apenas letras e/ou números no campo "Razão Social".',
            'razao_social.min'          => 'Poucos dígitos no campo "Razão Social".',
            'nome_fantasia.required'    => 'Campo "Fantasia" deve ser preenchido.',
            'nome_fantasia.regex'       => 'Digitar apenas letras e/ou números no campo "Fantasia".',
            'nome_fantasia.min'         => 'Poucos dígitos no campo "Fantasia".',
            'ie.numeric'                => 'Digitar apenas números no campo "Inscrição Estadual".',
            'ie.unique'                 => 'Insc. Estadual já cadastrado.',
            'im.numeric'                => 'Digitar apenas números no campo "Inscrição Municipal".',
            'im.unique'                 => 'Insc. Municipal já cadastrado.',
            'cnae.numeric'              => 'Digitar apenas números no campo "CNAE".',
            'cnae.unnique'              => 'CNAE já cadastrado.',
            'cep.regex'                 => 'Digite apenas números no campo "Cep".',
            'cep.max'                   => 'Excedeu o limite de digitos no campo "Cep".',
            'rua.regex'                 => 'Digitar apenas letras e/ou números no campo "Logradouro".',
            'complemento.regex'         => 'Digitar apenas letras e/ou números no campo "Complemento".',
            'numero.numeric'            => 'Digitar apenas números no campo "Número".',
            'bairro.regex'              => 'Digitar apenas letras no campo "Bairro".',
            'cidade.regex'              => 'Digitar apenas letras no campo "Cidade".',
            'uf.regex'                  => 'Digitar apenas a sigla do estado no campo "UF".',
            'cuf.numeric'               => 'Digitar apenas números no campo "cUF".',
            'cuf.max'                   => 'Máximo de 2 dígitos no campo "cUF',
            'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
            'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE".',
            'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
            'certificado_a1.mimetypes'  => 'Campo "Certificado Digital" aceita apenas a extensão .pfx',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        
        if ($request->certificado_a1 != null)
        {
            if ($request->certificado_a1->isValid())
            {
                $certificado_path = Str::of($request->razao_social)->slug('-'). '.' .$request->certificado_a1->getClientOriginalExtension();

                $certificado = $request->certificado_a1->storeAs('certificados', $certificado_path);
                $data['certificado_a1'] = $certificado;
            }
        }

        Emitente::create($data);

        return response()->json(['message' => 'Empresa cadastrada com sucesso.']);
    }

    function update(Request $request, $id)
    {
      if (!$emitente = Emitente::find($id))
        return redirect()->back();

      $data     = $request->all();
      $emitente = Emitente::find($id);
        
      $validator = Validator::make($data, [
        'cnpj'              => 'required|regex:/^[0-9]+$/|max:14|min:11|unique:emitentes,cnpj,'.$emitente->id,
        'razao_social'      => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/|min:2',
        'nome_fantasia'     => 'required|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9 "-]*$/|min:2',
        'ie'                => 'nullable|numeric|unique:emitentes,ie,'.$emitente->id,
        'im'                => 'nullable|numeric|unique:emitentes,im,'.$emitente->id,
        'cnae'              => 'nullable|numeric|unique:emitentes,cnae,'.$emitente->id,
        'cep'               => 'nullable|regex:/^[0-9]+$/|max:8',
        'rua'               => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
        'numero'            => 'nullable|numeric',
        'complemento'       => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]*$/',
        'bairro'            => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ 0-9]+$/',
        'cidade'            => 'nullable|regex:/^[A-Za-záàâãéêíóúçÁÀÂÃÉÊÍÓÚÇ ]+$/',
        'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
        'cuf'               => 'nullable|numeric',
        'cibge'             => 'nullable|numeric',
        'telefone'          => 'nullable|numeric',
        'certificado_a1'    => 'nullable|mimetypes:application/octet-stream',
        'senha_certificado' => 'nullable',
      ],
      [
        'cnpj.required'             => 'Campo "CNPJ/CPF" deve ser preenchido.',
        'cnpj.regex'                => 'Digitar apenas números no campo "CNPJ/CPF".',
        'cnpj.max'                  => 'Excedeu o limite de digitos no campo "CNPJ/CPF".',
        'cnpj.min'                  => 'Poucos dígitos no campo "CNPJ/CPF".',
        'cnpj.unique'               => 'CNPJ já cadastrado.',
        'razao_social.regex'        => 'Digite apenas letras e/ou números no campo "Razão Social".',
        'razao_social.min'          => 'Poucos dígitos no campo "Razão Social".',
        'nome_fantasia.required'    => 'Campo "Fantasia" deve ser preenchido.',
        'nome_fantasia.regex'       => 'Digite apenas letras e/ou números no campo "Fantasia".',
        'nome_fantasia.min'         => 'Poucos dígitos no campo "Fantasia".',
        'ie.numeric'                => 'Digite apenas números no campo "Inscrição Estadual".',
        'ie.unique'                 => 'Insc. Estadual já cadastrado.',
        'im.numeric'                => 'Digite apenas números no campo "Inscrição Municipal".',
        'im.unique'                 => 'Insc. Municipal já cadastrado.',
        'cnae.numeric'              => 'Digite apenas números no campo "CNAE".',
        'cnae.unnique'              => 'CNAE já cadastrado.',
        'cep.regex'                 => 'Digite apenas números no campo "Cep".',
        'cep.max'                   => 'Excedeu o limite de digitos no campo "Cep".',
        'rua.regex'                 => 'Digite apenas letras e/ou números no campo "Logradouro".',
        'complemento.regex'         => 'Digite apenas letras e/ou números no campo "Complemento".',
        'numero.numeric'            => 'Digite apenas números no campo "Número".',
        'bairro.regex'              => 'Digitar apenas letras no campo "Bairro".',
        'cidade.regex'              => 'Digitar apenas letras no campo "Cidade".',
        'uf.max'                    => 'Digite a sigla do estado no campo "UF".',
        'uf.regex'                  => 'Digite a sigla do estado no campo "UF".',
        'cuf.numeric'               => 'Digitar apenas números no campo "cUF".',
        'cuf.max'                   => 'Máximo de 2 dígitos numéricos no campo "cUF',
        'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
        'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE',
        'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
        'certificado_a1.mimetypes'  => 'Campo "Certificado Digital" aceita a extensão .pfx',
      ]);
    
      if ($validator->fails()) {
        return response()->json([
                    'error' => $validator->errors()->all()
                ]);
      }

      $emitente->cnpj              = $data['cnpj'];
      $emitente->razao_social      = $data['razao_social'];
      $emitente->nome_fantasia     = $data['nome_fantasia'];
      $emitente->ie                = $data['ie'];
      $emitente->im                = $data['im'];
      $emitente->cnae              = $data['cnae'];
      $emitente->cep               = $data['cep'];
      $emitente->rua               = $data['rua'];
      $emitente->numero            = $data['numero'];
      $emitente->complemento       = $data['complemento'];
      $emitente->bairro            = $data['bairro'];
      $emitente->cidade            = $data['cidade'];
      $emitente->uf                = $data['uf'];

      if ($request->certificado_a1 != null && $request->senha_certificado != null)
      {
        if ($request->certificado_a1->isValid())
        {
          if (Storage::exists($emitente->certificado_a1))
          {
            Storage::delete($emitente->certificado_a1);
          }
  
          $nome_certificado = Str::of($request->razao_social)->slug('-'). '.' .$request->certificado_a1->getClientOriginalExtension();
  
          $certificado_atualizado      = $request->certificado_a1->storeAs('certificados', $nome_certificado);
          $emitente->certificado_a1    = $certificado_atualizado;
          $emitente->senha_certificado = $data['senha_certificado'];
        }
      }
      else
      {
        $emitente->certificado_a1    = $emitente->certificado_a1;
        $emitente->senha_certificado = $emitente->senha_certificado;
      }
        $emitente->update();
  
        return response()->json(['message' => 'Empresa atualizada com sucesso!']);
    }

    function destroy($id){

      if (!$emitente = Emitente::find($id))
        return redirect()->back();
  
      $emitente->delete();
  
      return response()->json(['message' => 'Empresa removida com sucesso!']);
    }
  
}
