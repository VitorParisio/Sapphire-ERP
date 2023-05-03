<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Emitente;

class EmitenteController extends Controller
{
    public function index()
    {
        return view('emitentes.index');
    }

    public function store(Request $request)
    {
       
        $data      = $request->all();
        $validator = Validator::make($data, [
            'cnpj'              => 'required|regex:/^[0-9]+$/|max:14|min:11',
            'razao_social'      => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
            'nome_fantasia'     => 'required|regex:/^[a-z A-Z 0-9 "-]*$/|min:2',
            'ie'                => 'required|numeric',
            'im'                => 'nullable|numeric',
            'cnae'              => 'nullable|numeric',
            'cep'               => 'required|regex:/^[0-9]+$/|max:8',
            'rua'               => 'required|regex:/^[a-z A-Z 0-9]*$/',
            'numero'            => 'required|numeric',
            'complemento'       => 'nullable',
            'bairro'            => 'required|regex:/^[a-z A-Z "-]+$/',
            'cidade'            => 'required|regex:/^[a-z A-Z]+$/',
            'uf'                => 'nullable|regex:/^[A-Z]+$/|max:2',
            'cuf'               => 'nullable|numeric',
            'cibge'             => 'nullable|numeric',
            'telefone'          => 'nullable|numeric',
            'certificado_a1'    => 'required|mimetypes:application/octet-stream',
            'senha_certificado' => 'required',
        ],
        [
            'cnpj.required'             => 'Campo "CNPJ/CPF" deve ser preenchido.',
            'cnpj.regex'                => 'Digitar apenas números no campo "CNPJ/CPF".',
            'cnpj.max'                  => 'Excedeu o limite de digitos no campo "CNPJ/CPF".',
            'cnpj.min'                  => 'Poucos dígitos no campo "CNPJ/CPF".',
            'razao_social.required'     => 'Campo "Razão Social" deve ser preenchido.',
            'razao_social.regex'        => 'Digite apenas letras e/ou números no campo "Razão Social".',
            'razao_social.min'          => 'Poucos dígitos no campo "Razão Social".',
            'nome_fantasia.required'    => 'Campo "Fantasia" deve ser preenchido.',
            'nome_fantasia.regex'       => 'Digite apenas letras e/ou números no campo "Fantasia".',
            'nome_fantasia.min'         => 'Poucos dígitos no campo "Fantasia".',
            'ie.required'               => 'Campo "Inscrição Estadual" deve ser preenchido.',
            'ie.numeric'                => 'Digite apenas números no campo "Inscrição Estadual".',
            'im.numeric'                => 'Digite apenas números no campo "Inscrição Municipal".',
            'cnae.numeric'              => 'Digite apenas números no campo "CNAE".',
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
            'uf.required'               => 'Campo "UF" deve ser preenchido.',
            'uf.regex'                  => 'Digitar apenas a sigla do estado no campo "UF".',
            'cuf.required'              => 'Campo "cUF" deve ser preenchido.',
            'cuf.numeric'               => 'Digitar apenas números no campo "cUF".',
            'cuf.max'                   => 'Máximo de 2 dígitos no campo "cUF',
            'cibge.required'            => 'Campo "cIBGE" deve ser preenchido.',
            'cibge.numeric'             => 'Digitar apenas números no campo "cIBGE".',
            'cibge.max'                 => 'Máximo de 7 dígitos no campo "cIBGE',
            'telefone.numeric'          => 'Digite apenas números no campo "Telefone".',
            'certificado_a1.required'   => 'Campo "Certificado Digital" deve ser preenchido.',
            'certificado_a1.mimetypes'  => 'Campo "Certificado Digital" aceita as extensões .pfx e .p12',
            'senha_certificado.required'=> 'Campo "Senha (certificado)" deve ser preenchido.'
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
}
