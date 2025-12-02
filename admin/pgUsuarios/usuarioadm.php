<!--Érica Bonfanti e Laura Sartor - 3-52 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="usuario.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário</title>
</head>
<?php
//Conexão com o banco de dados
include("../../conexao.php");

//Recebe os dados que foram enviados pelo formulário
$login =$_POST['login'];
$senha = $_POST['senha'];
$tipo =$_POST['tipo'];

//Consulta SQL para inserir os dados
$sql = "Insert into usuario(login,senha,tipo)
                            values('".$login."',
                                    '".md5($senha)."',
                                    '".$tipo."')";

//Executa a consulta no banco de dados
$res = mysqli_query($id,$sql);

//Verifica se deu certo ou errado
if($res){
    echo "<p align= 'center' > Cadastro efetuado"; //Mensagem de certo
}else{
    echo "<p align= 'center' > Cadastro não efetuado"; //Mensagem de erro

}


?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Madimi+One&display=swap'); 
        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Madimi One', sans-serif;
        }

        /* Estilos do footer */
       footer {
            background-color: #415aca;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Container principal */
        .container-footer {
            max-width: 1400px;
            padding: 0 4%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo Snow com floco de neve */
        .logo-snow {
            display: flex;
            align-items: center;
            color: white;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -1px;
        }

        .snowflake {
            margin: 0 2px;
            font-size: 20px;
            font-weight: 300;
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Direitos autorais */
        .rodape-direitos {
            color: white;
            font-size: 14px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container-footer {
                flex-direction: column;
                gap: 15px;
            }
            
            .logo-snow {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <footer>
        <div class="container-footer">
            <!-- Logo Snow com floco de neve -->
            <div class="logo-snow">
                Sn<span class="snowflake">❄</span>w
            </div>
            
            <!-- Copyright no lado direito -->
            <p class="rodape-direitos">Copyright © 2024 – Todos os Direitos Reservados.</p>
        </div>
    </footer>
</body>
    </html>