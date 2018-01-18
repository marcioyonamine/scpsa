<?php include "header.php"; ?>
<?php 
if(isset($_GET['p'])){
	$p = $_GET['p'];
}else{
	$p = 'inicio';
}

?>
  <body>
  
  <?php include "menu/me_relatorio.php"; ?>
 
        <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
	<h1>Relatório</h1>		
<?php 
switch($p){
case 'inicio':
?>
  
<?php 
break;
case 'atualiza_categoria_ranking':  
if(isset($_GET['id_mapas'])){
	$id_mapas = $_GET['id_mapas'];
?>		  
<?php 
				$sql_sel_ins = "SELECT inscricao FROM ava_inscricao WHERE id_mapas = '$id_mapas'";
				$res = $wpdb->get_results($sql_sel_ins,ARRAY_A);
				
				
				for($i = 0; $i < count($res); $i++){
					$id_insc = $res[$i]['inscricao'];
					$sel = "SELECT descricao,inscricao FROM ava_inscricao WHERE inscricao = '$id_insc'";	
					$json = $wpdb->get_row($sel,ARRAY_A);	
					$res_json = converterObjParaArray(json_decode(($json['descricao'])));
					$filtro = $res_json['3.2 - Categoria'];
					$sql_atualiza = "UPDATE ava_ranking SET filtro = '$filtro' WHERE inscricao = '$id_insc'";
					if($wpdb->query($sql_atualiza)){
						echo "$id_insc - Filtro atualizado.<br />";
					}else{
						echo "$id_insc - $sql_atualiza.<br />";
						
					}
				}
					
				}else{
					?>
					 <div class="container">
        <div class="row">    
				<div class="col-md-offset-2 col-md-8">
					<h1>Não há Edital indicado</h1>
				</div>
        </div>
		</div>
			<?php 	}

							
				?>


  

<?php 
break;
case 'orcamento':  
$projeto = array();
$w = 0;
$orcamentototal = orcamentoTotal(2018);
//var_dump($orcamentototal);
?>  

          <h1>Orçamentário</h1>
		<!--<div><select>
		<option></option>
		<input class="btn btn-sm btn-default" type="submit" value="Filtrar" />
		</select></div>-->
		<?php $orcamento = orcamentoTotal(2018);
		//var_dump($orcamento); ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
				<th></th>
				<th></th>
     			</tr>
              </thead>
              <tbody>
				<tr>
				<td>Orçamento Aprovado</td>
				<td><?php echo dinheiroParaBr($orcamento['orcamento']); ?></td>
				</tr>
				<tr>
				<td>Contigenciado</td>
				<td><?php echo dinheiroParaBr($orcamento['contigenciado']); ?></td>
				</tr>
				<tr>
				<td>Descontigenciado</td>
				<td><?php echo dinheiroParaBr($orcamento['descontigenciado']); ?></td>
				</tr>
				<tr>
				<td>Suplementado</td>
				<td><?php echo dinheiroParaBr($orcamento['suplementado']); ?></td>
				</tr>
				<tr>
				<td>Liberado</td>
				<td><?php echo dinheiroParaBr($orcamento['liberado']); ?></td>
				</tr>
								<tr>
				<td>Planejado</td>
				<td><?php echo dinheiroParaBr($orcamento['planejado']); ?></td>
				</tr>
								<tr>
				<td>Executado</td>
				<td></td>
				</tr>
				<tr>
				<td>Saldo </td>
				<td><?php echo dinheiroParaBr($orcamento['total']); ?></td>
				<td></td>
				</tr>
				<tr>
				<td>Saldo Planejado</td>
				<td><?php //echo dinheiroParaBr($orcamento['']); ?></td>

				</tr>				
				</tbody>
            </table>
          </div> 

		
		
		
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
				<th width="25%">Programa</th>
				<th>Projeto</th>
				<th>Valor</th>
				<th>Nota</th>
				<th>Responsável</th>
				<th>Projeto/Ficha</th>
				<th>Descrição</th>

     			</tr>
              </thead>
              <tbody>
				<?php 
				$programa = array();
				$sel_programa = "SELECT * FROM sc_tipo WHERE abreviatura = 'programa'";
				$res_programa = $wpdb->get_results($sel_programa,ARRAY_A);
				for($i = 0; $i < count($res_programa); $i++){
				$total_programa = 0;
				?>
				<tr>
				<td><strong><?php echo $res_programa[$i]['tipo'] ?></strong></td>
				<td></td>
				<td></td>				
				<td></td>				
				<td></td>				
				<td></td>				
				<td></td>				

				</tr>
					<?php 
					$sel_projeto = "SELECT * FROM sc_tipo WHERE abreviatura = 'projeto'";
					$res_projeto = $wpdb->get_results($sel_projeto,ARRAY_A);
					//var_dump($res_projeto);	
					for($k = 0; $k < count($res_projeto); $k++){
						$pro_json = json_decode(utf8_encode($res_projeto[$k]['descricao']),true);
						//var_dump($pro_json);
						if($pro_json['programa'] == $res_programa[$i]['id_tipo']){
							
					?>
					<tr>
					<td></td>
					<td><?php echo $res_projeto[$k]['tipo'] ?></td>
					<td>
					<?php 
						$sql_orc = "SELECT valor,obs,idPai FROM sc_orcamento WHERE planejamento ='".$res_projeto[$k]['id_tipo']."' AND publicado ='1'";
						$res_orc = $wpdb->get_row($sql_orc,ARRAY_A);
						
						if($res_orc['idPai'] != NULL){
							$orc = recuperaDados("sc_orcamento",$res_orc['idPai'],"id");
						}else{
							$orc = array(
							'obs' => '',
							'projeto' => '',
							'ficha' => '',
							'descricao' => ''
							);
						}
						//var_dump($orc);
						echo dinheiroParaBr($res_orc['valor']);
						if($res_orc['valor'] != NULL){
							$total_programa = $total_programa + $res_orc['valor'];
						}
						if($orc == NULL){
							$res_orc = array(
							'obs' => '',
							'projeto' => '',
							'ficha' => '',
							'descricao' => ''
							);
						}
						?>
					</td>
					<td><?php echo $res_orc['obs']; ?></td>
									<td><?php echo $pro_json['responsavel'];//var_dump($orc); ?></td>				
				<td><?php echo $orc['projeto'] ?> / <?php echo $orc['ficha'] ?></td>				
				<td><?php echo $orc['descricao'] ?></td>				

					</tr>
					<?php } 
					
					$projeto[$w]['nome'] = $res_projeto[$k]['tipo'];
					if($res_orc['valor'] == NULL){
						$projeto[$w]['valor'] = 0;
					}else{
					$projeto[$w]['valor'] = $res_orc['valor'];
					}
					$w++;
					}


					
					
					?>
				<?php 
				?>
				<tr>
				<td><strong>Total do Programa:</strong></td>
				<td></td>
				<td><strong><?php echo dinheiroParaBr($total_programa); ?></strong></td>
				<td></td>
				<td></td>				
				<td></td>				
				<td></td>				

				<?php 
				$programa[$i]['programa'] = $res_programa[$i]['tipo'];
				$programa[$i]['valor'] = $total_programa;
				?>
				</tr>
				<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
								<td></td>				
				<td></td>				
				<td></td>				

				</tr>
				<?php 
				}


				?>

				</tbody>
            </table>
          </div> 

<style>

.bar {
  fill: steelblue;
}

.bar:hover {
  fill: brown;
}

.axis--x path {
  display: none;
}

</style>



		  </div>

<div id="pieChart" align="center"></div>

<!--<div id="pieChart2" align="center"></div>-->
  <div>
	<?php 
	//var_dump($programa);
	?>
</div>



<script src="https://d3js.org/d3.v4.js"></script>
<script src="visual/d3/d3pie.js"></script>
<script>
var pie = new d3pie("pieChart", {
	"header": {
		"title": {
			"text": "Planejamento por Programa",
			"fontSize": 24,
			"font": "open sans"
		},
		"subtitle": {
			"text": "",
			"color": "#999999",
			"fontSize": 12,
			"font": "open sans"
		},
		"titleSubtitlePadding": 9
	},
	"footer": {
		"color": "#999999",
		"fontSize": 10,
		"font": "open sans",
		"location": "bottom-left"
	},
	"size": {
		"canvasWidth": 800,
		"pieOuterRadius": "90%"
	},
	"data": {
		"sortOrder": "value-desc",
		"content": [
		
		<?php for ($i = 0; $i < count($programa); $i++){ ?>
			{
				"label": "<?php echo $programa[$i]['programa']?>",
				"value": <?php echo $programa[$i]['valor'] ?>,
				"color": "<?php echo '#' . dechex(rand(256,16777215)) ?>"
			},
		<?php } ?>
			{
				"label": "Em planejamento",
				"value": <?php echo $orcamentototal['planejado']; ?>,
				"color": "<?php echo '#' . dechex(rand(256,16777215)) ?>"			
			}
		]
	},
	"labels": {
		"outer": {
			"pieDistance": 32
		},
		"inner": {
			"hideWhenLessThanPercentage": 3
		},
		"mainLabel": {
			"fontSize": 11
		},
		"percentage": {
			"color": "#ffffff",
			"decimalPlaces": 0
		},
		"value": {
			"color": "#adadad",
			"fontSize": 11
		},
		"lines": {
			"enabled": true
		},
		"truncation": {
			"enabled": true
		}
	},
	"effects": {
		"pullOutSegmentOnClick": {
			"effect": "linear",
			"speed": 400,
			"size": 8
		}
	},
	"misc": {
		"gradient": {
			"enabled": true,
			"percentage": 100
		}
	}
});
</script>
<script>
var pie = new d3pie("pieChart2", {
	"header": {
		"title": {
			"text": "Planejamento por Projeto",
			"fontSize": 24,
			"font": "open sans"
		},
		"subtitle": {
			"text": "",
			"color": "#999999",
			"fontSize": 12,
			"font": "open sans"
		},
		"titleSubtitlePadding": 9
	},
	"footer": {
		"color": "#999999",
		"fontSize": 10,
		"font": "open sans",
		"location": "bottom-left"
	},
	"size": {
		"canvasWidth": 600,
		"pieOuterRadius": "90%"
	},
	"data": {
		"sortOrder": "value-desc",
		"content": [
		
		<?php for ($i = 0; $i < count($projeto); $i++){ ?>
			{
				"label": "<?php echo $projeto[$i]['nome']?>",
				"value": <?php echo $projeto[$i]['valor'] ?>,
				"color": "<?php echo '#' . dechex(rand(256,16777215)) ?>"
			},
		<?php } ?>

		]
	},
	"labels": {
		"outer": {
			"pieDistance": 32
		},
		"inner": {
			"hideWhenLessThanPercentage": 3
		},
		"mainLabel": {
			"fontSize": 11
		},
		"percentage": {
			"color": "#ffffff",
			"decimalPlaces": 0
		},
		"value": {
			"color": "#adadad",
			"fontSize": 11
		},
		"lines": {
			"enabled": true
		},
		"truncation": {
			"enabled": true
		}
	},
	"effects": {
		"pullOutSegmentOnClick": {
			"effect": "linear",
			"speed": 400,
			"size": 8
		}
	},
	"misc": {
		"gradient": {
			"enabled": true,
			"percentage": 100
		}
	}
});
</script>


<?php 
break;
case 'projeto2':  

?>  
 <script type="text/javascript" src="visual/vis.js"></script>
  <link href="visual/vis-network.min.css" rel="stylesheet" type="text/css" />

  <style type="text/css">
    #mynetwork {
      width: 1200px;
      height: 1200px;
      border: 1px solid lightgray;
    }
  </style>
<div id="mynetwork"></div>

<script type="text/javascript">
  // create an array with nodes
  var nodes = new vis.DataSet([
	<?php 
	$sel_programa = "SELECT * FROM sc_tipo WHERE abreviatura = 'programa'";
	$res_programa = $wpdb->get_results($sel_programa,ARRAY_A);
	for($i = 0; $i < count($res_programa); $i++){
	?>
    {id: <?php echo $res_programa[$i]['id_tipo']; ?>, label: '<?php echo $res_programa[$i]['tipo'] ?>', color: 'red', widthConstraint: { minimum: 120 }},
	<?php 
		$sel_projeto = "SELECT * FROM sc_tipo WHERE abreviatura = 'projeto'";
		$res_projeto = $wpdb->get_results($sel_projeto,ARRAY_A);
		//var_dump($res_projeto);	
		for($k = 0; $k < count($res_projeto); $k++){
			$pro_json = json_decode(utf8_encode($res_projeto[$k]['descricao']),true);
			//var_dump($pro_json);
			if($pro_json['programa'] == $res_programa[$i]['id_tipo']){ ?>
			{id: <?php echo $res_projeto[$k]['id_tipo']; ?>, label: '<?php echo $res_projeto[$k]['tipo'] ?>', color: 'yellow', widthConstraint: { minimum: 120 } },
			<?php
			}
		}
	}		
	?>
	
    {id: 200, label: 'Node 5'}
  ]);

  // create an array with edges
  var edges = new vis.DataSet([
  	<?php 
	$sel_programa = "SELECT * FROM sc_tipo WHERE abreviatura = 'programa'";
	$res_programa = $wpdb->get_results($sel_programa,ARRAY_A);
	$id = 1;
	for($i = 0; $i < count($res_programa); $i++){
	?>
	<?php 
		$id++;
		$sel_projeto = "SELECT * FROM sc_tipo WHERE abreviatura = 'projeto'";
		$res_projeto = $wpdb->get_results($sel_projeto,ARRAY_A);
		//var_dump($res_projeto);	
		for($k = 0; $k < count($res_projeto); $k++){
			$pro_json = json_decode(utf8_encode($res_projeto[$k]['descricao']),true);
			//var_dump($pro_json);
			if($pro_json['programa'] == $res_programa[$i]['id_tipo']){ ?>
			    {from: <?php echo $res_projeto[$k]['id_tipo'] ?> , to: <?php echo $res_programa[$i]['id_tipo']; ?>},
			
			<?php $id++;
				}
		}
	}		
	?>
    
    {from: 99, to: 99}
  ]);

  // create a network
  var container = document.getElementById('mynetwork');
  var data = {
    nodes: nodes,
    edges: edges
  };
  var options = {};
  var network = new vis.Network(container, data, options);
</script>
<?php 
break;
case 'culturaz':  

function chamaAPI($url,$data){
	$get_addr = $url.'?'.http_build_query($data);
	echo $get_addr;
	$ch = curl_init($get_addr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	$evento = json_decode($page,true);
	return $evento;
	
}

	
	$data = array(
 	"@select" => "id,createTimestamp",
	"@order" => "createTimestamp DESC",
	"@limit" => 1
	//"owner" => "IN(870,105)",
	//"isVerified" => TRUE
	//"@order" => "id ASC"
	);
	
	
	$teste = chamaAPI("http://culturaz.santoandre.sp.gov.br/api/agent/find/",$data);
	
?>		  
<div class="container">
        <div class="row">    
				<div class="col-md-offset-2 col-md-8">
					<h1>CulturAZ</h1>
				</div>
        </div>

        <div class="row">    
				<div class="col-md-offset-2 col-md-8">
					<h2>Total de Agentes</h2>
					<?php 
					echo $teste[0]['id'];
					echo "<pre>";
					var_dump($teste);
					echo "</pre>";
					?>
				</div>
        </div>
        <div class="row">    
				<div class="col-md-offset-2 col-md-8">
					<h2>Novos Agentes</h2>
				</div>
        </div>
</div>




		
<?php 
//break;
//case '':  
?>		  
<!--
					 <div class="container">
        <div class="row">    
				<div class="col-md-offset-2 col-md-8">
					<h1>Não há Edital indicado</h1>
				</div>
        </div>
		</div>
-->
<?php 
break;
}//fim da switch
?>		  
		  
        </main>
      </div>
    </div>
	
<?php 
include "footer.php";
?>