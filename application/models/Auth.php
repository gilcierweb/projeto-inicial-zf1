<?php

class Application_Model_Auth extends Application_Model_Mapper {

    public static function authenticate(array $values) {
        if (!count($values))
            throw new Exception('Exception trying to authenticate using empty values');
        // Get our authentication adapter and check credentials
        $loginValue = $values['login'];
        $adapter = self::getAuthAdapter('login');
        $adapter->setIdentity($loginValue);
        $adapter->setCredential($values['password']);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    /**
     * This function generates a password salt as a string of x (default = 15) characters
     * ranging from a-zA-Z0-9.
     * @param $max integer The number of characters in the string
     */
    public static function generateSalt($max = 40) {
        $characterList = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=<>";
        $i = 0;
        $salt = "";
        do {
            $salt .= $characterList{mt_rand(0, strlen($characterList) - 1)};
            $i++;
        } while ($i <= $max);
        return $salt;
    }

    /**
     * @link http://blog.thiagobelem.net/criptografia-no-php-usando-sha512-whirlpool-e-salsa20/
     * SHA-512 string de 128 caracteres
     */
    public static function generateSaltSha512($max = 40) {
        $characterList = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=<>";
        $i = 0;
        $salt = "";
        do {
            $salt .= $characterList{mt_rand(0, strlen($characterList) - 1)};
            $i++;
        } while ($i <= $max);
        $salt;
        $codificada = hash('sha512', $salt);
        return $codificada; //string de 128 caracteres
    }

    public static function logOut() {
        Zend_Auth::getInstance()->clearIdentity();
    }

    private static function getAuthAdapter($loginField = 'login') {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName('users')
                ->setIdentityColumn($loginField)
                ->setCredentialColumn('password')
                ->setCredentialTreatment('SHA1(CONCAT(?,salt))');
        return $authAdapter;
    }

}
