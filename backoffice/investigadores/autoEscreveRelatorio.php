<?php

include "./locaisPortugueses.php";
include '../../libraries/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
require "../verifica.php";
require "../config/basedados.php";

//verfica o tipo de sessão com base no utilizador
if ($_SESSION["autenticado"] != 'administrador' && $_SESSION["autenticado"] != $_GET["id"]) {
    header("Location: index.php");
    exit; 
}

//ano
if(isset($_SESSION["anoRelatorio"])){
	$year = $_SESSION["anoRelatorio"];
}else{
    $year = date("Y");
}


//$year = 2011;

//opcoes do select de cada pagina folha de calculo
//(apenas a contar da segunda pagina)
$activePageSelectOptions = array();


//array de carateres nao reconhecidos
$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

//credenciais para acesso ao swagger UI da API da cienciaVitae
$login = USERCIENCIA;
$password = PASSWORDCIENCIA;

//iniciar sessao cURL
$ch = curl_init();
$headers = array(
    "Content-Type: application/json",
    "Accept: application/json",
);

curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //adicionar cabecalhos
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //retornar transferencia ativada
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //autenticacao cURL basica ativada
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password"); //user e password para o swagger UI

// 1. Informações  --------------------------------------------------------------------------------------------

//consulta a realizar
$sql = "SELECT * FROM investigadores WHERE id = ?";

$id = $_GET["id"];
$stmt = mysqli_prepare($conn, $sql); //preparar conexao
mysqli_stmt_bind_param($stmt, 'i', $id); //agregar parametro id como inteiro
mysqli_stmt_execute($stmt); //executar consulta mysqli
$result = mysqli_stmt_get_result($stmt); //obter resultado da consulta
$rows = mysqli_fetch_assoc($result); //obter colunas como um array
$ciencia_id = $rows["ciencia_id"]; //ciencia id
$orcid = $rows["orcid"]; //orcid

//nomes do relatorio modelo
$relatorioModelo = "./relatorio.xlsx";
$aux = "./auxiliar.xlsx";

//$url = "https://qa.cienciavitae.pt/api/v1.1/curriculum/" . $ciencia_id . "/person-info?lang=User%20defined";
$url = "https://api.cienciavitae.pt/v1.1/curriculum/" . $ciencia_id . "/person-info?lang=User%20defined";

//adicionar url ao handler cURL
curl_setopt($ch, CURLOPT_URL, $url);

//echo curl_error($ch); //mostrar erros do cURL

$result_curl = curl_exec($ch); //executar cURL e armazenar resultado

curl_close($ch); //fechar handler
$data = json_decode($result_curl); //descodificar JSON da resposta

//nome completo
$nome = $data->{"full-name"};
$nomeArray = explode(" ", $nome);

//ultimo nome
$lastName = $nomeArray[count($nomeArray)-1];
$lastName = strtr($lastName, $unwanted_array);

//primeiro nome
$firstName = $nomeArray[0];
$firstName = strtr($firstName, $unwanted_array);

//nome do novo relatorio
$novoRelatorio = "./".strtoupper($lastName)."_".strtolower($firstName)."_".$year.".xlsx";

//echo $novoRelatorio."</br>";

//echo "$nome</br>";

//::::::::::::ESCREVER NO EXCEL::::::::::::

//tipo de ficheiro
$inputFileType = PHPExcel_IOFactory::identify($relatorioModelo);

//carregar o  relatorio modelo
$reader = PHPExcel_IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($relatorioModelo);

//escritor de folhas de calculo para o relatorio modelo
$writer = PHPExcel_IOFactory::createWriter($spreadsheet, $inputFileType);

$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->getCell('B20')->setValue($nome);
$spreadsheet->getActiveSheet()->getCell('B22')->setValue($ciencia_id);
$spreadsheet->getActiveSheet()->getCell('B23')->setValue($orcid);

// 2. Projetos de investigação  -------------------------------------------------------------------------------------------- --------------------------------------------------------------------------------------------

//consluta a realizar
$sql = "SELECT * FROM projetos AS p,investigadores_projetos AS ip WHERE ip.investigadores_id = ? AND p.id = ip.projetos_id";

$stmt = mysqli_prepare($conn, $sql); //preparar conexao
mysqli_stmt_bind_param($stmt, 'i', $id); //agregar parametro id como inteiro
mysqli_stmt_execute($stmt); //executar consulta mysqli
$result = mysqli_stmt_get_result($stmt); //obter resultado da consulta
$rows = mysqli_fetch_all($result); //obter colunas como um array

//echo json_encode($rows);

//::::::::::::ESCREVER NO EXCEL::::::::::::

//Letra e numero de comeco
$startChar = "C";
$startNumber = 14;

//echo chr(ord($startChar)+1);

$spreadsheet->setActiveSheetIndex(1);

foreach($rows as $row){

    //letra de comeco
    $startChar = "C";
    $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($row[1]);    //1 - Indice da coluna com o acronimo
    $startNumber++;
}


// 3. Publicações --------------------------------------------------------------------------------------------

$publicacoes = null;
if (isset($_POST['publicacoes'])) {
    $publicacoes = $_POST['publicacoes'];
}
$dataArray = json_decode($publicacoes, true);

//::::::::::::ESCREVER NO EXCEL::::::::::::

//tipo de publicacao
$outputType = "";

//localização de publicacao
$outputLocation;

//atributo associado ao tipo de publicacao
$outputAttr = (object)[];

//categoria na folha de calculo
$excelOutCategory = "";

//Letra e numero de comeco
$startChar = "A";
$startNumber = 12;

//echo chr(ord($startChar)+1);

$spreadsheet->setActiveSheetIndex(2);


//opcoes da lista pendente
/*
for($opc = 1; $opc <= 22; $opc++){
    $activePageSelectOptions[$opc-1] = $spreadsheet->getActiveSheet()->getCell("J".$opc)->getFormattedValue();
    // echo "<br> ".($opc-1)." => ".$spreadsheet->getActiveSheet()->getCell("J".$opc)->getFormattedValue();
}
*/
//opcoes da lista excel das pulicações
$excelOptionsPub = array(
    "livros" => "Publicação de livros",
    "catalogo" => "Autor ou coautor de catálogo",
    "coletivas" => "Publicação de capítulos em obras coletivas",
    "revistas" => "Publicação de artigos em revistas científicas/artísticas",
    "recensoes" => "Recensões",
    "relatorios" => "Relatórios",
    "pareceres" => "Pareceres",
    "traducoes" => "Traduções",
    "opiniao" => "Artigos de opinião publicados na imprensa",
    "blogs" => "Contributos para blogs",
    "social" => "Intervenções nos meios de comunicação social",
    "conselhos" => "Participação em conselhos editoriais ou científicos de revistas académicas",
    "edicao" => "Edição de volume de revista como editor convidado (guest-editor)",
    "avaliacao" => "Avaliação de artigos para publicação"
);

//Array que define se a opção no excel contém a localização, "no estrangeiro/em Portugal"
$addLocation = ["livros", "catalogo", "coletivas", "revistas"];
//Arrays que definem se a opção no excel contém a revisão, "(sem revisão por pares)"
$addRevision = ["coletivas", "revistas"];


//Alinhar os tipos de publicações vindo da API com os do excel, usando as chaves do array excelOptionsPub
$APItoExceltypePub = array(
    'journal-article' => $excelOptionsPub['revistas'],
    'journal-issue' => $excelOptionsPub['revistas'], 
    'book' =>  $excelOptionsPub['livros'],
    'edited-book' => $excelOptionsPub['livros'],
    'book-chapter' =>  $excelOptionsPub['coletivas'],
    'book-review' => $excelOptionsPub['coletivas'], 
    'translation' =>  $excelOptionsPub['traducoes'],
    //'dissertation' => '',
    'newspapper-article' => $excelOptionsPub['opiniao'],
    /* 'newsletter-article' => '',
    'encyclopedia-entry' => '',*/
    'magazine-article' => $excelOptionsPub['revistas'],
    //'dictionary-entry' => '',
    'report' => $excelOptionsPub['relatorios'],
    /*'working-paper' => '',
    'manual' => '',
    'online-resource' => '',
    'website' => '',
    'conference-paper' => '',
    'conference-abstract' => '',
    'conference-poster' => '',*/
    'exhibition-catalogue' => $excelOptionsPub['catalogo'],
   /* 'preface-postface' => '', 
    'preprint' => '',
    'artistic-exhibition' => '',
    'audio-recording' => '',
    'musical-composition' => '',
    'musical-performance' => '',
    'radio-tv-program' => '',
    'short-fiction' => '',
    'video-recording' => '',
    'visual-artwork' => '',
    'choreography' => '',
    'curatorial-museum-exhibition' => '',
    'performance-art' => '',
    'patent' => '',
    'software' => '',
    */
);

foreach ($dataArray as $item) {
    $startChar = "A";

    $referenceAPA = $item['dados'];
    $pais = $item['pais'];
    $cidade = $item['cidade'];
    $tipo = $item['tipo'];
    $excelOutCategory = '';
    //Verificar se o tipo de publicação está no array APItoExceltypePub se estiver, logo for um tipo válido processá-lo para adicionar o que falta de dados
    if (isset($APItoExceltypePub[$tipo])) {

        $excelOutCategory =  $APItoExceltypePub[$tipo];
        $excelArrayKey = array_search($excelOutCategory, $excelOptionsPub);;

        //Se estiver no array para colocar a localização, calcula e adiciona-a
        if (in_array($excelArrayKey, $addLocation)) {
            if ($pais == "Portugal" || in_array($cidade, $localizacoesPortuguesas)) {
                $outputLocation = "em Portugal";
            } else {
                //Por default é no estrangeiro se não for colocada a localização
                $outputLocation = "no estrangeiro";
            }
            $excelOutCategory .= " $outputLocation";
        }
        if (in_array($excelArrayKey, $addRevision)) {
            //Adicionar sempre sem revisão por default porque os dados do ciencia vitae não tem divisão entre os que tem e não tem
            $excelOutCategory .= " (sem revisão por pares)";
        }
    } else {
        //Se o tipo não for válido passar à frente
        continue;
        //$excelOutCategory = $tipo;
    }

    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($excelOutCategory);

    //::::::referencia bibliografica::::::

    //caracter de comeco
    $startChar = chr(ord($startChar) + 1);
    $wizard = new PHPExcel_Helper_HTML;

    //Transformar de HTML para Rich Text
    $richTextAPA = $wizard->toRichTextObject(
        mb_convert_encoding(html_entity_decode($referenceAPA), 'HTML-ENTITIES', 'UTF-8')
    );

    //URL para referencia
    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($richTextAPA);
    $spreadsheet->getActiveSheet()->getStyle($startChar . $startNumber)->getAlignment()->setWrapText(true);

    //::::::URL, DOI::::::

    $pattern = '@((https?://)?([-\\w]+\\.[-\\w\\.]+)+\\w(:\\d+)?(/([-\\w/_\\.]*(\\?\\S+)?)?)*)@';

    // Use preg_match to find the URL in the text
    if (preg_match($pattern, urldecode($richTextAPA), $urls)) {
        $urlPublic = $urls[0];
    }else{
        $urlPublic = '';
    }

    //caracter de comeco
    $startChar = chr(ord($startChar) + 1);

    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($urlPublic);

    $startNumber++;
}


// 4. Eventos e conferencias  --------------------------------------------------------------------------------------------  

//::::::::::::ESCREVER NO EXCEL::::::::::::
$startNumber = 16;


//categoria de servico
$serviceCategory = "";

//atributo do servico
$serviceAttr = (object)[];

//dados para escrever na folha

$categoriaEvento = "";

$refBibliografica = "";

$spreadsheet->setActiveSheetIndex(3);

//opcoes da lista pendente

$activePageSelectOptions = array();
/*
for ($opc = 1; $opc <= 15; $opc++) {

    $activePageSelectOptions[$opc - 1] = $spreadsheet->getActiveSheet()->getCell("H" . $opc)->getFormattedValue();
}
*/

$excelOptionsConf = array(
    "oradorprincipal" => "Comunicações em conferências nacionais/internacionais enquanto orador principal",
    "revisaoporpares" => "Comunicações em conferências nacionais/internacionais com admissão sujeita a revisão por pares",
    "convite" => "Comunicações em conferências nacionais/internacionais por convite",
    "outras" => "Outras comunicações",
    "organizacao" => "Organização de conferências de âmbito nacional/internacional",
    "conselho" => "Participação no Conselho Científico de eventos científicos de âmbito nacional/internacional",
    "organizacaoart" => "Criação/apresentação/organização de exposições e outros eventos artísticos de âmbito nacional/internacional",
    "participacaoart" => "Participação em exposições e outros eventos artísticos de âmbito nacional/internacional",
);
$addLocationConfPlurar = ["oradorprincipal", "revisaoporpares", "convite"];
$addLocationConfSingular = ["organizacao", "conselho", "organizacaoart", "participacaoart"];

//echo implode("</br>",$activePageSelectOptions);

//Alinhar os tipos de publicações vindo da API com os do excel, usando as chaves do array excelOptionsPub
$APItoExceltypeConf = array(
    // 'journal-issue' => '', 
    // 'edited-book' => '', 
    // 'book-review' => '', 
    /* 'dissertation' => '',
    'newspapper-article' => '',
    'newsletter-article' => '',
    'encyclopedia-entry' => '',
    'magazine-article' => '',
    'dictionary-entry' => '',
    'report' => '',
    'working-paper' => '',
    'manual' => '',
    'online-resource' => '',
    'website' => '',
    'conference-paper' => '',
    'conference-abstract' => '',
    'conference-poster' => '',
    'exhibition-catalogue' => '',
    'preface-postface' => '', 
    'preprint' => '',
    'artistic-exhibition' => '',*/
    'audio-recording' => $excelOptionsConf['organizacaoart'],
    //'musical-composition' => '',
    'musical-performance' => $excelOptionsConf['organizacaoart'],
    'radio-tv-program' => $excelOptionsConf['outras'],
    /*'short-fiction' => '',
    'video-recording' => '',
    'visual-artwork' => '',
    'choreography' => '',
    'curatorial-museum-exhibition' => '',
    'performance-art' => '',
    'patent' => '',
    'software' => '',
    */
);
 
$dataArray = json_decode($publicacoes, true);

foreach ($dataArray as $item) {
    $startChar = "A";

    $referenceAPA = $item['dados'];
    $pais = $item['pais'];
    $cidade = $item['cidade'];
    $tipo = $item['tipo'];
    $excelOutCategory = '';
    //Verificar se o tipo de evento ou conferência está no array APItoExceltypeConf se estiver, logo for um tipo válido processá-lo para adicionar o que falta de dados
    if (isset($APItoExceltypeConf[$tipo])) {
        $excelOutCategory =  $APItoExceltypeConf[$tipo];
        $excelArrayKey = array_search($excelOutCategory, $excelOptionsConf);;

        if (in_array($excelArrayKey, $addLocationConfPlurar)) {//Se estiver no array para colocar a localização em plurar, calcula e adiciona-a
            if ($pais == "Portugal" || in_array($cidade, $localizacoesPortuguesas)) {
                $ambit = "nacionais";
            } else {
                //Por default é no estrangeiro se não for colocada a localização
                $ambit = "internacionais";
            }
            $excelOutCategory = str_replace("nacionais/internacionais", $ambit, $excelOutCategory);
        }else if(in_array($excelArrayKey, $addLocationConfSingular)){ //Se estiver no array para colocar a localização em singular, calcula e adiciona-a
            if ($pais == "Portugal" || in_array($cidade, $localizacoesPortuguesas)) {
                $ambit = "nacional";
            } else {
                //Por default é no estrangeiro se não for colocada a localização
                $ambit = "internacional";
            }
            $excelOutCategory = str_replace("nacional/internacional", $ambit, $excelOutCategory);
        }
    } else {
        //Se o tipo não for válido passar à frente
        continue;
        //$excelOutCategory = $tipo;
    }
    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($excelOutCategory);

    //::::::referencia bibliografica::::::

    //caracter de comeco
    $startChar = chr(ord($startChar) + 1);
    $wizard = new PHPExcel_Helper_HTML;

    //Transformar de HTML para Rich Text
    $richTextAPA = $wizard->toRichTextObject(
        mb_convert_encoding(html_entity_decode($referenceAPA), 'HTML-ENTITIES', 'UTF-8')
    );

    //URL para referencia
    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($richTextAPA);
    $spreadsheet->getActiveSheet()->getStyle($startChar . $startNumber)->getAlignment()->setWrapText(true);

    //caracter de comeco
    $startChar = chr(ord($startChar) + 1);

    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($urlPublic);

    $startNumber++;
}

/* API
//iniciar sessao cURL
$ch = curl_init();
$headers = array(
    "Content-Type: application/json",
    "Accept: application/json",
);

curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //adicionar cabecalhos
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //retornar transferencia ativada
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //autenticacao cURL basica ativada
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password"); //user e password para o swagger UI

//$url = "https://qa.cienciavitae.pt/api/v1.1/curriculum/" . $ciencia_id . "/service?lang=User%20defined";
$url = "https://api.cienciavitae.pt/v1.1/curriculum/" . $ciencia_id . "/service?lang=User%20defined";

//adicionar url ao handler cURL
curl_setopt($ch, CURLOPT_URL, $url);

//mostrar erros
//echo curl_error($ch);

//resultado
$result_curl = curl_exec($ch); //executar cURL e armazenar resultado

curl_close($ch); //fechar handler
$data = json_decode($result_curl); //descodificar JSON da resposta
foreach ($data->{"service"} as $row) {

    //letra de comeco
    $startChar = "A";

    //iterador
    $i = 0;

    //encontrar a propriedade nao nula correspondente ao tipo de servico
    foreach ($row as $attr) {
        if ($attr != null && $i >= 1) {
            $serviceAttr = $attr;
            break;
        }
        $i++;
    }
    //S202 = Participação em evento, S201 = Organização de evento
    if(@$row->{"service-category"} != null && 
    (@$row->{"service-category"}->{"code"} == "S202" || 
    @$row->{"service-category"}->{"code"} == "S201") && 
    (@$serviceAttr->{"end-date"}->{"year"} == $year . "" ||
    @$serviceAttr->{"start-date"}->{"year"} == $year . "")){


        //::::::categoria do evento::::::
        $serviceCategory = @$row->{"service-category"}->{"value"};

        $categoriaEvento = $serviceCategory.": ".@$serviceAttr->{"end-date"}->{"year"};
        
        $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($categoriaEvento);

        //::::::referencia bibliografica::::::

        $startChar = chr(ord($startChar) + 1);

        $refBibliografica = @$serviceAttr->{"event-description"};

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($refBibliografica);

        $startNumber++;        

    }

}*/

// 5. Patentes  --------------------------------------------------------------------------------------------

$patentes = null;
if (isset($_POST['patentes'])) {
    $patentes = $_POST['patentes'];
}

$spreadsheet->setActiveSheetIndex(4);

$dataArray = json_decode($patentes, true);
//letra de comeco
$startChar = "A";
$startNumber = 16;

foreach ($dataArray as $item) {
    $referenceAPA = $item['dados'];
    $wizard = new PHPExcel_Helper_HTML;
    //Transformar de HTML para Rich Text
    $richTextAPA = $wizard->toRichTextObject(
        mb_convert_encoding(html_entity_decode($referenceAPA), 'HTML-ENTITIES', 'UTF-8')
    );

    $spreadsheet->getActiveSheet()->getCell($startChar . $startNumber)->setValue($richTextAPA);
    $startNumber++;
}


// 6. Trabalho de orientação  --------------------------------------------------------------------------------------------

//iniciar sessao cURL
$ch = curl_init();
$headers = array(
    "Content-Type: application/json",
    "Accept: application/json",
);

curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //adicionar cabecalhos
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //retornar transferencia ativada
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //autenticacao cURL basica ativada
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password"); //user e password para o swagger UI

//$url = "https://qa.cienciavitae.pt/api/v1.1/curriculum/" . $ciencia_id . "/service?lang=User%20defined";
$url = "https://api.cienciavitae.pt/v1.1/curriculum/" . $ciencia_id . "/service?lang=User%20defined";

//adicionar url ao handler cURL
curl_setopt($ch, CURLOPT_URL, $url);

//mostrar erros
//echo curl_error($ch);

//resultado
$result_curl = curl_exec($ch); //executar cURL e armazenar resultado

curl_close($ch); //fechar handler
$data = json_decode($result_curl); //descodificar JSON da resposta

//::::::::::::ESCREVER NO EXCEL::::::::::::

//categoria de servico
$serviceCategory = "";

//atributo do servico
$serviceAttr = (object)[];

//tipo de graduacao
$degreeType = "";

//:::dados a escrever:::

$tipologiaOrientacao = "";

$nomeAluno = "";

$tituloProjeto = "";

$instituicao = "";

$spreadsheet->setActiveSheetIndex(5);

//opcoes da lista pendente

$activePageSelectOptions = array();

for($opc = 1; $opc <= 5; $opc++){

    $activePageSelectOptions[$opc-1] = $spreadsheet->getActiveSheet()->getCell("G".$opc)->getFormattedValue();

}

//Letra e numero de comeco
$startChar = "A";
$startNumber = 14;

foreach($data->{"service"} as $row){

    //letra de comeco
    $startChar = "A";

    //iterador
    $i = 0;

    //encontrar a propriedade nao nula correspondente ao tipo de servico
    foreach($row as $attr){
        if($attr != null && $i >= 1){
            $serviceAttr = $attr;
            break;
        }
        $i++;
    }

    //S110 = Orientação
    //Obter todas as orientações em curso ou concluidas no ano do relátorio
    if (
        @$row->{"service-category"} != null && @$row->{"service-category"}->{"code"} == "S110" &&
        (@$serviceAttr->{"start-date"}->{"year"} >= $year . "" || @$serviceAttr->{"end-date"}->{"year"} <= $year . "")
    ) {

        //::::::tipologia de orientacao::::::

        $serviceCategory = @$row->{"service-category"}->{"value"};
        $degreeType = $serviceAttr->{"degree-type"}->{"code"};
        switch ($degreeType) {
            case "M":
                if ($serviceAttr->{"end-date"}->{"year"} == $year) {
                    $pageOption = $activePageSelectOptions[0]; //Orientação de dissertações de mestrado concluídas
                } else {
                    $pageOption = $activePageSelectOptions[1]; //Orientação de dissertações de mestrado em curso
                }
                break;
            case "D":
                if ($serviceAttr->{"end-date"}->{"year"} == $year) {
                    $pageOption = $activePageSelectOptions[2]; //Orientação de dissertações de doutoramento concluídas
                } else {
                    $pageOption = $activePageSelectOptions[3]; //Orientação de dissertações de doutoramento em curso
                }
                break;
            default:
                $pageOption = $activePageSelectOptions[4];
                break;
        }

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($pageOption);

        //::::::nome do aluno::::::

        $startChar = chr(ord($startChar) + 1);

        $nomeAluno = @$serviceAttr->{"student-name"}->{"value"};

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($nomeAluno);

        //::::::titulo do projeto::::::

        $startChar = chr(ord($startChar) + 1);

        $tituloProjeto = @$serviceAttr->{"thesis-title"};

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($tituloProjeto);

        //::::::instituicao::::::

        $startChar = chr(ord($startChar) + 1);

        $instituicao = @$serviceAttr->{"academic-institutions"}->{"institution"}[0]->{"institution-name"};

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($instituicao);

        $startNumber++;
    }
}


// 7. Prémios e distinções  --------------------------------------------------------------------------------------------

//iniciar sessao cURL
$ch = curl_init();
$headers = array(
    "Content-Type: application/json",
    "Accept: application/json",
);

curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //adicionar cabecalhos
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //retornar transferencia ativada
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); //autenticacao cURL basica ativada
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password"); //user e password para o swagger UI

//$url = "https://qa.cienciavitae.pt/api/v1.1/curriculum/" . $ciencia_id . "/distinction?lang=User%20defined";
$url = "https://api.cienciavitae.pt/v1.1/curriculum/" . $ciencia_id . "/distinction?lang=User%20defined";

//adicionar url ao handler cURL
curl_setopt($ch, CURLOPT_URL, $url);

//mostrar erros
//echo curl_error($ch);

//resultado
$result_curl = curl_exec($ch); //executar cURL e armazenar resultado

curl_close($ch); //fechar handler
$data = json_decode($result_curl); //descodificar JSON da resposta

//::::::::::::ESCREVER NO EXCEL::::::::::::

//dados para escrever na folha

$categoriaPremio = "";

$descricao = "";

$spreadsheet->setActiveSheetIndex(6);

//Letra e numero de comeco
$startChar = "A";
$startNumber = 16;

//opcoes da lista pendente

$activePageSelectOptions = array();

for($opc = 1; $opc <= 3; $opc++){

    $activePageSelectOptions[$opc-1] = $spreadsheet->getActiveSheet()->getCell("C".$opc)->getFormattedValue();

}

foreach($data->{"distinction"} as $row){

    $startChar = "A";

    if(@$row->{"effective-date"} != null && 
    @$row->{"effective-date"} == "".$year){

        //:::categoria do premio:::
        $categoriaPremio = @$row->{"distinction-type"}->{"value"};

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($categoriaPremio);

        //:::descricao/nome:::

        $startChar = chr(ord($startChar) + 1);

        $descricao = @$row->{"distinction-name"};

        $spreadsheet->getActiveSheet()->getCell($startChar.$startNumber)->setValue($descricao);

        $startNumber++;

    }

    

}




// 8. Outras atividades  --------------------------------------------------------------------------------------------

//::::::::::::GUARDAR RELATORIO::::::::::::

//Voltar à primeira pagina antes de guardar
$spreadsheet->setActiveSheetIndex(0);
$writer->save($novoRelatorio);

//::::::::::::DESCARREGAR RELATORIO::::::::::::
/* não funciona no php do site de produção
$finfo = finfo_open(FILEINFO_MIME_TYPE); 
$mime =  finfo_file($finfo, basename($novoRelatorio));
finfo_close($finfo);*/
$mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
header('Content-Type: '.$mime.'; charset=UTF-8');
header('Content-Disposition: attachment;  filename="'.basename($novoRelatorio).'"');
header('Content-Length: ' . filesize(basename($novoRelatorio)));
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

ob_get_clean();
echo file_get_contents(basename($novoRelatorio));
ob_end_flush();


//::::::::::::APAGAR RELATORIO DO SERVIDOR::::::::::::
unlink($novoRelatorio);
