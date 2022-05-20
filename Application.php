<?php

namespace app\core;

class Application
{
    public string $layout = 'main';
    public string $userClass;
    public static string $ROOT_DIR;

    public static Application $app;

    public Router $router;
    public Request $request;
    public Response  $response;

    public Session $session;
    public Database $db;
    public ?DbModel $user;
    public ?Controller $controller = null;
    public View $view;

    /** @var $userClass \app\models\User */
    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->userClass = $config['user'];
        $this->view = new View();

        $this->db = new Database($config['db']);

        //find in session authorized user if not exist user will be null.
        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            /** @var $userClass \app\models\User */
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }
        else{
            $this->user = null;
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        }
        catch (\Exception $e)
        {
            $this->response->getStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}