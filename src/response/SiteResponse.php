<?php

/*
 * @author      Mauricio van der Maesen <mauriciovander@gmail.com>
 * @link        https://github.com/mauriciovander/vsf
 */

namespace vsf\response;

// Concrete Factory
// Response for AJAX context
class SiteResponse implements ResponseInterface {

    private $template = null;

    public function error($message = null, $data = null) {
        return new SiteErrorResponse($message, $data, $this->template);
    }

    public function success($message = null, $data = null) {
        return new SiteSuccessResponse($message, $data, $this->template);
    }

    public function setHeaders() {
        header('HTTP/1.1 200 OK');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
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
            if (!\is_null($this->message)) {
                $message = $this->message;
            }
            if (!\is_null($this->data)) {
                foreach ($this->data as $key => $value) {
                    $$key = $value;
                }
            }
            ob_start();
            include __DIR__ . '/../../application/views/' . $this->template;
            $output = ob_get_contents();
            ob_end_clean();
            return trim($output);
        } else {
            $result = new \stdClass();
            if (!\is_null($this->message)) {
                $result->message = $this->message;
            }
            if (!\is_null($this->data)) {
                $result->data = $this->data;
            }
            return \json_encode($result);
        }
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
            if (!\is_null($this->message)) {
                $message = $this->message;
            }
            if (!\is_null($this->data)) {
                foreach ($this->data as $key => $value) {
                    $$key = $value;
                }
            }
            ob_start();
            include __DIR__ . '/../../application/views/' . $this->template;
            $output = ob_get_contents();
            ob_end_clean();
            return trim($output);
        } else {
            $result = new \stdClass();
            if (!\is_null($this->message)) {
                $result->message = $this->message;
            }
            if (!\is_null($this->data)) {
                $result->data = $this->data;
            }
            return \json_encode($result);
        }
    }

}
