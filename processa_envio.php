<?php 
//mailer 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\Exception;
//google
use League\OAuth2\Client\Provider\Google;

//require
/*require 'phpMailer/Exception.php';
require 'phpMailer/OAuthTokenProvider.php'; 
require 'phpMailer/OAuth.php';
require 'phpMailer/PHPMailer.php';
//require 'phpMailer/PO3.php';
require 'phpMailer/SMTP.php';*/

//------------------------------------------------------

class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array('codigo_status' => null,'descricao_status' => null);

    public function __get($atributo){
        return $this->$atributo; 
    }

    public function __set($atributo, $valor){
        $this-> $atributo = $valor; 
    }

    public function mensagemValida(){
        //checar se os dados foram preenchidos
        if(empty($this->para)|| empty($this->assunto) || empty($this->mensagem) ){
            return false;
        } return true;
    }
}

//atribuições objeto e seus valores com dados preenchidos no form
$mensagem = new Mensagem();
$mensagem-> __set('para', $_POST['para']);
$mensagem-> __set('assunto', $_POST['assunto']);
$mensagem-> __set('mensagem', $_POST['mensagem']);

if(!$mensagem-> mensagemValida()){
    echo 'Mensagem não é valida';
    header('Location: index.php');
}


//mail
$mail = new PHPMailer(true);

try {
    //Server 
    $mail->SMTPDebug = false; 
    $mail->isSMTP(); 
    $mail->Host     = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true; 
    $mail->Username = 'YOUREMAIL@gmail.com'; //google mail account
    $mail->Password = 'YOUR KEY'; //google mail app key 
    $mail->SMTPSecure = 'tls'; 
    $mail->Port     = 587; 

    //Recipients
    $mail->setFrom('YOUR EMAIL', 'remetente');//
    $mail->addAddress($mensagem-> __get('para')); //
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz'); 
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); 

    //Content
    $mail->isHTML(true); 
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessário usar o client de suporte HTML para ter acesso total ao conteúdo dessa mensagem';

    $mail->send();
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso!';
} catch (Exception $e) {
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = "Não foi possível enviar este e-mail. Error: {$mail->ErrorInfo}";
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App send e-mail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="py-3 text-center">
		  <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
		  <h2>Send Mail</h2>
		  <p class="lead">Seu app de envio de e-mails particular!</p>
		</div>

        <div class="row">
            <div class="col-md-12">
                <?php if($mensagem->status['codigo_status']== 1){ ?>
                    <div class="container">
                        <h1 class="display-4 text success">Sucesso!</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
                <?php } ;?>
                    
                <?php if($mensagem->status['codigo_status']== 2){ ?>
                    <div class="container">
                        <h1 class="display-4 text-danger">Erro.</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p>
                        <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white"></a>
                    </div>
                    <?php } ;?>
            </div>
        </div>
    </div>

</body>
</html>
