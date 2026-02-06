<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_Serialize
        {
            static public   $replacement_list   =   array();
            static public   $replacement_list_compare   =   array();
            
            static public   $wph;
            
            /**
            * Returns the processed data, or FALSE if error occours
            * Also return FALSE if no replacement is found in data
            * 
            * @param mixed $value
            * @return mixed
            */
            static public function process ( $value )
                {
                    global $wph;
                    
                    self::$wph  =   $wph;
                    
                    if ( empty ( $value ) )
                        return FALSE;
                        
                    self::$replacement_list = self::$replacement_list_compare   =   [];   
                    
                    self::$replacement_list     =   array_flip( self::$wph->functions->get_replacement_list() );
                    if ( count ( self::$replacement_list  ) < 1 )
                        return FALSE;
                    
                    foreach ( self::$replacement_list as $rp_key =>  $rp_value )
                        self::$replacement_list_compare[ preg_replace('/^https?:\/\//i', '', $rp_key ) ] = preg_replace('/^https?:\/\//i', '', $rp_value );
                    foreach ( self::$replacement_list_compare as $rp_key =>  $rp_value )
                        {
                            self::$replacement_list_compare[ trim ( json_encode ( $rp_key ), '"' ) ] = trim ( json_encode ( $rp_value ), '"' );
                            self::$replacement_list_compare[ urlencode ( $rp_key ) ] = urlencode ( $rp_value );
                        }
                    
                    
                    if ( is_string ( $value ) )
                        $compare_data   =   $value;
                        else
                        $compare_data  =   json_encode( $value );

                    $search =   array_keys ( self::$replacement_list_compare );
                    
                    $founds = array_filter( $search, function( $needle ) use ( $compare_data ) {
                        return stripos( $compare_data, $needle) !== false;
                    });
                    
                    if ( empty ( $founds ) )
                        return FALSE;

                    
                    if ( ! is_serialized ( $value ) )
                        {
                            $value  =   self::block_revert( $value  );
                            
                            return $value;
                        }
                    
                    $parsed = self::parse_serialized( $value );
                    if ( $parsed    === FALSE )
                        return FALSE;
                    
                        
                    self::walk_modify( $parsed, function ( $path, $key, $value ) {
                        $last = end($path);
                        if ( ! is_serialized( $value )   &&  is_string( $value )) 
                            {                                
                                $founds = array_filter( $replacement_list_json, function($needle) use ($value ) {
                                    return stripos( $value, $needle) !== false;
                                });
                                
                                if ( ! empty ( $founds ) )
                                    return self::$wph->functions->content_urls_replacement( $value,  self::$replacement_list );
                            }
                        
                        return $value;
                    });

                    $value = self::reserialize_parsed( $parsed );    

                    return $value;   
                }
                
                
            static public function block_revert( $data )
                {
                    if ( is_serialized( $data ) )
                        return $data;
                    
                    switch ( gettype( $data ) )
                        {
                            case 'array':
                                            foreach ($data as $key => $value)
                                                {
                                                    $data[$key] = self::block_revert( $value );
                                                }
                                            break;
                                            
                            case 'object':
                                            foreach ($data as $key => $value)
                                                {
                                                    $data->$key = self::block_revert( $value );
                                                }
                                            break;
                                            
                            case 'string': 
                                            $data = self::$wph->functions->content_urls_replacement( $data,  self::$replacement_list ); 
                                            
                                            break;            
                        }

                    
                    return $data;
                }
             
            static public function parse_serialized(string $s) 
                {
                    $i = 0;
                    $len = strlen($s);

                    $skip = function() use ( &$s, &$i, $len ) {
                            while ($i < $len && ($s[$i] === ' ' || $s[$i] === "\n" || $s[$i] === "\r" || $s[$i] === "\t")) $i++;
                        };

                    $expect = function($ch) use (&$s, &$i, $len) {
                            if (!isset($s[$i]) || $s[$i] !== $ch)
                                return FALSE;
                            $i++;
                        };

                    $readNum = function() use ( &$s, &$i, $len ) {
                        $start = $i;
                        if ($i < $len && ($s[$i] === '-' || $s[$i] === '+')) $i++;
                        while ($i < $len && ctype_digit($s[$i])) $i++;
                        if ($start === $i) 
                            return FALSE;
                        return substr($s, $start, $i - $start);
                    };

                    $parseString = function() use ( &$s, &$i, $readNum, $expect ) {
                        $i++; $expect(':');
                        $num = $readNum();
                        $expect(':');
                        $expect('"');
                        $lenBytes = (int)$num;
                        $str = substr($s, $i, $lenBytes);
                        if (strlen($str) !== $lenBytes)
                            return FALSE;
                        $i += $lenBytes;
                        $expect('"');
                        $expect(';');
                        return $str;
                    };

                    $parseValue = null;
                    $parseValue = function() use (&$s, &$i, $len, $skip, $expect, $readNum, &$parseValue, $parseString) {
                        $skip();
                        if ($i >= $len)
                            return FALSE;
                        $type = $s[$i];

                        switch ($type) {
                            case 'N':
                                $i++; $expect(';'); return null;
                            case 'b':
                                $i++; $expect(':'); $n = $readNum(); $expect(';'); return (bool)$n;
                            case 'i':
                                $i++; $expect(':'); $n = $readNum(); $expect(';'); return (int)$n;
                            case 'd':
                                $i++; $expect(':');
                                $start = $i;
                                while ($i < $len && $s[$i] !== ';') $i++;
                                $num = substr($s, $start, $i - $start);
                                $expect(';');
                                
                                return (float)$num;
                                
                            case 's':
                                return $parseString();
                                
                            case 'a':
                                $i++; $expect(':');
                                $count = (int)$readNum();
                                $expect(':'); $expect('{');
                                $arr = [];
                                for ($k = 0; $k < $count; $k++) {
                                    $key = $parseValue();
                                    $val = $parseValue();
                                    if (is_int($key) || is_string($key)) {
                                        $arr[$key] = $val;
                                    } else {
                                        $arr[(string)$key] = $val;
                                    }
                                }
                                $expect('}');
                                
                                return $arr;
                                
                            case 'O':
                                $i++; $expect(':');
                                $classLen = (int)$readNum();
                                $expect(':');
                                $expect('"');
                                $className = substr($s, $i, $classLen);
                                if (strlen($className) !== $classLen)
                                    return FALSE;
                                $i += $classLen;
                                $expect('"');
                                $expect(':');
                                $propCount = (int)$readNum();
                                $expect(':'); $expect('{');
                                $props = [];
                                for ($p = 0; $p < $propCount; $p++) {
                                    $propKey = $parseValue();
                                    $propVal = $parseValue();
                                    $props[$propKey] = $propVal;
                                }
                                $expect('}');
                                
                                return ['__type' => 'object', '__class' => $className, '__props' => $props];
                                
                            default:
                                return FALSE;
                        }
                    };

                    return $parseValue();
                }


            static public function reserialize_parsed($value) 
                {
                    if (is_array($value) && array_key_exists('__type', $value) && $value['__type'] === 'object') 
                        {
                            $class = $value['__class'];
                            $props = $value['__props'];
                            $out = 'O:' . strlen($class) . ':"' . $class . '":' . count($props) . ':{';
                            foreach ($props as $k => $v) 
                                {
                                    $out .= 's:' . strlen($k) . ':"' . $k . '";';
                                    $out .= self::reserialize_parsed($v);
                                }
                            $out .= '}';
                            return $out;
                        }

                    if (is_null($value))    return "N;";
                    if (is_bool($value))    return "b:" . ($value ? "1" : "0") . ";";
                    if (is_int($value))     return "i:$value;";
                    if (is_float($value))   return "d:$value;";
                    if (is_string($value)) 
                        {
                            return 's:' . strlen($value) . ':"' . $value . '";';
                        }

                    if (is_array($value)) 
                        {
                            $count = count($value);
                            $out = "a:$count:{";
                            foreach ($value as $k => $v) {
                                if (is_int($k)) {
                                    $out .= "i:$k;";
                                } else {
                                    $out .= 's:' . strlen((string)$k) . ':"' . (string)$k . '";';
                                }
                                $out .= self::reserialize_parsed($v);
                            }
                            $out .= "}";
                            return $out;
                        }

                    if ( is_object($value)) 
                        {
                            return self::reserialize_parsed((array)$value);
                        }

                    return FALSE;
                }


            static public function walk_modify(&$node, callable $fn, array $path = []) 
                {
                    // If scalar (not array), call callback
                    if (!is_array($node)) {
                        $node = $fn($path, null, $node);
                        return;
                    }

                    // If object-representation array
                    if (array_key_exists('__type', $node) && $node['__type'] === 'object' && isset($node['__props'])) {
                        $class = $node['__class'];
                        $new = $fn(array_merge($path, ['@object:' . $class]), null, $node);
                        if ($new !== $node) {
                            $node = $new;
                            return;
                        }
                        foreach ($node['__props'] as $prop => &$val) {
                            $propPath = array_merge($path, ['@object:' . $class, $prop]);
                            self::walk_modify($val, $fn, $propPath);
                        }
                        unset($val);
                        return;
                    }

                    // Normal array
                    foreach ($node as $k => &$v) {
                        $keyPath = array_merge($path, [(is_int($k) ? $k : $k)]);
                        self::walk_modify($v, $fn, $keyPath);
                    }
                    unset($v);
                }

        }
?>