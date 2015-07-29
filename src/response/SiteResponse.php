<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Concrete Factory
// Response for AJAX context
class SiteResponse implements ResponseInterface {

    private $template;

    public function error($view = null, $data = null) {
        return new SiteErrorResponse($view, $data, $this->template);
    }

    public function success($view = null, $data = null) {
        return new SiteSuccessResponse($view, $data, $this->template);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Description: Site response");
        header('Content-type: text/html');
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

}

// Concrete Product:
// Error response for Site context
class SiteErrorResponse implements Response {

    private $message;
    private $data;
    private $template;

    public function __construct($message = null, $data = null, $template = null) {
        $this->data = $data;
        $this->message = $message;
        $this->template = $template;
    }

    public function __toString() {
        if (!is_null($this->template)) {
            $message = $this->message;
            foreach ($this->data as $key => $value) {
                $$key = $value;
            }
            include BASEPATH . '/application/views/' . $this->template;
        } else {
            var_dump($this->data);
        }
        return '';
    }

}

// Concrete Product:
// Successful response for Site context
class SiteSuccessResponse implements Response {

    private $message;
    private $data;
    private $template;

    public function __construct($message = null, $data = null, $template = null) {
        $this->data = $data;
        $this->message = $message;
        $this->template = $template;
    }

    public function __toString() {
        if (!is_null($this->template)) {
            $message = $this->message;
            foreach ($this->data as $key => $value) {
                $$key = $value;
            }
            include BASEPATH . '/application/views/' . $this->template;
        } else {
            echo $this->message;
            var_dump($this->data);
        }
        return '';
    }

}
