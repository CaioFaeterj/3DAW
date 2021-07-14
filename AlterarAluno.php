<?php
require_once('conectabd.php');//conecta bd

//seleciona as colunas da tabela a ser usada
$query="SELECT aluno.cod_aluno,nome_aluno,GROUP_CONCAT(nome_disciplina),nome_curso FROM aluno left join contem ON aluno.cod_aluno=contem.cod_aluno LEFT JOIN disciplina on disciplina.cod_disciplina=contem.cod_disciplina LEFT JOIN curso on curso.cod_curso=contem.cod_curso group by aluno.cod_aluno";


//executa a query pondo dentro de uma variavel
$dados = mysql_query($query) or die(mysql_error());

//poe os dados obtidos da query em um array
$linha = mysql_fetch_assoc($dados);

//calcula quanto dados retornaram
$total = mysql_num_rows($dados);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Relatório Aluno</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
</head>
<body>
<style type="text/css">
h2{
	margin:20px 0px 0px 520px;
}
#botoes{
	margin-left:-10px; 	
}

</style>
<h2>Relatório Alunos</h2>
<table class="table">
<thead>
	<tr>
		<th>Codigo</th>
		<th>Nome</th>
		<th>Curso</th>
		<th>Disciplinas</th>
	</tr>
</thead>
<div class="container" id="botoes">
	<button class="btn btn-primary" type="button">
	  Total Alunos Cadastrados <span class="badge"><?php echo "$total"?></span>
	</button>
	<a href="editar_aluno.php?cod_aluno=".$linha['cod_aluno'].>	
		<button class="btn btn-warning" type="button">Editar</button>
	</a>
	<a href="excluir_aluno.php">
		<button class="btn btn-danger" type="button">Excluir</button>
	</a>	
</div>
<?php 
	//se o numero da variavel for maior que zero, mostra dados
	if($total > 0){

			//inicio do loop

		do{
?>			<tr class="active">
				<td class="col-md-1">
					<input type="checkbox" name="seleciona" value=<?=$linha['cod_aluno']?>> 
				</td>
				<td><?=$linha['nome_aluno']?></td>
				<td><?=$linha['nome_curso']?></td>
				<td><?=$linha['GROUP_CONCAT(nome_disciplina)']?>
				</td>
			</tr>
		
			
<?php	//finaliza o loop 		
		}while($linha = mysql_fetch_assoc($dados));
		//final do if
	}
?>		
</table>
</body>
</html>

<?php
//resultado da busca da memoria
mysql_free_result($dados);

?>
