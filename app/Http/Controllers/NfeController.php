<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\DA\NFe\Danfe;
use App\Models\Emitente;
use App\Models\Destinatario;
use App\Models\Product;
use App\Models\ItemVenda;
use App\Models\PDV;
use App\Models\Nfe;
use App\Models\Venda;
use NFePHP\Mail\Mail;
use stdClass;
use Exception;
use InvalidArgumentException;

class NfeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $dados = Nfe::join('destinatarios', 'destinatarios.id','=', 'nves.destinatario_id')
        ->select('*', 'nves.id AS nota_id')
        ->get();

        return view('nfe.index', compact('dados'));
    }

    public function create()
    { 
        $nota_fiscal = new Nfe();
        $nota_fiscal->save();

        $emitentes      = Emitente::all();
        $destinatarios  = Destinatario::all();
        $produtos       = Product::all();
        $id_nota_fiscal = $nota_fiscal->id;

        return view('nfe.create', compact('emitentes', 'destinatarios', 'produtos', 'id_nota_fiscal'));
    }

    public function cadastraNfe(Request $request)
    {
        $data = $request->all();

        
        $valor_recebido_formatado = str_replace('.', '', $request->valor_recebido);
        $total_venda_formatado    = str_replace('.', '', $request->total_venda);
        //$desconto_formatado     = str_replace('.', '', $request->desconto);
        $troco_formatado          = str_replace('.', '', $request->troco);

        $data['valor_recebido'] = str_replace(',', '.', $valor_recebido_formatado);
        $data['total_venda']    = str_replace(',', '.', $total_venda_formatado);
        $data['troco']          = str_replace(',', '.', $troco_formatado);
        $data['desconto']       = 0.00;
        
        $venda                  = new Venda();
        $venda->nfe_id          = $request->nfe_id;
        $venda->forma_pagamento = $request->forma_pagamento;
        $venda->valor_recebido  = $data['valor_recebido'];
        $venda->troco           = $data['troco'];
        $venda->total_venda     = $data['total_venda'];
        
        $venda->save();

        Nfe::where('id', $request->nfe_id)->update([
            "status_id"       => 2,
            "emitente_id"     => $request->select_emitente,
            "destinatario_id" => $request->select_destinatario,
            "serie_nfe"       => 1,
            "nro_nfe"         => 90,
        ]);  

        return response()->json(['message' => 'Nota fiscal gerada com sucesso.']);

    }

    public function geraNfe($id)
    {   
        
        $nota = Nfe::where('id', $id)->first();
      
        if($nota->status_id == 2)
        {
            $emitente     = Emitente::where('id', $nota->emitente_id)->first();

            $destinatario = Destinatario::where('id', $nota->destinatario_id)->first();

            $item         = ItemVenda::join('products', 'products.id', '=', 'item_vendas.product_id')
            ->where('item_vendas.nfe_id', $id)->get();

            $venda        = Venda::where("nfe_id", $id)
            ->select('forma_pagamento', 'valor_recebido', 'troco')
            ->first();
            
            $config = [
                "atualizacao" => date('Y-m-d h:i:s'), // Data e hora de atualização
                "tpAmb"       => (int) $nota->ambiente, // Tipo de ambiente (1 - Produção, 2 - Homologação)
                "razaosocial" => $emitente->razao_social, // Razão social do emitente
                "cnpj"        => $emitente->cnpj, // CNPJ do emitente (precisa ser válido)
                "ie"          => $emitente->ie, // Inscrição Estadual do emitente (precisa ser válida)
                "siglaUF"     => $emitente->uf, // Sigla da UF do emitente
                "schemes"     => "PL_009_V4", // Esquema da NF-e
                "versao"      => '4.00', // Versão da NF-e
                "tokenIBPT"   => $emitente->tokenIBPT, // Token IBPT
                "CSC"         => $emitente->csc, // Código de Segurança do Contribuinte
                "CSCid"       => $emitente->csc_id, // ID do Código de Segurança do Contribuinte
            ];
          
            // Converte o array de configuração em formato JSON
            $configJson = json_encode($config);
            // Lê o conteúdo do arquivo de certificado digital A1 do emitente
            $certificadoDigital = file_get_contents('storage/'.$emitente->certificado_a1);
            // Extrai as informações do certificado digital a partir do seu conteúdo e senha de desbloqueio
            $certificate = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);
            // Instancia a classe Tools, passando o JSON de configuração e o certificado digital
            $tools = new Tools($configJson, $certificate);
            
            $nfe = new Make();
            $std = new stdClass();
        
            $std->versao = '4.00';
            $std->Id = null;
            $std->pk_nItem = null;
            $nfe->taginfNFe($std);
            
            ##### Node de identificação da NFe######
            $stdIdE = new stdClass();
            $stdIdE->cUF = $emitente->cuf; //coloque um código real e válido
            $stdIdE->cNF = '789' . substr(strval(rand(100000000, 999999999)), 0, 5);
            $stdIdE->natOp = 'VENDA';
            $stdIdE->mod = 55;
            $stdIdE->serie = $nota->serie_nfe;// poder ser que ja testataro,
            $stdIdE->nNF = $nota->nro_nfe;
            $stdIdE->dhEmi = date('Y-m-d') . 'T' . date('H:i:s') . '-03:00';
            $stdIdE->dhSaiEnt = date('Y-m-d') . 'T' . date('H:i:s') . '-03:00';
            $stdIdE->tpNF = 1;
            $stdIdE->idDest = 1;
            $stdIdE->cMunFG = $emitente->cibge; //Código de município precisa ser válido
            $stdIdE->tpImp = 1;
            $stdIdE->tpEmis = 1;
            $stdIdE->cDV = 2;
            $stdIdE->tpAmb = $nota->ambiente; // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            $stdIdE->finNFe = $nota->finNFe;
            $stdIdE->indFinal = 0;
            $stdIdE->indPres = 0;
            $stdIdE->procEmi = '0';
            $stdIdE->verProc = 1;
            $nfe->tagide($stdIdE);
            ##### FIM Node de identificação da NFe######
            
            ##### Node  Emitente  NFE ######
            $stdEmitente = new stdClass();
            $stdEmitente->xNome = $emitente->razao_social;
            $stdEmitente->IE    = $emitente->ie;
            $stdEmitente->xFant = $emitente->nome_fantasia;
            $stdEmitente->IM    = $emitente->im;     
            $stdEmitente->CRT   = $emitente->crt;
            $stdEmitente->CNPJ  = $emitente->cnpj;
           
            $nfe->tagemit($stdEmitente);

            $stdEndereEmitente = new stdClass();
            $stdEndereEmitente->xLgr = $emitente->rua;
            $stdEndereEmitente->nro = $emitente->numero;
            $stdEndereEmitente->xBairro = $emitente->bairro;
            $stdEndereEmitente->cMun = $emitente->cibge; //Código de município precisa ser válido e igual o  cMunFG
            $stdEndereEmitente->xMun = $emitente->cidade;
            $stdEndereEmitente->UF = $emitente->uf;
            $stdEndereEmitente->CEP = $emitente->cep;
            $stdEndereEmitente->cPais = '1058';
            $stdEndereEmitente->xPais = 'BRASIL';
            $stdEndereEmitente->fone = $emitente->telefone;
            $stdEndereEmitente->xCpl = $emitente->complemento;
            $nfe->tagenderEmit($stdEndereEmitente);
            ##### FIM Node  Emitente  NFE ######

            #####  Node Destinatarios NFE ######
            $stdDestinatario        = new stdClass();
            $stdDestinatario->xNome = $destinatario->nome;
            
            ///////////////TRATAR/////
            $stdDestinatario->indIEDest = 2;
            ///////////////TRATAR/////
            
            if (strlen($destinatario->CPF_CNPJ) == '14') {
                $stdDestinatario->CNPJ = $destinatario->cpf_cnpj;
                $stdDestinatario->IE   = $destinatario->rg_ie;
            } else {
                $stdDestinatario->CPF = $destinatario->cpf_cnpj;
            }
            $nfe->tagdest($stdDestinatario);

            $stdEnderecoDestinatario = new stdClass();
            $stdEnderecoDestinatario->xLgr = $destinatario->rua;
            $stdEnderecoDestinatario->nro = $destinatario->numero;
            $stdEnderecoDestinatario->xBairro = $destinatario->bairro;
            $stdEnderecoDestinatario->cMun = $destinatario->cibge;
            $stdEnderecoDestinatario->xMun = $destinatario->cidade;
            $stdEnderecoDestinatario->UF = $destinatario->uf;
            $stdEnderecoDestinatario->CEP = $destinatario->cep;
            $stdEnderecoDestinatario->cPais = $destinatario->cPais;
            $stdEnderecoDestinatario->xPais = $destinatario->xPais;
            $stdEnderecoDestinatario->xCpl = $destinatario->xCpl;
            $stdEnderecoDestinatario->fone = $destinatario->fone;
            $nfe->tagenderDest($stdEnderecoDestinatario);
            ##### FIM Node Destinatarios NFE ######

            //prod OBRIGATÓRIA
            $itens = 0;
            foreach($item as $lisItem):
                $itens++;

                $stdProd = new stdClass();
                $stdProd->item = $itens;
                $stdProd->cProd = $lisItem->id;
                $stdProd->cEAN = "SEM GTIN";
                $stdProd->xProd = $lisItem->nome;
                $stdProd->NCM = $lisItem->ncm;
                //$stdProd->cBenef = 'ab222222';
                $stdProd->EXTIPI = '';
                $stdProd->CFOP = $lisItem->cfop; //Vendas de produção própria ou de terceiros
                $stdProd->uCom = $lisItem->ucom;
                $stdProd->qCom = $lisItem->qtd;
                $stdProd->vUnCom = $lisItem->preco_venda;
                $stdProd->vProd = $lisItem->sub_total;
                $stdProd->cEANTrib = "SEM GTIN"; //'6361425485451';
                $stdProd->uTrib = $lisItem->utrib;
                $stdProd->qTrib = $lisItem->qtd;
                $stdProd->vUnTrib = $lisItem->vuntrib;
                $stdProd->indTot = $lisItem->indTot;
                $nfe->tagprod($stdProd);
            
                //Informações adicionais do produto
                $tag = new stdClass();
                $tag->item = $itens;
                $tag->infAdProd = $lisItem->descricao;
                $nfe->taginfAdProd($tag);
            
                //Imposto
                $stdImposto = new stdClass();
                $stdImposto->item = $itens; //item da NFe
                $stdImposto->vTotTrib = 0.00;
                $nfe->tagimposto($stdImposto);

                //ICMS 
                $stdICMS = new stdClass();
                $stdICMS->item = $itens; //item da NFe
                $stdICMS->orig = 0;
                $stdICMS->CSOSN = 102;
                $stdICMS->pCredSN = 0.00;
                $stdICMS->vCredICMSSN = 0.00;
                $stdICMS->modBCST = null;
                $stdICMS->pMVAST = null;
                $stdICMS->pRedBCST = null;
                $stdICMS->vBCST = null;
                $stdICMS->pICMSST = null;
                $stdICMS->vICMSST = null;
                $stdICMS->vBCFCPST = null; //incluso no layout 4.00
                $stdICMS->pFCPST = null; //incluso no layout 4.00
                $stdICMS->vFCPST = null; //incluso no layout 4.00
                $stdICMS->vBCSTRet = null;
                $stdICMS->pST = null;
                $stdICMS->vICMSSTRet = null;
                $stdICMS->vBCFCPSTRet = null; //incluso no layout 4.00
                $stdICMS->pFCPSTRet = null; //incluso no layout 4.00
                $stdICMS->vFCPSTRet = null; //incluso no layout 4.00
                $stdICMS->modBC = null;
                $stdICMS->vBC = null;
                $stdICMS->pRedBC = null;
                $stdICMS->pICMS = null;
                $stdICMS->vICMS = null;
                $stdICMS->pRedBCEfet = null;
                $stdICMS->vBCEfet = null;
                $stdICMS->pICMSEfet = null;
                $stdICMS->vICMSEfet = null;
                $stdICMS->vICMSSubstituto = null;
                $nfe->tagICMSSN($stdICMS);
            
                //PIS
                $stdPIS = new stdClass();
                $stdPIS->item = $itens; //item da NFe
                $stdPIS->CST = '99';
                //$stdPIS->vBC = 1200;
                //$stdPIS->pPIS = 0;
                $stdPIS->vPIS = 0.00;
                $stdPIS->qBCProd = 0;
                $stdPIS->vAliqProd = 0;
                $nfe->tagPIS($stdPIS);
            
                //COFINS
                $stdCOFINS = new stdClass();
                $stdCOFINS->item = $itens; //item da NFe
                $stdCOFINS->CST = '99';
                $stdCOFINS->vBC = null;
                $stdCOFINS->pCOFINS = null;
                $stdCOFINS->vCOFINS = 0.00;
                $stdCOFINS->qBCProd = 0;
                $stdCOFINS->vAliqProd = 0;
                $nfe->tagCOFINS($stdCOFINS);
            endforeach;

            //icmstot OBRIGATÓRIA
            $stdICMStot = new stdClass();
            $nfe->tagicmstot($stdICMStot);

            //transp OBRIGATÓRIA
            $stdFrete = new stdClass();
            $stdFrete->modFrete = 9;
            $nfe->tagtransp($stdFrete);
            
            //pag OBRIGATÓRIA
            $stdFormPagamento = new stdClass();
            $stdFormPagamento->vTroco = $venda->troco;
            $nfe->tagpag($stdFormPagamento);
           
            //detPag OBRIGATÓRIA
            $stdDetFormPagamento = new stdClass();
            $stdDetFormPagamento->tPag = $venda->forma_pagamento;
            $stdDetFormPagamento->vPag = $venda->valor_recebido;
            $nfe->tagdetpag($stdDetFormPagamento);

            //infadicNFE
            $stdInfoAdicinalNfe = new stdClass();
            $stdInfoAdicinalNfe->infAdFisco = '';
            $stdInfoAdicinalNfe->infCpl = 'Os boleto serão enviando posterior a data';
            $nfe->taginfadic($stdInfoAdicinalNfe);
            
            /**erros do xml */
            if ($nfe->dom->errors) {
                $resultado = '';
                foreach ($nfe->dom->errors as $key => $erros):
                    $resultado .= $erros . '<br>';
                    exit;
                endforeach;
                echo 'Erros Encontrados:</b><br>';
            }

            //Informações do técnico responsável
            $stdinfoResTec = new stdClass();
            $stdinfoResTec->CNPJ = $emitente->cnpj; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
            $stdinfoResTec->xContato= 'Saymon Feio'; //Nome da pessoa a ser contatada
            $stdinfoResTec->email = 'saymon_feio@hotmail.com'; //E-mail da pessoa jurídica a ser contatada
            $stdinfoResTec->fone = '81983021847'; //Telefone da pessoa jurídica/física a ser contatada
            // $stdinfoResTec->CSRT = 'G8063VRTNDMO886SFNK5LDUDEI24XJ22YIPO'; //Código de Segurança do Responsável Técnico
            // $stdinfoResTec->idCSRT = '01'; //Identificador do CSRT
            $nfe->taginfRespTec($stdinfoResTec);
          
            /** monta o xml */
            $nfe->monta();
            $XML = $nfe->getXML();
     
            $chave = $nfe->getChave();
       
            $st = new Standardize();
            $std = $st->toStd($XML);
            //  return $std;    
            /// CRIAMOS AS DATA QUE COMPOEM O NOME DAS PASTAS
            $data_geracao_dia = date('d');
            $data_geracao_ano = date('Y');
            $data_geracao_mes = date('m');
            #######################################################

            // INDETIFICAMOS QUAL ABIENTE PARA CRIAR A PASTA COM NOME DO MESMO.
            $PastaAmbi = $nota->ambiente;
            if ($PastaAmbi == 1):
                $PastaAmbiente = 'producao';
            else:
                $PastaAmbiente = 'homologacao'; 
            endif;
        
            /** salvado o arquivo  xml gerado na pasta "temporaria" */
            $Pasta = "XML/NFe/{$emitente->cnpj}/{$PastaAmbiente}/temporaria/{$data_geracao_ano}/{$data_geracao_mes}/{$data_geracao_dia}";
            
            if (is_dir($Pasta)) {

            } else {
                mkdir($Pasta, 0777, true);
            }
            $arquivo_temp = $Pasta . '/' . $chave . '-nfe.xml';
         
            file_put_contents($arquivo_temp, $XML);
            
            /** assinando o xml */
            $response_assinado = $tools->signNFe(file_get_contents($arquivo_temp));
            
            /** salvado o xml assinado na pasta "assinadas" */
            $path_assinadas = "XML/NFe/{$emitente->cnpj}/{$PastaAmbiente}/assinados/{$data_geracao_ano}/{$data_geracao_mes}/{$data_geracao_dia}";
            $arquivo_assinado = $path_assinadas . '/' . $chave . '-nfe.xml';
           
            if (is_dir($path_assinadas)) {
            } else {
                mkdir($path_assinadas, 0777, true);
            }
            
            file_put_contents($arquivo_assinado, $response_assinado);

            ############ PROTOCOLANDO XML ############
            try {
                $idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
                $resp = $tools->sefazEnviaLote([$response_assinado], $idLote);

                $st = new Standardize();
                $std = $st->toStd($resp);

                if ($std->cStat != 103) {
                    //erro registrar e voltar
                    exit("[$std->cStat] $std->xMotivo");
                }
                $recibo = $std->infRec->nRec; // Vamos usar a variável $recibo para consultar o status da nota
            } catch (Exception $e) {
                //aqui você trata possiveis exceptions do envio
                exit($e->getMessage());
            }
            ############ FIM PROTOCOLANDO XML ############

            $protocolo = $tools->sefazConsultaRecibo($recibo);
           
            $sts = new Standardize();
            $stdProt = $sts->toStd($protocolo);

            $path_Protocolo = "XML/NFe/{$emitente->cnpj}/{$PastaAmbiente}/protocolo/{$data_geracao_ano}/{$data_geracao_mes}/{$data_geracao_dia}";
            $caminho_Protocolo = $path_Protocolo . '/' . $chave . '-nfe-protocolo.xml';

            if (is_dir($path_Protocolo)) {
            } else {
                mkdir($path_Protocolo, 0777, true);
            }
            
            file_put_contents($caminho_Protocolo, $protocolo);

            $caminho_aut = '';
            if ($stdProt->protNFe->infProt->cStat != 100) {

                return (['Resposta' => $stdProt]);

            } else {
                $xml_autorizado = Complements::toAuthorize($response_assinado, $protocolo);
                $path_autorizadas = "XML/NFe/{$emitente->cnpj}/{$PastaAmbiente}/autorizadas/{$data_geracao_ano}/{$data_geracao_mes}/{$data_geracao_dia}";
                $caminho_aut = $path_autorizadas . '/' . $chave . '-nfe.xml';
                if (is_dir($path_autorizadas)) {
                } else {
                    mkdir($path_autorizadas, 0777, true);
                }

                file_put_contents($caminho_aut, $xml_autorizado);

                $stdCl = new Standardize($xml_autorizado);
                // // $std = $stdCl->toStd();
                $arr = $stdCl->toArray();
                $data_xml_aut = $arr['protNFe']['infProt'];
            
                Nfe::where('id', $nota->id)->update([
                    "status_id" => 4,
                    "dhRecbto"  => $data_xml_aut['dhRecbto'],
                    "xMotivo"   => $data_xml_aut['xMotivo'],
                    "chave_nfe" => $chave,
                    "path_xml"  => $path_autorizadas,
                    "path_file" => $caminho_aut,
                    "dataRecibo"=> date('d/m/Y'),
                    "horaRecibo"=> date('H:i:s'),
                    "digVal"    => $data_xml_aut['digVal'],
                    "nProt"     => $data_xml_aut['nProt'],
                    "cStat"     => $data_xml_aut['cStat']
                ]);  
            }
            return response()->json(['message' => 'Nota fiscal transmitida com sucesso.']);
        }
        else
        {
            return response()->json(['message' => 'Nota fiscal já em digitação.']);
        }
    }

    public function consultaNfe(Request $request)
    {
        $nota         = Nfe::where('id', $request->id)->first();
        $emitente     = Emitente::where('id', $nota->emitente_id)->first();

        $config = [
            "atualizacao" => date('Y-m-d h:i:s'), 
            "tpAmb" => (int) $nota->ambiente, 
            "razaosocial" => $emitente->razao_social,
            "cnpj" => $emitente->cnpj, 
            "ie" => $emitente->ie, 
            "siglaUF" => $emitente->uf,
            "schemes" => "PL_009_V4", 
            "versao" => '4.00', 
            "tokenIBPT" => $emitente->tokenIBPT,
            "CSC" => $emitente->csc, 
            "CSCid" => $emitente->csc_id,
        ];

        $configJson         = json_encode($config);
        $certificadoDigital = file_get_contents($emitente->certificado_a1);
        $certificate        = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);

        $tools = new Tools($configJson, $certificate);
        $tools->model('55');

        try {
            $certificate = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);
            $tools = new Tools($configJson, $certificate);
            $tools->model('55');
        
            $chave = $nota->chave_nfe;
            $response = $tools->sefazConsultaChave($chave);
            
            $stdCl = new Standardize($response);
            $std   = $stdCl->toStd();
            $arr   = $stdCl->toArray();
            $json  = $stdCl->toJson();
           
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function imprimeNfe(Request $request)
    {
        $nota = Nfe::where('id', $request->id)->first();
        
        $logo = '';
        $xml  = file_get_contents($nota->path_file);

      
        //$logo = 'data://text/plain;base64,'. base64_encode(file_get_contents(realpath(__DIR__ . '/../images/tulipas.png')));
        //$logo = realpath(__DIR__ . '/../images/tulipas.png');

        try {
            $danfe = new Danfe($xml);
            $danfe->exibirTextoFatura = false;
            $danfe->exibirPIS = false;
            $danfe->exibirIcmsInterestadual = false;
            $danfe->exibirValorTributos = false;
            $danfe->descProdInfoComplemento = false;
            $danfe->exibirNumeroItemPedido = false;
            $danfe->setOcultarUnidadeTributavel(true);
            $danfe->obsContShow(false);
            $danfe->printParameters(
                $orientacao = 'P',
                $papel = 'A4',
                $margSup = 2,
                $margEsq = 2
            );
            $danfe->logoParameters($logo, $logoAlign = 'C', $mode_bw = false);
            $danfe->setDefaultFont($font = 'times');
            $danfe->setDefaultDecimalPlaces(4);
            $danfe->debugMode(false);
            $danfe->creditsIntegratorFooter('Sapphire - Sistema de Vendas - http://www.smartnetloja.com.br');
            //$danfe->epec('891180004131899', '14/08/2018 11:24:45'); //marca como autorizada por EPEC

            // Caso queira mudar a configuracao padrao de impressao
            /*  $this->printParameters( $orientacao = '', $papel = 'A4', $margSup = 2, $margEsq = 2 ); */
            // Caso queira sempre ocultar a unidade tributável
            /*  $this->setOcultarUnidadeTributavel(true); */
            //Informe o numero DPEC
            /*  $danfe->depecNumber('123456789'); */
            //Configura a posicao da logo
            /*  $danfe->logoParameters($logo, 'C', false);  */

            //Gera o PDF
            $pdf = $danfe->render($logo);
            header('Content-Type: application/pdf');
            echo $pdf;
        } catch (InvalidArgumentException $e) {
            echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
        }
    }

    public function cancelaNfe(Request $request)
    {
        $nota     = Nfe::where('id', $request->id)->first();
        $emitente = Emitente::where('id', $nota->emitente_id)->first();

        $config = [
            "atualizacao" => date('Y-m-d h:i:s'), 
            "tpAmb" => (int) $emitente->ambiente, 
            "razaosocial" => $emitente->razao_social, 
            "cnpj" => $emitente->cnpj, 
            "ie" => $emitente->ie, 
            "siglaUF" => $emitente->uf, 
            "schemes" => "PL_009_V4", 
            "versao" => '4.00', 
            "tokenIBPT" => $emitente->tokenIBPT, 
            "CSC" => $emitente->csc, 
            "CSCid" => $emitente->csc_id, 
        ];

        $configJson = json_encode($config);
        
        $certificadoDigital = file_get_contents($emitente->certificado_a1);
        $certificate        = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);

        $tools = new Tools($configJson, $certificate);
        $tools->model('55');
        
        try{
            $chave = $nota->chave_nfe;
            $justificativa = 'NFe com erros de digitação';
            $nProt = $nota->nProt;
            $response = $tools->sefazCancela($chave, $justificativa, $nProt);

            $stdCl = new Standardize($response);
            // // $std = $stdCl->toStd();
            $arr = $stdCl->toArray();
            $eventos = $arr['retEvento']['infEvento'];

            Nfe::where('id', $nota->id)->update(
                [
                    'status'      => 0,
                    'xMotivo'     => $eventos['xMotivo'],
                    'xEvento'     => $eventos['xEvento'],
                    'dhRegEvento' => $eventos['dhRegEvento'],
                    'nProt'       => $eventos['nProt'],
                    'cStat'       => $eventos['cStat'],
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function cartaCorrecaoNfe(Request $request)
    {
        $nota     = Nfe::where('id', $request->id)->first();
        $emitente = Emitente::where('id', $nota->emitente_id)->first();

        $config = [
            "atualizacao" => date('Y-m-d h:i:s'), 
            "tpAmb" => (int) $emitente->ambiente, 
            "razaosocial" => $emitente->razao_social, 
            "cnpj" => $emitente->cnpj, 
            "ie" => $emitente->ie, 
            "siglaUF" => $emitente->uf, 
            "schemes" => "PL_009_V4", 
            "versao" => '4.00', 
            "tokenIBPT" => $emitente->tokenIBPT, 
            "CSC" => $emitente->csc, 
            "CSCid" => $emitente->csc_id, 
        ];

        $configJson         = json_encode($config);
        $certificadoDigital = file_get_contents($emitente->certificado_a1);
        $certificate        = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);

        $tools = new Tools($configJson, $certificate);
        $tools->model('55');

        try {
            $chave      = $nota->chave_nfe;
            $xCorrecao  = 'Informações complementares: Endereco';
            $nSeqEvento = 1;

            $response   = $tools->sefazCCe($chave, $xCorrecao, $nSeqEvento);
        
            $stdCl = new Standardize($response);

            $std  = $stdCl->toStd();
            $arr  = $stdCl->toArray();
            $json = $stdCl->toJson();
           
            //verifique se o evento foi processado
            if ($std->cStat != 128) {
                //houve alguma falha e o evento não foi processado
                //TRATAR
            } else {
                $cStat = $std->retEvento->infEvento->cStat;
                if ($cStat == '135' || $cStat == '136') {
                    //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
                    $xml = Complements::toAuthorize($tools->lastRequest, $response);
                    //grave o XML protocolado 
                } else {
                    //houve alguma falha no evento 
                    //TRATAR
                }
            }    
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // public function emailNfe(Request $request)
    // {
    //     $config = new stdClass();
    //     $config->host = 'smtp.test.com.br';
    //     $config->user = 'usuario@test.com.br';
    //     $config->password = 'senha';
    //     $config->secure = 'tls';
    //     $config->port = 587;
    //     $config->from = 'usuario@test.com.br';
    //     $config->fantasy = 'Test Ltda';
    //     $config->replyTo = 'vendas@test.com.br';
    //     $config->replyName = 'Vendas';

    //     try {
    //         //paramtros:
    //         //config - (obrigatório) vide acima
    //         //xml - (obrigatório) documento a ser enviado NFe, NFCe, CTe, ou CCe, pode ser um path ou o arquivo em string
    //         //pdf - (opcional) documento pdf a ser enviado DANFE, DANFCE, DACTE, ou DACCE, pode ser um path ou o arquivo em string
    //         //enderecos - (opcional) array com os endereços de email adicionais para envio
    //         //template = (opcional) template HTML a ser usado 
    //         $resp = Mail::sendMail($config, 'nfe.xml', '', ['recebedor@outro.com.br'], '');

    //     } catch (\InvalidArgumentException $e) {
    //         echo "Falha: " . $e->getMessage();
    //     } catch (\RuntimeException $e) {
    //         echo "Falha: " . $e->getMessage();
    //     } catch (\Exception $e) {
    //         echo "Falha: " . $e->getMessage();
    //     }  
    // }

    public function statusSefaz()
    {
        $emitente = Emitente::where('id', 1)->first();

        $config = [
            "atualizacao" => date('Y-m-d h:i:s'), 
            "tpAmb" => (int) $emitente->ambiente, 
            "razaosocial" => $emitente->razao_social,
            "cnpj" => $emitente->cnpj, 
            "ie" => $emitente->ie, 
            "siglaUF" => $emitente->uf,
            "schemes" => "PL_009_V4", 
            "versao" => '4.00', 
            "tokenIBPT" => $emitente->tokenIBPT,
            "CSC" => $emitente->csc, 
            "CSCid" => $emitente->csc_id,
        ];

        $configJson         = json_encode($config);
        $certificadoDigital = file_get_contents($emitente->certificado_a1);
        $certificate        = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);

        $tools = new Tools($configJson, $certificate);
        $tools->model('55');
        
        try {

            $certificate = Certificate::readPfx($certificadoDigital, $emitente->senha_certificado);
            $tools = new Tools($configJson, $certificate);
            $tools->model('55');
            $uf = $emitente->uf;
            $tpAmb = $emitente->ambiente;
            $response = $tools->sefazStatus($uf, $tpAmb);
            
            $stdCl = new Standardize($response);
           
            $std  = $stdCl->toStd();
            $arr  = $stdCl->toArray();
            $json = $stdCl->toJson();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

}