<?php 

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;

class Mail {

	public function enviaEmail($email, $name, $token, $userId)
	{

		$conf = load_config('mail');

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	    $mail->SMTPDebug 	= $conf['debug'];   
	    $mail->CharSet 		= $conf['charset'];                              
	    $mail->isSMTP();
	    $mail->SMTPSecure 	= $conf['secure'];
	    $mail->Host 		= $conf['host'];  	
	    $mail->SMTPAuth 	= $conf['auth'];                            
	    $mail->Username 	= $conf['username'];                 
	    $mail->Password 	= $conf['password'];                          
	    $mail->Port 		= $conf['port'];  
	    $mail->setFrom($conf['fromEmail'], $conf['fromName']);
	    $mail->addAddress($email, $name);     // Add a recipient
	   	$mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = 'Email de recuperação de senha';
	    $mail->Body    = 'Acesse o link <b><a href="'. baseUrl() .'/recovers/account/' . $userId . '/token/'. $token .'" target="_blank">Trocar senha</a></b> para a redefinição de sua senha!';

	    if ($mail->send()) {
	    	return true;
	    } else {
	    	return false;
	    }

		
	}

}