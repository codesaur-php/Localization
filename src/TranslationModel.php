<?php

namespace codesaur\Localization;

use PDO;
use Exception;

use codesaur\DataObject\Column;
use codesaur\DataObject\MultiModel;

class TranslationModel extends MultiModel
{
    function __construct(PDO $conn)
    {
        parent::__construct($conn);
        
        $account_table = getenv('CODESAUR_ACCOUNT_TABLE', true);
        if (!$account_table) {
            $account_table = 'rbac_accounts';
            $this->exec('set foreign_key_checks=0');
        }
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
           (new Column('keyword', 'varchar', 128))->unique(),
            new Column('type', 'int', 4, 0),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->foreignKey("$account_table(id) ON UPDATE CASCADE"),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->foreignKey("$account_table(id) ON UPDATE CASCADE")
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
        
        TranslationInitial::$method($this);
    }
    
    public function setTable(string $name, $collate = null): bool
    {
        if (empty($name)) {
            throw new Exception(__CLASS__ . ': Table name must be provided!');
        }
        
        return parent::setTable("translation_$name", $collate);
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
    
    public function retrieve(string $code = null) : array
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
            $stmt = $this->select('p.keyword as keyword, c.title as title', array(
                'WHERE' => "c.$codeName=:$codeName AND p.is_active=1", 'ORDER BY' => 'p.keyword', 'VALUES' => [$codeName => $code]
            ));
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
            $pdostmt = $this->prepare("SELECT * FROM $table WHERE {$keywordColumn->getName()}=:keyword");
            $pdostmt->bindParam(':keyword', $keyword, $keywordColumn->getDataType(), $keywordColumn->getLength());
            $pdostmt->execute();
            
            if ($pdostmt->rowCount() === 1) {
                $result = array('table' => $table);
                $result += $pdostmt->fetch(PDO::FETCH_ASSOC);
                
                return $result;
            }
        }
        
        return null;
    }
}
