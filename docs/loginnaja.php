<?php
$msg = NULL;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim(strip_tags($_POST['email']));
    $senha = trim(strip_tags($_POST['senha']));
    if (empty($email) && empty($senha)) {
        $msg = 'Informe um e-mail/senha!';
    } elseif (empty($email)) {
        $msg = 'Informe um e-mail!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Informe um e-mail válido!';
    } elseif (empty($senha)) {
        $msg = 'Informe uma senha!';
    } else {
        $senha = md5($senha);
        $link = "http://najasolucoes.mysuite1.com.br/central.php?lf={$email}_*_{$senha}";
        header("Location: $link");
    }
}

/* Acesso ao MySuite (teste)
  Link: http://najasolucoes.mysuite1.com.br/central.php
  Usuário: tenerteste@gmail.com
  Senha: tener123456 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>login Naja</title>        
        <link rel="stylesheet" href="css/formee-structure.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/formee-style.css" type="text/css" media="screen" />
    </head>
    <body>
        <div class="formulario">
            <?php if (isset($msg) && !empty($msg)): ?>
                <div class="formee-msg-error">
                    <h3><?php echo $msg; ?></h3></div>
            <?php endif; ?>
            <form class="formee" name="form-naja" method="post" action="">
                <fieldset>
                    <legend>Login Naja</legend>
                    <div class="grid-6-12">
                        <label class="form_label">Email:</label>
                        <input type="text" name="email" class="form_input"/>
                    </div>
                    <div class="grid-6-12">
                        <label class="form_label">Senha:</label>
                        <input type="password" name="senha" class="form_input" />
                    </div>
                    <div class="grid-2-12">
                        <input type="submit" name="btn" value="Entrar" />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>