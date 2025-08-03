<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de Dados</title>

<style>
body{
font-family: sans-serif;
margin: 20px;
}

table{
    width: 100%;
    border-collapse: collapse;
}

th,td{
    border: 1px solid #ddd;
    text-align: left;
}

a{
text-decoration: none;
color: #007bff;
border: 1px solid #ddd;
border-radius: 3px;
padding: 5px 10px;
}

a.ativo{
background: #007bff;
color: white;

}



</style>


</head>
<body>
<?php
$dados_con = "mysql:host=localhost;dbname=cadastro;charset=utf8mb4";
$usuario = "root";
$senha = "123456";
try{
   $con = new PDO($dados_con, $usuario,$senha);
}catch(PDOException $e){
die("Erro:". $e->getMessage());
}

//paginação
$registros_por_pagina = 5;
$pagina = max(1, filter_input(INPUT_GET,'pagina', FILTER_VALIDATE_INT)?:1); 
$inicio = ($pagina - 1) * $registros_por_pagina;

//contar registros e trazer esses números
$stmt = $con->query("SELECT COUNT(*) FROM pessoas");
$total_de_registros = $stmt->fetchColumn();
$total_paginas = ceil($total_de_registros / $registros_por_pagina);

//buascar dos dados

$stmt_dados = $con->prepare("SELECT nome, email, contato_telefone FROM pessoas
LIMIT :inicio,:registros");

$stmt_dados->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt_dados->bindParam(':registros', $registros_por_pagina, PDO::PARAM_INT);
$stmt_dados->execute();
$dados = $stmt_dados->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Dados Cadastrados</h1>
    
<?php if($dados) { ?>

        <table>
        <tr><th>Nome:</th><th>Email:</th><th>Telefone</th></tr>
                <?php foreach($dados as $linha) { ?>
                            <tr>
                                    <td><?=$linha['nome']?></td>
                                    <td><?=$linha['email']?></td>
                                    <td><?=$linha['contato_telefone']?></td>
                     
                            </tr>

                        <?php } ?>
        </table>

 <?php }else{  ?>

<p> Nenhum registro encontrado!</p>

    <?php } ?>                


<div style="margin-top:20px; text-align:center;">

<?php if($total_paginas > 1) { ?>

    <a href="?pagina=1">Primeira</a>

              <?php  for($i=1; $i<=$total_paginas; $i++) { ?>

                <a href="?pagina=<?=$i?>" class="<?=($i==$pagina) ? 'ativo':''?>"><?=$i?></a>

                   <?php } ?>

            <a href="?pagina=<?=$total_paginas?>">Última</a>

  <?php  } ?>
</div>
</body>
</html>