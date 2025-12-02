<?php
//Conexão com o banco de dados
include("../../conexao.php");

//Verifica se os dados existem antes de usar
if(isset($_POST['id_diario']) && isset($_POST['id_tecnicas']) && isset($_POST['descricao'])){
    
    //Recebe os dados que foram enviados pelo formulário
    $id_diario = $_POST['id_diario'];
    $id_tecnicas = $_POST['id_tecnicas'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];

    //Consulta SQL para inserir os dados 
    $sql = "Insert into historico (id_diario, id_tecnicas, data, descricao)
                                values('".$id_diario."',
                                       '".$id_tecnicas."',
                                         '".$data"',
                                       '".$descricao."')";

    //Executa a consulta no banco de dados
    $res = mysqli_query($id,$sql);

    //Verifica se deu certo ou errado
    if($res){
        echo "<p align='center'> Cadastro efetuado"; //Mensagem de certo
    }else{
        echo "<p align='center'> Cadastro não efetuado"; //Mensagem de erro
    }
}else{
    echo "<p align='center'> Dados incompletos";
}
?>