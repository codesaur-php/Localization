<?php

namespace codesaur\Localization;

use PDO;

use codesaur\DataObject\Model;
use codesaur\DataObject\Column;

class LanguageModel extends Model
{
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        
        $account_table = getenv('CODESAUR_ACCOUNT_TABLE', true);
        if (!$account_table) {
            $account_table = 'rbac_accounts';
            $this->exec('set foreign_key_checks=0');
        }
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
            new Column('app', 'varchar', 128, 'common'),
            new Column('code', 'varchar', 6),
            new Column('full', 'varchar', 64),
            new Column('description', 'text'),
            new Column('is_default', 'tinyint', 1, 0),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
           (new Column('created_by', 'bigint', 20))->foreignKey("$account_table(id) ON UPDATE CASCADE"),
            new Column('updated_at', 'datetime'),
           (new Column('updated_by', 'bigint', 20))->foreignKey("$account_table(id) ON UPDATE CASCADE")
        ));
        
        $this->setTable('language');
    }
    
    public function retrieve(string $app = 'common', int $is_active = 1)
    {
        $stmt = $this->select('*', array(
            'WHERE' => 'app=:app AND is_active=:is_active',
            'ORDER BY' => 'is_default Desc',
            'VALUES' => array('app' => $app, 'is_active' => $is_active)
        ));
        
        $languages = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $languages[$row['code']] = $row['full'];
        }
        
        return $languages;
    }

    public function getByCode(string $code, string $app = 'common', int $is_active = 1)
    {
         $stmt = $this->select('*', array(
            'WHERE' => 'app=:app AND code=:code AND is_active=:is_active',
            'ORDER BY' => 'is_default Desc', 'LIMIT' => 1,
            'VALUES' => array('app' => $app, 'code' => $code, 'is_active' => $is_active)
        ));
         
         if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return null;
    }

    function __initial()
    {
        $table = $this->getName();
        $nowdate = date('Y-m-d H:i:s');
        $query =  "INSERT INTO $table (created_at, code, full, app, is_default)"
                . " VALUES ('$nowdate', 'mn', 'Монгол', 'common', 1), ('$nowdate', 'en', 'English', 'common', 0)";

        $this->exec($query);
    }
}
