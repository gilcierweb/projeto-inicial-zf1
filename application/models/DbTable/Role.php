<?php
/* Application/models/DbTable/Role.php */
class Application_Model_DbTable_Role extends Zend_Db_Table_Abstract
{
    protected $_name = 'roles';
}
//Última coisa, você vai precisar adicionar um usuário padrão para todos os visitantes 
//não registrados. Execute o seguinte comando em qualquer script apenas uma vez e marque a opção "usuários" tabela para ter certeza de que o usuário "convidado" foi criado.
//Application_Model_User::createUser( array('role_id' => 1, 'login' => 'Guest', 'password' => 'shocks'));