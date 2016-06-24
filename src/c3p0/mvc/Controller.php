<?php
namespace c3p0\mvc;

class Controller {

    protected $view;
    protected $mail;

    public function __construct() {
        $this->view = new View($this->getViewPath()); 
    }

    public function __destruct() {
        $view = $this->view;
        if ($view instanceof View && !empty($view->view)) {
            if (!empty($view->data)) {
                extract($view->data);
            }
            require $view->view;
        }
        
        $mail = $this->mail;
        if ($mail instanceof Mail) {
            $mailer = new \Nette\Mail\SmtpMailer($mail->config);
            $mailer->send($mail);
        }
    }
    
    public function beforeAction() {
        
    }
    
    public function afterAction() {
        
    }
    
    private function getViewPath() {
        $subClassName = get_class($this); 
        $arrDir = explode('\\', $subClassName); 
        $cDir = array();
        foreach ($arrDir as $item) {
            if ($item != 'controllers') {
                array_push($cDir, $item);
            } else {
                break; 
            }
        }
        $viewPath = "/src/".join("/",$cDir)."/views/";
        return $viewPath; 
    }
    
    public function getEntityManager($key = '') {
        global $_entityManager;
        if (empty($key)) return $_entityManager['default'];
        return $_entityManager[$key]; 
    }
    
}
