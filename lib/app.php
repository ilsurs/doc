<?php

class BeforeRenderCallback {

    private $callbacks;
    private $cwd;

    public function __construct($callbacks, $cwd=null) {
        $this->callbacks = $callbacks;
        $this->cwd = $cwd;
    }

    public function addCallback($callback) {
        $this->callbacks[] = $callback;
    }

    public function __invoke($content, $phase) {

        if ($this->cwd) {
            chdir($this->cwd);
        }

        $content = trim($content);
        foreach ($this->callbacks as $callback) {
            $content = $callback($content, $this->cwd);
        }
        return $content;
    }

    public function prepare() {
        foreach ($this->callbacks as $callback) {
            $callback->prepare();
        }
    }
}

function incl($filename, $context=array()) {
    extract($context);
    require($filename);
}

class PrelandInjector {

    public $redirectUrl;
    public $fixImages;
    public $comebacker;
    public $mining;

    private $code;

    public function __invoke($content, $cwd) {
        return str_replace('</head>',  $this->code . '</head>', $content);
    }

    public function prepare() {
        $this->code = $this->render();
    }

    private function render() {
        ob_start();
        incl('js.preland.php', array(
            'redirectUrl' => $this->redirectUrl,
        ));
        $code = ob_get_clean();
        return $code;
    }
}

function add_fb_pixel($url, $fb_pixel) {
    if ($url == '{offer}') {
        $new_url = $url . '&fb_pixel=' . $fb_pixel;
    } else {
        $parts_url = parse_url($url);
        parse_str($parts_url['query'], $parts_query);
        $parts_query['fb_pixel'] = $fb_pixel;
        $parts_url['query'] = http_build_query($parts_query);
        $new_url =  unparse_url($parts_url);
    }
    return $new_url;
}

function unparse_url($parsed_url) {
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
}