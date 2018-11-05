<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 12:28
 */


namespace Core\Mail;


class PhpMail
{
  private
	$to,
	$from,
	$message,
	$header,
	$subject;

  /**
   * @return mixed
   */
  public function getSubject()
  {
	return $this->subject;
  }

  /**
   * @param mixed $subject
   */
  public function setSubject($subject): void
  {
	$this->subject = $subject;
  }

  /**
   * Mailer constructor.
   *
   * @param $to
   * @param $from
   * @param $message
   * @param $header
   * @param $subject
   */
  public function __construct($to, $from, $message, $subject, $header = null)
  {
	$this->to = $to;
	$this->from = $from;
	$this->setMessage($message);
	$this->subject = $subject;
	$this->setHeader($header);
	return $this;
  }

  public function sendEmail(): bool
  {
	return mail($this->to, $this->subject, $this->message,
	  $this->from . $this->header);
  }


  /**
   * @return mixed
   */
  public function getTo()
  {
	return $this->to;
  }

  /**
   * @param mixed $to
   */
  public function setTo($to): void
  {
	$this->to = $to;
  }

  /**
   * @return mixed
   */
  public function getFrom()
  {
	return $this->from;
  }

  /**
   * @param mixed $from
   */
  public function setFrom($from): void
  {
	$this->from = 'From: ' . $from . "\r\n";;
  }

  /**
   * @return mixed
   */
  public function getMessage()
  {
	return $this->message;
  }

  /**
   * @param mixed $message
   */
  public function setMessage(string $message): void
  {
	$this->message = '<html><body>' . $message . '</body></html>';
  }

  /**
   * @return mixed
   */
  public function getHeader()
  {
	return $this->header;
  }

  /**
   * @param mixed $header
   */
  public function setHeader($header): void
  {
	if ($header !== null)
	  $this->header = $header;
	else
	{
	  $this->header = "Reply-To: " . 'no_reply@camagru.fr"' . "\r\n";
	  $this->header .= "MIME-Version: 1.0\r\n";
	  $this->header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	}
  }
}

//// test
//$a = urlencode(bin2hex(random_bytes(50)));
//
//$a =
//  'https://localhost:8080' .
//  '?id=2&token=' .
//  $a;
//$link = "<a href='$a'> CLICK </a>";
//
//$to = 'adrien.pusel@gmail.com';
//
//$message = $link;
//
//$subject = 'Confirm insciption';
//
//$headers = "Reply-To: " . 'no_reply@camagru.fr"' . "\r\n";
//$headers .= "MIME-Version: 1.0\r\n";
//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//
//$mailer = new PhpMail(
//  $to,
//  'Camagru',
//  $message,
//  $headers,
//  $subject
//);
//
//var_dump($mailer->sendEmail());