<?php

namespace codesaur\Localization;

use PDO;
use Exception;

use codesaur\DataObject\Column;
use codesaur\DataObject\MultiModel;

class TranslationModel extends MultiModel
{
    function __construct(PDO $pdo, $accountForeignRef = null)
    {
        parent::__construct($pdo);
        
        $created_by = new Column('created_by', 'bigint', 20);
        $updated_by = new Column('updated_by', 'bigint', 20);
        if (!empty($accountForeignRef)) {
            if (is_array($accountForeignRef)) {
                call_user_func_array(array($created_by, 'foreignKey'), $accountForeignRef);
                call_user_func_array(array($updated_by, 'foreignKey'), $accountForeignRef);
            } else {
                $created_by->foreignKey($accountForeignRef, 'id');
                $updated_by->foreignKey($accountForeignRef, 'id');
            }
        }
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('keyword', 'varchar', 128))->unique(),
            new Column('type', 'int', 4, 0),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
            $created_by,
            new Column('updated_at', 'datetime'),
            $updated_by
        ));
        
        $this->setContentColumns(array(
            new Column('title', 'varchar', 1024)
        ));
    }
    
    function __initial()
    {
        $method = $this->getName();
        if (!method_exists(TranslationInitial::class, $method)) {
            return;
        }
        
        $this->setForeignKeyChecks(false);        
        TranslationInitial::$method($this);        
        $this->setForeignKeyChecks();
    }
    
    public function setTable(string $name, $collate = null)
    {
        if (empty($name)) {
            throw new Exception(__CLASS__ . ': Table name must be provided!', 1103);
        }
        
        parent::setTable("translation_$name", $collate);
    }
    
    public function retrieve(?string $code = null) : array
    {
        $text = array();
        $codeName = $this->getCodeColumn()->getName();
        if (empty($code)) {
            $stmt = $this->select(
                    "p.keyword as keyword, c.$codeName as $codeName, c.title as title",
                    array('WHERE' => 'p.is_active=1', 'ORDER BY' => 'p.keyword'));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $text[$row['keyword']][$row[$codeName]] = $row['title'];
            }
        } else {            
            $code = preg_replace('/[^A-Za-z]/', '', $code);
            $condition = array(
                'WHERE' => "c.$codeName=:1 AND p.is_active=1",
                'ORDER BY' => 'p.keyword',
                'PARAM' => array(':1' => $code)
            );
            $stmt = $this->select('p.keyword as keyword, c.title as title', $condition);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $text[$row['keyword']] = $row['title'];
            }
        }
        return $text;
    }
}
