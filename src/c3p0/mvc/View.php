<?php
namespace c3p0\mvc;

/**
 * \View
 */
class View {

    // const VIEW_BASE_PATH = '/app/views/';

    public $view;
    public $data;
    private $viewPath;

    public function __construct($viewPath = '') {
        $this->viewPath = $viewPath;
    }

    public function make($viewName = null) {
        if (!$viewName) {
            throw new \InvalidArgumentException("视图名称不能为空！");
        } else {
           $filePath = $this->getFilePath($viewName); 
           if (!\file_exists($filePath)) {
               throw new \Exception("视图文件:{$filePath}没找到！");
           } 
           $this->view = $filePath; 
           return $this;
        }
    }

    public function with($key, $value = null) {
        $this->data[$key] = $value;
        return $this;
    }

    private function getFilePath($viewName) {
        $filePath = \str_replace('.', '/', $viewName);
        return BASE_PATH . $this->viewPath . $filePath . '.php';
    }

    public function __call($method, $parameters) {
        if (substr($method, 0, 4) == 'with') {
            return $this->with(substr($method, 4), $parameters[0]);
        }

        throw new \BadMethodCallException("方法 [$method] 不存在！.");
    }

}
