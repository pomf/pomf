<?php

class Response
{
    private $type;

    public function __construct($response_type = null)
    {
        switch ($response_type) {
            case 'csv':
            case 'gyazo':
                header('Content-Type: text/plain; charset=UTF-8');
                $this->type = $response_type;
                break;
            case 'text':
                header('Content-Type: text/plain; charset=UTF-8');
                $this->type = $response_type;
                break;

            case 'html': 
                header('Content-Type: text/html;charset=utf-8');
                $this->type = 'html';
                break;
            default:
                header('Content-Type: application/json; charset=UTF-8');
                $this->type = 'json';
                break;
        }
    }

    public function error($code, $desc)
    {
        $response = null;

        switch ($this->type) {
            case 'csv':
                $response = $this->csv_error($desc);
                break;
            case 'gyazo':
                $response = $this->gyazo_error($code, $desc);
                break;
            case 'text':
                $response = $this->text_error($code, $desc);
                break;
            case 'html':
                $response = $this->html_error($code, $desc);
                break;
            default:
                $response = $this->json_error($code, $desc);
                break;
        }

        http_response_code($code);
        echo $response;
    }

    public function send($files)
    {
        $response = null;

        switch ($this->type) {
            case 'html':
                $response = $this->html_success($files);
                break;
            case 'csv':
                $response = $this->csv_success($files);
                break;
            case 'gyazo':
                $response = $this->gyazo_success($files);
                break;
            case 'text':
                $response = $this->text_success($files);
                break;
            default:
                $response = $this->json_success($files);
                break;
        }

        http_response_code(200);
        echo $response;
    }

    private static function csv_error($description)
    {
        return "error\n".$description."\n";
    }

    private static function csv_success($files)
    {
        $result = "name,url,hash,size\n";
        foreach ($files as $file) {
            $result .= $file['name'].','.
                       $file['url'].','.
                       $file['hash'].','.
                       $file['size']."\n";
        }

        return $result;
    }

    private static function gyazo_error($code, $description)
    {
        return 'ERROR: ('.$code.') '.$description;
    }

    private static function gyazo_success($files)
    {
        return POMF_URL.$files[0]['url'];
    }
    private static function txt_error($code, $description)
    {
        return 'ERROR: ('.$code.') '.$description;
    }

    private static function text_success($files)
    {
        foreach ($files as $file) {
            $result .=  POMF_URL.$files[0]['url'];
        }

    return $result;
    }

    private static function html_error($code, $description)
    {
        return 'ERROR: ('.$code.') '.$description;
    }

    private static function html_success($files)
    {
        foreach ($files as $file) {
            $result .=  "<a href=\"https://i.pantsu.cat/".$file['url']."\">".$file['url']."</a><br>";
        }

    return $result;
    }

    private static function json_error($code, $description)
    {
        return json_encode(array(
            'success' => false,
            'errorcode' => $code,
            'description' => $description,
        ));
    }

    private static function json_success($files)
    {
        return json_encode(array(
            'success' => true,
            'files' => $files,
        ));
    }
}
