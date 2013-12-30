<?php

class Application_Model_Produtosimagens extends Zend_Db_Table_Abstract
{

    protected $_name = 'produtos_imagens';
    protected $_primary = 'prod_img_id';
    protected $_referenceMap = array(
        'produtos' => array(
            'columns' => 'produto_id',
            'refTableClass' => 'Application_Model_Produtos',
            'refColumns' => 'produto_id',
            'onDelete' => self::CASCADE
        )
    );

    public function getAll($id)
    {
        $where = $this->getAdapter()->quoteInto('pri.produto_id = ?', $id);

        $sql = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('pri' => $this->_name))
                ->joinInner(array('pro' => 'produtos'), 'pri.produto_id = pro.produto_id')
                ->where($where);

        return $this->fetchAll($sql);
    }

    public function getImg($id = array())
    {
        $where = $this->getAdapter()->quoteInto("{$this->_primary} IN (?)", $id);

        $sql = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name)
                ->where($where);

        return $this->fetchAll($sql);
    }

}
