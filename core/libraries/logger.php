<?php

class logger
{
    public $type;

    public $message;

    public function __Construct($type="")
    {
        if (!!$type) {
            $this->type = PATH.'logs/'.$type.'.txt';
        }
    }

    public function write()
    {
        if (!file_exists($this->type)) {
            fopen($this->type, 'w');
        }
        file_put_contents($this->type, $this->message);
    }

    public function set($message)
    {
        $content = $this->get();

        $message = date('d-m-Y H:i:s').' '.$message."\n";

        $this->message = $content.$message;

        return $this;
    }

    public function get()
    {
        $content = file_get_contents($this->type);

        return $content;
    }
}
