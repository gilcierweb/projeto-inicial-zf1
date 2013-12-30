<?php

class Mylib_Util
{
    /*
     * Apaga diretorio com imagens
     */

    public static function rmdir_recurse($path)
    {
        $path = @rtrim($path, '/') . '/';
        if (@is_dir($path)) {
            $handle = @opendir($path);
            while (false !== ($file = @readdir($handle))) {
                if ($file != '.' and $file != '..') {
                    $fullpath = $path . $file;
                    if (@is_dir($fullpath)) {
                        @$this->rmdir_recurse($fullpath);
                    } else {
                        @unlink($fullpath);
                    }
                }
            }
            @closedir($handle);
            @rmdir($path);
        }
    }

// ------------------------------------------------------------------------
    /**
     * funções do codeigniter
     * Create a Random String
     *
     * Useful for generating passwords or hashes.
     *
     * @access	public
     * @param	string	type of random string.  basic, alpha, alunum, numeric, nozero, unique, md5, encrypt and sha1
     * @param	integer	number of characters
     * @return	string
     */
    public function random_string($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'basic' : return mt_rand();
                break;
            case 'alnum' :
            case 'numeric' :
            case 'nozero' :
            case 'alpha' :

                switch ($type) {
                    case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric' : $pool = '0123456789';
                        break;
                    case 'nozero' : $pool = '123456789';
                        break;
                }

                $str = '';
                for ($i = 0; $i < $len; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
                }
                return $str;
                break;
            case 'unique' :
            case 'md5' :
                return md5(uniqid(mt_rand()));
                break;
            case 'encrypt' :
            case 'sha1' :
                return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
                break;
        }
    }

    /**
     * Site URL
     *
     * Create a local URL based on your basepath. Segments can be passed via the
     * first parameter either as a string or an array.
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public static function site_url($uri = null)
    {
        $ZF = Zend_Controller_Front::getInstance();

        if (empty($uri)) {
            $url_link = $ZF->getBaseUrl();
        } else {
            $url_link = $ZF->getBaseUrl() . $uri;
        }

        return $url_link;
    }

    /**
     * Word Limiter
     *
     * Limits a string to X number of words.
     *
     * @access	public
     * @param	string
     * @param	integer
     * @param	string	the end character. Usually an ellipsis
     * @return	string
     */
    public function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }

    /**
     * Character Limiter
     *
     * Limits the string based on the character count.  Preserves complete words
     * so the character count may not be exactly as specified.
     *
     * @access	public
     * @param	string
     * @param	integer
     * @param	string	the end character. Usually an ellipsis
     * @return	string
     */
    public function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (strlen($str) < $n) {
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n) {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';

            if (strlen($out) >= $n) {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
            }
        }
    }

    /**
     * Word Censoring Function
     *
     * Supply a string and an array of disallowed words and any
     * matched words will be converted to #### or to the replacement
     * word you've submitted.
     *
     * @access	public
     * @param	string	the text string
     * @param	string	the array of censoered words
     * @param	string	the optional replacement value
     * @return	string
     */
    function word_censor($str, $censored, $replacement = '')
    {
        if (!is_array($censored)) {
            return $str;
        }

        $str = ' ' . $str . ' ';

// \w, \b and a few others do not match on a unicode character
// set for performance reasons. As a result words like über
// will not match on a word boundary. Instead, we'll assume that
// a bad word will be bookeneded by any of these characters.
        $delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

        foreach ($censored as $badword) {
            if ($replacement != '') {
                $str = preg_replace("/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/i", "\\1{$replacement}\\3", $str);
            } else {
                $str = preg_replace("/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
            }
        }

        return trim($str);
    }

    /**
     * Anchor Link
     *
     * Creates an anchor based on the local URL.
     *
     * @access	public
     * @param	string	the URL
     * @param	string	the link title
     * @param	mixed	any attributes
     * @return	string
     */
    public static function anchor($uri = '', $title = '', $attributes = '')
    {
        $title = (string) $title;

        if (!is_array($uri)) {
            $site_url = (!preg_match('!^\w+://! i', $uri)) ? $this->site_url($uri) : $uri;
        } else {
            $site_url = $this->site_url($uri);
        }

        if ($title == '') {
            $title = $site_url;
        }

        if ($attributes != '') {
            $attributes = $this->_parse_attributes($attributes);
        }

        return '<a href="' . $site_url . '"' . $attributes . '>' . $title . '</a>';
    }

    /**
     * Anchor Link - Pop-up version
     *
     * Creates an anchor based on the local URL. The link
     * opens a new window based on the attributes specified.
     *
     * @access	public
     * @param	string	the URL
     * @param	string	the link title
     * @param	mixed	any attributes
     * @return	string
     */
    public function anchor_popup($uri = '', $title = '', $attributes = FALSE)
    {
        $title = (string) $title;

        $site_url = (!preg_match('!^\w+://! i', $uri)) ? $this->site_url($uri) : $uri;

        if ($title == '') {
            $title = $site_url;
        }

        if ($attributes === FALSE) {
            return "<a href='javascript:void(0);' onclick=\"window.open('" . $site_url . "', '_blank');\">" . $title . "</a>";
        }

        if (!is_array($attributes)) {
            $attributes = array();
        }

        foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0',) as $key => $val) {
            $atts[$key] = (!isset($attributes[$key])) ? $val : $attributes[$key];
            unset($attributes[$key]);
        }

        if ($attributes != '') {
            $attributes = $this->_parse_attributes($attributes);
        }

        return "<a href='javascript:void(0);' onclick=\"window.open('" . $site_url . "', '_blank', '" . $this->_parse_attributes($atts, TRUE) . "');\"$attributes>" . $title . "</a>";
    }

    /**
     * Create URL Title
     *
     * Takes a "title" string as input and creates a
     * human-friendly URL string with either a dash
     * or an underscore as the word separator.
     *
     * @access	public
     * @param	string	the string
     * @param	string	the separator: dash, or underscore
     * @return	string
     */
    function url_title($str, $separator = 'dash', $lowercase = FALSE)
    {
        if ($separator == 'dash') {
            $search = '_';
            $replace = '-';
        } else {
            $search = '-';
            $replace = '_';
        }

        $trans = array(
            '&\#\d+?;' => '',
            '&\S+?;' => '',
            '\s+' => $replace,
            '[^a-z0-9\-\._]' => '',
            $replace . '+' => $replace,
            $replace . '$' => $replace,
            '^' . $replace => $replace,
            '\.+$' => ''
        );

        $str = strip_tags($str);

        foreach ($trans as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        if ($lowercase === TRUE) {
            $str = strtolower($str);
        }

        return trim(stripslashes($str));
    }

    /**
     * Parse out the attributes
     *
     * Some of the functions use this
     *
     * @access	private
     * @param	array
     * @param	bool
     * @return	string
     */
    public function _parse_attributes($attributes, $javascript = FALSE)
    {
        if (is_string($attributes)) {
            return ($attributes != '') ? ' ' . $attributes : '';
        }

        $att = '';
        foreach ($attributes as $key => $val) {
            if ($javascript == TRUE) {
                $att .= $key . '=' . $val . ',';
            } else {
                $att .= ' ' . $key . '="' . $val . '"';
            }
        }

        if ($javascript == TRUE AND $att != '') {
            $att = substr($att, 0, -1);
        }

        return $att;
    }

}
