<?php
namespace Core;

class EMail
{
    private $from;
    private $to = array();
    private $subject;
    private $message;

    public function setFrom($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            $this->from = $email;
        else
            throw new \MailException(sprintf("%s is not an valid email adress", $email));
    }

    public function setTo($email)
    {
        if (is_array($email))
        {
            foreach ($email as $v)
                if (filter_var($v, FILTER_VALIDATE_EMAIL))
                    $this->to[] = $v;
                else
                    throw new \MailException(sprintf("%s is not an valid email adress", $v));
        }
        else
        {
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
                $this->to[] = $email;
            else
                throw new \MailException(sprintf("%s is not an valid email adress", $email));
        }
    }

    public function setSubject($subjet)
    {
        $this->subject = $subjet;
    }

    public function setMessage($message)
    {
        $this->message = str_replace('\n.', '\n..', $message);
    }

    public function send()
    {
        foreach ($this->to as $to)
        {
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            mail($to, $this->subject, $this->message, $headers);
        }
    }
}