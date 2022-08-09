<link rel="stylesheet" href="styles.css"> 
<form action="/index.php" method="post">
    <p>Controle de consumo por residência</p>
    <div>
        <label for="entrada">Entrada:</label>
        <textarea id="entrada" name="usuario_entrada"></textarea>
    </div>

    <div class="button">
        <button type="submit">Enviar entrada</button>
    </div>
</form>
<?php if(isset($_POST['usuario_entrada'])){
	echo "<form>";
	echo controle_de_consumo();
	echo "</form>";} ?>

<?php
/*A função $callback compara dois elementos ($a e $b) e retorna um valor inteiro:

     - zero (0) significa que $a é igual a $b.
     - um número negativo significa que $a está antes de $b.
     - um número positivo significa que $a está depois de $b. */

function my_sort($a,$b) {
	$a = explode("-", $a);
	$b = explode("-", $b);
	if ($a[1] == $b[1]) return 0;
	return ($a[1] < $b[1]) ? -1 : 1;

}

function controle_de_consumo(){
	$entrada = explode("\n", $_POST['usuario_entrada']);
		
	$cidade = 0;
	$array = array();
	$propriedades = 0;
	$pessoas_consumo = array();
	$somatorio_pessoas = 0;
	$somatorio_consumo = 0;
	for($i = 0; $i < count($entrada); $i++){
			
		//não havendo " " ou "0", a $entrada[n] só pode ser o número de casas de uma cidade
		if( !strpos($entrada[$i], " ") and $entrada[$i] != "0" ){
			$propriedades = $entrada[$i];
			echo "Cidade #".(++$cidade)."<br>";
		}
		//é o par de valores: pessoas-consumo
		else if($entrada[$i] != "0"){
				
			//chave 0 => pessoas, chave 1 => consumo
			$pessoas_consumo = explode(" ", $entrada[$i]);
			$somatorio_pessoas += $pessoas_consumo[0];
			$somatorio_consumo += $pessoas_consumo[1];
				
			//preserva o valor de pessoas e concatena a média (consumo/pessoas)
			array_push($array, $pessoas_consumo[0]
			."-".floor($pessoas_consumo[1]/$pessoas_consumo[0]));
		}
		if(count($array) == $propriedades ){
			usort($array, "my_sort"); // ordena array por consumo
				
			for($j = 0; $j < count($array); $j++){
				echo $array[$j];
				if( ($j+1) < $propriedades){ // espaço entre os pares de valores
					echo " ";
				}else{
					echo "<br>";
					echo "Consumo medio: " .number_format($somatorio_consumo/$somatorio_pessoas, 2)."m3";
					if($entrada[$i+1] != end($entrada)){
						echo "<br><br>";
					}
					else{
						break;
					}
				}
			}
			// reseta os dados para fazer os cálculos da próxima cidade
			$somatorio_pessoas = 0;
			$somatorio_consumo = 0;
			$array = [];
		}
	}
}
?>