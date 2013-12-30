<?php

class Application_Model_Galeriasimagens extends Zend_Db_Table_Abstract
{

    protected $_name = 'galerias_imagens';
    protected $_primary = 'galeria_imagem_id';
    protected $_referenceMap = array(
        'produtos' => array(
            'columns' => 'galeria_id',
            'refTableClass' => 'Application_Model_Galerias',
            'refColumns' => 'galeria_id',
            'onDelete' => self::CASCADE
        )
    );

    public function getAll($id)
    {
        $where = $this->getAdapter()->quoteInto('gli.galeria_id = ?', $id);

        echo $sql = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('gli' => $this->_name))
        ->joinInner(array('gal' => 'galerias'), 'gli.galeria_id = gal.galeria_id')
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
