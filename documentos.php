<?php 

//Carrega WP como FW
require_once("../wp-load.php");
$user = wp_get_current_user();
if(!is_user_logged_in()): // Impede acesso de pessoas não autorizadas
      /*** REMEMBER THE PAGE TO RETURN TO ONCE LOGGED IN ***/
	  $_SESSION["return_to"] = $_SERVER['REQUEST_URI'];
      /*** REDIRECT TO LOGIN PAGE ***/
	  header("location: /");
endif;
//Carrega os arquivos de funções
require "inc/function.php";

if(!isset($_GET['id']) OR !isset($_GET['modelo'])){
	echo "<h1>Erro.</h1>";
	
}else{
	$pedido = retornaPedido($_GET['id']);
	//var_dump($pedido);
	
	
	
	
	switch ($_GET['modelo']){
	case 303: // Folha de Rosto	
	
		$file_name='folha_de_rosto.doc';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/force-download');
		header('Content-type: application/vnd.ms-word');
		header('Content-Type: application/download');
		header('Content-Disposition: attachment;filename='.$file_name);
		header('Content-Transfer-Encoding: binary ');

			?>
		<html>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8">
		<body>
		<style type='text/css'>
		.style_01 {
			font-size: 16px;

		}
		.paragrafo{
			text-indent:4em
		}
		p{
			font-size: 18px;
		}
		
		.rodape{
			text-align: center;
			font-size: 12px;
			padding: -10px;
			
		}
		</style>
		<br /><br /><br /> 
		<p class="style_01">À <br />
		Encarregatura de Protocolo <br />
		Sr. Encarregado</p>
		<br />
		<br />
		<br />
		
		<p class="paragrafo">Solicitamos a abertura de processo administrativo com os seguintes dados:</p>

		<br /><br />
		<p>Interessado: <?php echo $pedido['area']." - ".$pedido['cr']; ?></p>

		<br />
		<br />
		<br />
		<p>Assunto: Dispensa de Licitação - contratação da <?php echo $pedido['tipoPessoa']; ?> <b><?php echo $pedido['nome_razaosocial']  ?> </b> para representar <b><?php echo $pedido['nome_razaosocial']  ?> </b> no evento "<b><?php echo $pedido['objeto'] ?></b>" que fará parte da programação cultural da Secretaria de Cultura.</p>

		<br />
		<br />
		<br />

		<p>Atenciosamente,</p>
				<br />
		<br />
		<br />
		<center>
		<p>Santo André, <?php 
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');
		echo strftime('%A, %d de %B de %Y', strtotime('today'));
		
		?>. </center></p>
		
		<br /><br />
		
		<br /><br />
		
		<p><center><?php echo $pedido['usuario']; ?><br />
		<?php echo $pedido['cargo']; ?>	
		</p></center>
		
		<br /><br />
		
		<br /><br />
		
		<p class="rodape">------------------------------------------------------------</p>
		<p class="rodape">Secretaria de Cultura - <?php echo $pedido['area']; ?> <br />
		Praça IV Centenário, 02 - Centro - Paço Municipal - Prédio da Biblioteca - Santo André - SP, <br /> 
		Telefone (11) 4433-0711/ 4433-0632 / email: musica@santoandre.sp.gov.br</p>
		
		
		
		
		
		
		
		
	<?php 
	break;	
	case 304: // OS	
	
	?>
	<!-- CSS para impressão -->
	
	
	
	<link rel="stylesheet" type="text/css" href="print.css" media="print" />

	
	
	
	<table  width="100%" border="1">
	<tr>
	<td rowspan="5" width="15%"><center><img src="images/logo.png" /></center></td>
	
	</tr>
	<tr>
	<td colspan="2"><center>Prefeitura Municipal de Santo André</center><td>
	</tr>
	<tr>
	<td colspan="2"><center>Solicitação de Serviços</center><td>
	</tr>	
		<tr>
		<td><center>Data da Emissão<br /><?php echo date("d/m/Y")?></center></td>
	<td><center>CR Requisitante<br /></center><?php echo $pedido['cr']; ?></td>

	</tr>
	<tr>
	<td colspan="3">Nome da área requisitante: Secretaria de Cultura - <?php echo $pedido['area']; ?></td>
	<tr/>	
	</table>
	<table border="1">
	<tr>
	<td colspan="4"><center>Dotação orçamentária</center></td>

	</tr>

	<tr>
	<td>Cód. Dotação:<br /><?php echo $pedido['cod_dotacao']; ?></td>
	<td>Projeto:<br /><?php echo $pedido['projeto']; ?></td>
	<td>Ficha: <br /> <?php echo $pedido['ficha']; ?></td>
	<td>Sub-elemente Despesa: <br /></td>	
	</tr>
	</tr>
	<tr>
	<td colspan="3">Conta corrente <br />codReduzido/DB</td>
	<td>Fonte: <br />  <?php echo $pedido['fonte']; ?></td>
	</tr>
	<tr>
	<td colspan="3">Nome do Contato <br /><?php echo $pedido['usuario']; ?></td>
	<td>Telefone Contato<br /><?php echo $pedido['telefone']; ?></td>
	</tr>
	<tr><td colspan=4"><center>Serviço e/ou Evento</center></td></tr>	
	<tr>
	<td colspan="4">Data Período do evento: <br /><?php echo $pedido['periodo']; ?></td>
	</tr>	
	<tr>
	<td colspan="4">Local de aplicação do serviço ou evento:<br /><?php echo $pedido['local']; ?></td>
	<tr>
	<td colspan="4">Especificação (a maior quantidade necessária de informações para a correta contratação)</td>
	</tr>
	<tr>
	<td colspan="4">	<p>Contratação de <?php echo $pedido['tipoPessoa']; ?> <b><?php echo $pedido['nome_razaosocial']  ?>, representando com exclusividade as apresentações do(s) seguinte(s) artista(s) <?php  echo $pedido['autor'] ?> para  <?php echo $pedido['tipo']; ?> em <?php echo $pedido['local']; ?> no(s) dia(s)  <?php echo $pedido['periodo']; ?> </b></p>
		<p>Empresa: <?php echo $pedido['nome_razaosocial']  ?></p>
	<p>CNPJ: <?php echo $pedido['cpf_cnpj']  ?></p>
	<p>Endereço: <?php echo $pedido['end']  ?></p>
	<p>Email: <?php echo $pedido['email'];?> </p>
	<p>Valor total: R$<?php echo $pedido['valor'];?> (<?php echo $pedido['valor_extenso']; ?>)</p>
	
	<p>Forma de pagamento: <?php echo $pedido['forma_pagamento'];?> </p>
	<p><?php echo $pedido['banco'];?> </p>		

	</td>
	</tr>

	
	</table>


	
	

	
	<?php /*
	echo '<pre>';
	var_dump($pedido);
	echo '</pre>';
	*/
	?>
	
	
	
	<?php 
	break;	
	case 305: //Justificativa	
	?>

	<?php 
	break;	
	case 306: //Gerencia de compra	

		$file_name='gerencia_de_compras.doc';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/force-download');
		header('Content-type: application/vnd.ms-word');
		header('Content-Type: application/download');
		header('Content-Disposition: attachment;filename='.$file_name);
		header('Content-Transfer-Encoding: binary ');

			?>
		<html>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8">
		<body>
		<style type='text/css'>
		.style_01 {
			font-size: 16px;

		}
		.paragrafo{
			text-indent:4em
		}
		p{
			font-size: 18px;
		}
		
		.rodape{
			text-align: center;
			font-size: 12px;
			padding: -10px;
			
		}
		</style>
		<br /><br /><br /> 
		<p class="style_01">À <br />
		Gerência de Compras e Licitações | <br />
		Sr. Gerente</p>
		<br />

		
		<p class="paragrafo">Com base nas informações e justificativas, retro que adoto, peço a continuidade da contratação nas bases da O.S. que o presente processo trata, com fulcro na Lei Federal n°8.6666/93.</p>


		<br />
		<br />
		<p>Santo André, <?php 
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');
		echo strftime('%A, %d de %B de %Y', strtotime('today'));
		
		?>. </p>
		
		<br /><br />
		
		<br /><br />
		
		<p><center><?php echo $pedido['usuario']; ?><br />
		<?php echo $pedido['cargo']; ?>	
		</p></center>
		
		<br /><br />
		
		<br /><br />
		
		<p>De acordo,</p>

		<p>Santo André, <?php 
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');
		echo strftime('%A, %d de %B de %Y', strtotime('today'));
		
		?>. </p>

		<br /><br />
		
		<br /><br />
			<p>Simone Zárate<br />
			Secretaria de Cultura</p>
		
		<p class="rodape">------------------------------------------------------------</p>
		<p class="rodape">Secretaria de Cultura - <?php echo $pedido['area']; ?> <br />
		Praça IV Centenário, 02 - Centro - Paço Municipal - Prédio da Biblioteca - Santo André - SP, <br /> 
		Telefone (11) 4433-0711/ 4433-0632 / email: musica@santoandre.sp.gov.br</p>
		
		
		
		
	<?php 
	break;	
	case 307: //Ordenador de Despesa	

		$file_name='ordenador_de_despesas.doc';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/force-download');
		header('Content-type: application/vnd.ms-word');
		header('Content-Type: application/download');
		header('Content-Disposition: attachment;filename='.$file_name);
		header('Content-Transfer-Encoding: binary ');

			?>
		<html>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8">
		<body>
		<style type='text/css'>
		.style_01 {
			font-size: 16px;

		}
		.paragrafo{
			text-indent:4em
		}
		p{
			font-size: 18px;
		}
		
		.rodape{
			text-align: center;
			font-size: 12px;
			padding: -10px;
			
		}
		</style>
		<br />

		
		<p>Ref: Contratação de <?php echo $pedido['tipoPessoa'] ?> <?php echo $pedido['nome'] ?> representando com exclusividade, <?php echo $pedido['autor'] ?>.


		<br />
		<br />
		<p>Dotação: <?php echo $pedido['cod_dotacao']?> - Projeto: <?php echo $pedido['projeto']?> - Ficha: <?php echo $pedido['ficha']?> </p>
<br /><br />
<p>Valor: R$ <?php echo $pedido['valor'] ?> ( <?php echo $pedido['valor_extenso'] ?>)		
		
		<br /><br />
		<p>Declaração</p>
		<p>
		Declaro que a despesa pretendida tem a correspondente adequação orçamentária e financeira de acordo com a lei orçamentária anual e possui dotação específica e suficiente, ou seja, está abrangida por crédito genérico, de forma que somadas todas as despesas da mesma espécie, realizadas e a realizar, previstas no programa de trabalho da unidade, não serão ultrapassados os limetes estabelecidos para o exercício, estando adequada também a Lei de Diretrizes Orçamentárias e o Plano Plurianual vigentes.</p>
		</p>
		<p>Santo André, <?php 
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');
		echo strftime('%A, %d de %B de %Y', strtotime('today'));
		
		?>. </p>
		
		<br /><br />
		
		<br /><br />
		

		<br /><br />
		
		<br /><br />
			<p>Simone Zárate<br />
			Secretaria de Cultura<br />
			CPF : 161.410.008-00<br />
			E-mail profissional: szarate@santoandre.sp.gov.br<br />
			E-mail particular: simonezarate@terra.com.br
			</p>
			
		
		<p class="rodape">------------------------------------------------------------</p>
		<p class="rodape">Secretaria de Cultura - <?php echo $pedido['area']; ?> <br />
		Praça IV Centenário, 02 - Centro - Paço Municipal - Prédio da Biblioteca - Santo André - SP, <br /> 
		Telefone (11) 4433-0711/ 4433-0632 / email: musica@santoandre.sp.gov.br</p>
	<?php 
	break;
	case 396:
		$justificativa = "";
		if($pedido['evento_atividade'] == 'atividade'){
			$justificativa .= "Valor a ser reservado para empenho  ".$pedido['obs']." ".$pedido['objeto'] ;	
		}else{
		$justificativa .= "
		Valor a ser reservado para empenho de contratação para ".$pedido['objeto']; 
		}
		
		$justificativa .= " a ser realizado por ".$pedido['nome_razaosocial']." (".$pedido['cpf_cnpj'].") em data/período ".$pedido['periodo']  ;

		if($pedido['local'] != ""){
			$justificativa .= " em ".$pedido['local'];
			
		}
		
	

		$file_name='liberacaodeverba.doc';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/force-download');
		header('Content-type: application/vnd.ms-word');
		header('Content-Type: application/download');
		header('Content-Disposition: attachment;filename='.$file_name);
		header('Content-Transfer-Encoding: binary ');

			?>
		<html>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8">
		<body>
		<style type='text/css'>
		.style_01 {
			font-size: 16px;

		}
		.paragrafo{
			text-indent:4em
		}
		p{
			font-size: 18px;
		}
		
		.rodape{
			text-align: center;
			font-size: 12px;
			padding: -10px;
			
		}
		</style>

		<table border='1'>
		<tr>
		<th>Liberação Nº</th>
		<th>Data</th>
		<th>Justificativa</th>
		<th>Projeto/Ficha</th>
		<th>Dotação</th>
		<th>Fonte</th>
		<th>Valor</th>
		</tr>
		<tr>
		<td></td>	
		<td><?php echo date('d/m/Y'); ?></td>	
		<td><?php echo $justificativa ?></td>	
		<td><?php echo $pedido['projeto']." / ".$pedido['ficha']; ?></td>	
		<td><?php echo $pedido['cod_dotacao']; ?></td>	
		<td><?php echo $pedido['fonte'] ?></td>	
		<td><?php echo $pedido['valor'] ?>	

		</tr>
		
		</table>


		<?php 
	break;
	default:
	?>


	<?php 
   break;	
	}
	
	
}

