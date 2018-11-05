<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 09:23
 */

// son id = et le link

$a = urlencode(bin2hex(random_bytes(50)));

$a =
  'https://localhost:8080' .
  '?id=2&token=' .
  $a;
$link = "<a href='$a'> CLICK </a>";

$to = 'adrien.pusel@gmail.com';

$message = $link;

$subject = 'Confirm insciption';

$headers = "From: " . 'Camagru' . "\r\n";
$headers .= "Reply-To: " . 'no_reply@camagru.fr"' . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


class Mailer
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
  public function __construct($to, $from, $message, $header, $subject)
  {
	$this->to = $to;
	$this->from = $from;
	$this->setMessage($message);
	$this->header = $header;
	$this->subject = $subject;
  }

  public function sendEmail() : bool
  {
	return
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
	$this->from = $from;
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
	$this->header = $header;
  }


}
