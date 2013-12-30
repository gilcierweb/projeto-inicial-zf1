<?php

class Application_Model_User extends Application_Model_Mapper
{

    protected $Model_DbTable = 'Application_Model_DbTable_User';
    protected $Model_DbView;
    protected $id;
    protected $role_id;
    protected $login;
    protected $password;
    protected $salt;
    protected $viewColumns = array();

    public function __set($name, $value)
    {
        if (('mapper' == $name) || !property_exists($this, $name)) {
            throw new Exception('Error __set() function, Invalid  property');
        }
        $this->$name = (string) $value;
    }

    public function __get($name)
    {
        if (('mapper' == $name) || !property_exists($this, $name)) {
            if (array_key_exists($name, $this->viewColumns)) {
                return $this->viewColumns[$name];
            }
            throw new Exception("Error __get() function, Invalid  property '$name'");
        }
        return $this->$name;
    }

    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = (string) $value;
            } elseif (in_array($key, $this->viewColumns)) {//Check if the property is a view
                $this->viewColumns[$key] = (string) $value;
                unset($this->viewColumns[array_search($key, $this->viewColumns)]);
            }
        }
        return $this;
    }

    public static function getByColumn($column = null, $value = null, $order = null)
    {
        $mapper = new self();
        $out = $mapper->MapperGetByColumn($column, $value, $order);
        return $out;
    }

    public static function getBySQLCondition(array $conditions, $is_OR = false, $order = null)
    {
        $mapper = new self();
        $out = $mapper->MapperGetBySQLCondition($conditions, $is_OR, $order);
        return $out;
    }

    public static function getAll($order = null)
    {
        $mapper = new self();
        $out = $mapper->MapperFetchAll($order);
        return $out;
    }

    /**
     *
     * Create and returns a new user
     * @param array $data: it requires at least three parameters: $data['role_id',  $data['login'] and  $data['password']
     * @throws Exception
     */
    public static function createUser(array $data)
    {
        if (!$data['role_id'] || !$data['login'] || !$data['password'])
            throw new Exception('role_id, login and password must be provided in the function createUser().');
        $data['id'] = self::save($data);
        $data['salt'] = Application_Model_Auth::generateSaltSha512();
        $data['password'] = sha1($data['password'] . $data['salt']);
        self::save($data);
        //Return created user:
        return self::getById($data['id']);
    }

    public static function select($sql = null, $params = null)
    {
        $mapper = new self();
        $out = $mapper->MapperSelect($sql, $params);
        return $out;
    }

    public static function save(array $data)
    {
        $mapper = new self();
        $out = $mapper->MapperSave($data);
        return $out;
    }

    public static function getById($id = null)
    {
        $mapper = new self();
        $out = $mapper->MapperGetById($id);
        return $out;
    }

    public static function getCurrentUser()
    {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            return 0;
        }
        $id = $auth->getIdentity()->id;
        $user = Application_Model_User::getById($id);
        return (count($user)) ? $user[0]->toArray() : 0;
    }

    public function getViewProperty($column = null)
    {
        return (in_array($column, $this->viewColumns)) ? $column : null;
    }

}
