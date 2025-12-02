<?php
//Conexão com o banco de dados
include("../conexao.php");

//Recebe os dados que foram enviados pelo formulário
$nome = $_POST['nome'];
$icone = $_POST['icone'];

//Consulta SQL para inserir os dados
$sql = "Insert into emocao(nome,icone)
                            values( 
                                    '".$nome."',
                                   '".$icone."')";

//Executa a consulta no banco de dados
$res = mysqli_query($id,$sql);

//Verifica se deu certo ou errado
if($res){
    echo "<p align='center'> Cadastro efetuado"; //Mensagem de certo
}else{
    echo "<p align='center'> Cadastro não efetuado"; //Mensagem de erro
}


?>
