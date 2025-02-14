<?php
// Acesso ao banco
$host = 'localhost';
$username = 'root';
$password = '';     // Senha vazia se não tiver configuração
$dbname = 'cadastro2'; // Nome do banco de dados
// Criando conexão
$conn = new mysqli($host, $username, $password, $dbname);
// Verificando se ouve erro na conexão
if ($conn->connect_errno) {
    die("Conexão falhou:" . $conn->connect_error);
};
// Verifica se chegou o formuçário
if (isset($_POST['nome_usuario'])) {
    // Recebe os dados
    $nome = $_POST['nome_usuario'];
    $email = $_POST['email_usuario'];
    // verifica se existe um cadastro igual
    $busca_cadastro = $conn->query("SELECT * FROM usuario WHERE email_usuario = '" . $email . "'");
    // caso encontre um usuario com esse email
    if ($busca_cadastro->num_rows >= 1) {
        $msgAlerta =  "Usuário já cadastrado";
    } else {
        // Cadastra no banco
        $inserir_cadastro = $conn->query("INSERT usuario SET nome_usuario = '" . $nome . "', email_usuario = '" . $email . "'");
        // Verifica se cadastrou
        if ($inserir_cadastro) {
            $msgAlerta = "Cadastrado com sucesso!";
        } else {
            $msgAlerta = "Erro ao cadastrar usuario!";  
        }
    }

};

// Apagar cadastro
if(isset($_GET['email_usuario'])){
    $email_usuario = $_GET['email_usuario'];
    $deleteCadastro = $conn->prepare("DELETE FROM usuario WHERE email_usuario = ?");
    $deleteCadastro->bind_param("s", $email_usuario);
    if($deleteCadastro->execute()){
        $msgAlerta = "Cadastro excluído com sucesso!";
    }else{
        $msgAlerta = "Erro ao excluir cadastro!";
    }

    $deleteCadastro->close();
}

// Busca todos os cadastros úsuarios
$busca = $conn->query("SELECT * FROM usuario");

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siatema básico de CRUD</title>
    <?php include("css-include.php"); ?>
</head>

<body>
    <?php if (isset($msgAlerta)) { ?>
        <div class="alert">
            <span>
                <?php echo $msgAlerta; ?>
            </span>
        </div>
    <?php } ?>

    <div class="container">
        <div class="title">
            <h1>Cadastre seu e-mail</h1>
        </div>
        <!-- Cadastrando os e-mails -->
        <div class="caixa">
            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="name" name="nome_usuario" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email_usuario" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Cadastrar">
                </div>
                <img src="" alt="">
            </form>
        </div>
        <!-- Mostrando os e-mails cadastrados -->
        <div class="busca">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Lista e-mails cadastrados-->
                    <?php
                    while ($resCadastro = $busca->fetch_array(MYSQLI_ASSOC)) {
                        echo "
                            <tr>
                                <td>" . $resCadastro["nome_usuario"] . "</td>
                                <td>" . $resCadastro["email_usuario"] . "</td>
                                <td style='width:30px'><a href='index.php?email_usuario=".$resCadastro["email_usuario"]. "' onclick='return confirm(\"Tem certeza que deseja excluir este cadastro?\")'> <img src='./img/fechar.png' alt='' style='width:30px; text-align:center'></a></td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- JavaScript include -->
    <?php include("js-include.php"); ?>
</body>

</html>