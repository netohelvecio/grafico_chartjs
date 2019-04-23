<?php
include ('conexao.php');
$resposta1 = 0;
$resposta2 = 0;
$resposta3 = 0;
$resposta4 = 0;
$resposta5 = 0;

$filtro = filter_input(INPUT_GET, "filtro");
$parametro = filter_input(INPUT_GET, "parametro");

$select_equipe = "SELECT count(resposta_1) as 'r1',count(resposta_2) as 'r2',count(resposta_3) as 'r3',count(resposta_4) as 'r4',count(resposta_5) as 'r5',b.equipe FROM ligacao b
INNER JOIN resposta a ON a.ligacao = b.id_ligacao
where b.equipe = '$parametro'";

$select_campanha = "SELECT count(resposta_1) as 'r1',count(resposta_2) as 'r2',count(resposta_3) as 'r3',count(resposta_4) as 'r4',count(resposta_5) as 'r5',b.campanha FROM ligacao b
INNER JOIN resposta a ON a.ligacao = b.id_ligacao
where b.campanha='$parametro'";

$select_geral = "SELECT count(resposta_1) as 'r1',count(resposta_2) as 'r2',count(resposta_3) as 'r3',count(resposta_4) as 'r4',count(resposta_5) as 'r5' from resposta";

$select_agente = "SELECT count(resposta_1) as 'r1',count(resposta_2) as 'r2',count(resposta_3) as 'r3',count(resposta_4) as 'r4',count(resposta_5) as 'r5',c.nome FROM ligacao b
INNER JOIN resposta a ON a.ligacao = b.id_ligacao
INNER JOIN funcionario c ON c.id_funcionario = b.funcionario
where c.nome = '$parametro'";

if($filtro != ""){
    if ($filtro == "equipe"){
        $resultado = mysqli_query($conn, $select_equipe);
    }
    else if ($filtro == "agente"){
        $resultado = mysqli_query($conn, $select_agente);
    }
    else if ($filtro == "campanha"){
        $resultado = mysqli_query($conn, $select_campanha);
    }
}else{
    $resultado = mysqli_query($conn, $select_geral);
}

while($dado = $resultado -> fetch_array()){  
    $resposta1 = $dado['r1'];  
    $resposta2 = $dado['r2'];
    $resposta3 = $dado['r3'];
    $resposta4 = $dado['r4'];
    $resposta5 = $dado['r5'];
}

if($resposta1 == 0 && $resposta2 == 0 && $resposta3 == 0 && $resposta4 == 0 && $resposta5 == 0){
    $url = './grafico2.php';
    echo '<META HTTP-EQUIV=Refresh CONTENT="0.; URL=' . $url . '">';
    echo "<script>alert('Dados não encontrados!');</script>";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
<body>
    

    <style>
        .tamanho{
            width: 800px;
        }
    </style>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label>Filtrar por:</label>
        <select name="filtro">
            <option value="">Selecione...</option>
            <option value="equipe">Equipe</option>
            <option value="agente">Agente</option>
            <option value="campanha">Campanha</option>
        </select>

        <input type="text" name="parametro" onkeypress="myFunction()"> 
        <input type="submit" value="Buscar"> 
    </form>
    
    <div class="tamanho">
    <canvas id="pie-chart"></canvas>
    </div>


    <script>
        Chart.defaults.global.defaultFontFamily = 'Helvetica';
        Chart.defaults.global.defaultFontSize = 15;

        new Chart(document.getElementById("pie-chart"), {
            type: 'pie',
            data: {
            labels: ["Pergunta 1", "Pergunta 2", "Pergunta 3", "Pergunta 4", "Pergunta 5"],
            datasets: [{
                label: "Quantidade de respostas",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                hoverBorderColor: "#000000",          
                hoverBorderWidth: 1.5,
                data: [<?php echo $resposta1 ?>,<?php echo $resposta2 ?>,<?php echo $resposta3 ?>,<?php echo $resposta4 ?>,<?php echo $resposta5 ?>],
            }],
            
            },
            options: {
            responsive: true,
            title: {
                display: true,
                text: 'Quantidade de respostas por perguntas',
            },
            animation:{
                easing: "easeInQuad", //easeOutBack,easeInOutCirc,easeOutCirc,easeOutExpo,easeInOutQuint,easeInQuint
                animateScale: true,
                animateRotate: true
            }
            }
        });
    </script>
</body>
</html>