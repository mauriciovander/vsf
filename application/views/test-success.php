SUCCESS
<?php
if (!\is_null($this->message)) {
    echo $this->message;
}
if (!\is_null($this->data)) {
    echo json_encode($this->data);
}
