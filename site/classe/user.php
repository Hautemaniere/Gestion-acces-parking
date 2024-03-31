<!-- user.php-->


<?php
class User
{
    private $id;
    private $login;
    private $password;
    private $type;
    private $mail;

    public function __construct($id, $login, $password, $type, $mail)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->type = $type;
        $this->mail = $mail;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }
}
