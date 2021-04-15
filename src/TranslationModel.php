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
            throw new Exception(__CLASS__ . ': Table name must be provided!');
        }
        
        parent::setTable("translation_$name", $collate);
    }

    public function getNames(): array
    {
        $database = $this->databaseName();
        $namelike = $this->quote('translation_%');
        $pdostmt = $this->prepare("SHOW TABLES FROM $database LIKE $namelike");
        $pdostmt->execute();
        
        $likeness = array();
        while ($rows = $pdostmt->fetch(PDO::FETCH_ASSOC)) {
            $likeness[] = current($rows);
        }
        
        $names = array();
        foreach ($likeness as $name) {
            if (in_array($name . '_content', $likeness)) {
                $names[] = substr($name, strlen('translation_'));
            }
        }
        
        return $names;
    }
    
    public function retrieve(?string $code = null) : array
    {
        $codeName = $this->getCodeColumn()->getName();
        
        $text = array();
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
    
    public function findKeyword(string $keyword)
    {
        $keywordColumn = $this->getColumn('keyword');

        $stmt_content_tables = $this->prepare(
                'SHOW TABLES FROM ' . $this->databaseName()
                . ' LIKE ' . $this->quote('translation_%_content'));
        $stmt_content_tables->execute();
        
        while ($name = $stmt_content_tables->fetch(PDO::FETCH_NUM)) {
            $table = substr($name[0], 0, strlen($name[0]) - strlen('_content'));
            $pdostmt = $this->prepare("SELECT * FROM $table WHERE {$keywordColumn->getName()}=:1");
            $pdostmt->bindParam(':1', $keyword, $keywordColumn->getDataType(), $keywordColumn->getLength());
            $pdostmt->execute();
            
            if ($pdostmt->rowCount() === 1) {
                $result = array('table' => $table);
                $result += $pdostmt->fetch(PDO::FETCH_ASSOC);                
                foreach ($this->getColumns() as $column) {
                    if (isset($result[$column->getName()])) {
                        if ($column->isInt()) {
                            $result[$column->getName()] = (int)$result[$column->getName()];
                        } elseif ($column->getType() == 'decimal') {
                            $result[$column->getName()] = (float)$result[$column->getName()];
                        }
                    }
                }                
                return $result;
            }
        }
        
        return null;
    }
}
