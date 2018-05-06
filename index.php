<?php

interface IUser
{
    public function toPage($Page);
    public function getLevel();
}

interface IUnreg extends IUser
{
    const level = 1;
    public function register();
    public function login();
}

interface IReg extends IUser
{
    public function logout();
    public function comment();
    public function edit();
}

class CPage
{
    private $id;

    function CPage($setId)
    {
        $this->id = $setId;
    }
	
    function load()
    {
        echo "Страница $this->id загружена.<br />";
    }

    function getID()
    {
        return $this->id;
    }
}

class CAuth
{
    private $pageRights;
        
    function CAuth()
    {
        $this->pageRights['index'] = 1;
        $this->pageRights['profile'] = 2;
        $this->pageRights['admin'] = 3;
    }

    function failed()
    {
        echo "Не удалось войти, у Вас недостаточно прав.<br />";
    }
	
    function authToPage($User, $Page)
    {
        
        $userLevel = $User->getLevel();
        $pageLevel = $Page->getID();
        
        if ($userLevel < $this->pageRights[$pageLevel]) {$this->failed();}
        else {$Page->load();}
    }
}

class CUnreg implements IUnreg
{
    private $authent;
	
    function CUnreg($Auth) 
    {
        $this->authent = $Auth;
    }
    
    function register() {}
    function login() {}
        
    function getLevel()
    {
        return CUnreg::level;
    }
	
    function toPage($Page)
    {
        $this->authent->authToPage($this, $Page);
    }
    	
}

class CReg implements IReg
{
    protected $authent;
    protected $level;
	
    function CReg()
    {
        die ("Cannot create Abstract CReg class!");
    }
	
    public function getLevel()
    {
        return $this->level;
    }
        
    function logout() {}
    function comment() {}
    function edit() {}
        
    function toPage($Page)
    {
        $this->authent->authToPage($this, $Page);
    }
    
}

class CRegUser extends CReg
{
    function CRegUser($Auth)
    {
        $this->level = 2;
        $this->authent = $Auth;
    }
}

class CRegAdmin extends CReg
{
    function CRegAdmin($Auth)
    {
        $this->level = 3;
        $this->authent = $Auth;
    }
}


$authenticator = new CAuth();

//Создание страниц
$indexPage = new CPage('index');
$profilePage = new CPage('profile');
$adminPage = new CPage('admin');



echo '<h3>Гость</h3>';
$unreg = new CUnreg($authenticator);

echo "Обращение к странице <em>index</em>: ";
$unreg->toPage($indexPage);
echo "Обращение к странице <em>profile</em>: ";
$unreg->toPage($profilePage);
echo "Обращение к странице <em>admin</em>: ";
$unreg->toPage($adminPage);



echo '<hr \><h3>Пользователь</h3>';
$user = new CRegUser($authenticator);

echo "Обращение к странице <em>index</em>: ";
$user->toPage($indexPage);
echo "Обращение к странице <em>profile</em>: ";
$user->toPage($profilePage);
echo "Обращение к странице <em>admin</em>: ";
$user->toPage($adminPage);



echo '<hr \><h3>Администратор</h3>';
$admin = new CRegAdmin($authenticator);

echo "Обращение к странице <em>index</em>: ";
$admin->toPage($indexPage);
echo "Обращение к странице <em>profile</em>: ";
$admin->toPage($profilePage);
echo "Обращение к странице <em>admin</em>: ";
$admin->toPage($adminPage);

