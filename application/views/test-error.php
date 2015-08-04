ERROR
<?php
if (!\is_null($message)) {
    echo $message;
}
if (!\is_null($this->data)) {
    echo json_encode($this->data);
}
