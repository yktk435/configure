<?php



class Configure
{
    function __construct()
    {
        $this->value = $this->configToArray();
        $this->hostname = $this->getHostName();
    }
    function show()
    {
        print_r($this->value);
    }
    function configToArray()
    {
        $file = fopen('conf.cfg', "r");
        $array = [];
        $key = '';

        if ($file) {
            while ($line = fgets($file)) {

                if (preg_match('/^[^\s!]/', rtrim($line), $match) && !strpos($line, '#') !== false) { //文字の先頭に空白がないなら
                    $key = trim($line);
                    $array[$key][] = '';
                }
                if (preg_match('/^\s/', rtrim($line), $match)) { //文字の先頭に空白があるなら
                    $array[$key][] = trim($line);
                }
            }
        }
        return $array;
    }
    function getHostName()
    {
        foreach ($this->value as $key => $value) {
            if (preg_match('/hostname\s(.*)/', $key, $match)) {
                unset($this->value[$key]);
                return $match[1];
                break;
            }
        }
    }
    function getInterface()
    {
        foreach ($this->value as $key => $value) {
            if (preg_match('/^interface\s(.*)/', $key, $match)) {

                $name = $match[1];
                unset($value[0]);
                $value = array_values($value);
                foreach ($value as $key2 => $val) {
                    if (gettype($val) == 'string') {
                        if (preg_match('/description\s(.*)/', $val, $match)) {
                            $value['description'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/ip address\s(.*)/', $val, $match)) {
                            $value['ip address'] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/no\s(.*)/', $val, $match)) {
                            $value['no'][] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/standby\s(.*)/', $val, $match)) {
                            $value['standby'][] = $match[1];
                            unset($value[$key2]);
                        } else if (preg_match('/delay\s(.*)/', $val, $match)) {
                            $value['delay'] = $match[1];
                            unset($value[$key2]);
                        }else if (preg_match('/switchport\s(.*)/', $val, $match)) {
                            $value['switchport'] = $match[1];
                            unset($value[$key2]);
                        }else if (preg_match('/duplex\s(.*)/', $val, $match)) {
                            $value['duplex'] = $match[1];
                            unset($value[$key2]);
                        }else if (preg_match('/speed\s(.*)/', $val, $match)) {
                            $value['speed'] = $match[1];
                            unset($value[$key2]);
                        }else if (preg_match('/encapsulation\s(.*)/', $val, $match)) {
                            $value['encapsulation'] = $match[1];
                            unset($value[$key2]);
                        }
                    }
                }
                print_r($value);
                $this->interface[$name] = $value;
                unset($this->value[$key]);
            }
        }
    }
}

$c = new Configure();
// $c->show();
$c->getInterface();
// print_r($c->interface);
$c->show();