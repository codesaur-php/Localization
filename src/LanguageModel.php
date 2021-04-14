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
        
        $this->setColumns(array(
           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
            new Column('app', 'varchar', 128, 'common'),
            new Column('code', 'varchar', 6),
            new Column('full', 'varchar', 64),
            new Column('description', 'text'),
            new Column('is_default', 'tinyint', 1, 0),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
            new Column('updated_at', 'datetime')
        ));
        
        $this->setTable('language');
    }
    
    public function retrieve(string $app = 'common', int $is_active = 1)
    {
        $condition = array(
            'WHERE' => 'app=:1 AND is_active=:2',
            'ORDER BY' => 'is_default Desc',
            'PARAM' => array(':1' => $app, ':2' => $is_active)
        );
        
        $languages = array();
        $stmt = $this->select('*', $condition);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $languages[$row['code']] = $row['full'];
        }
        
        return $languages;
    }

    public function getByCode(string $code, string $app = 'common', int $is_active = 1)
    {
        return $this->getRowBy(
                array(
                    'code' => $code,
                    'app' => $app,
                    'is_active' => $is_active
                ),
                'is_default Desc'
        );
    }

    function __initial()
    {
        $table = $this->getName();
        $nowdate = date('Y-m-d H:i:s');
        $query =  "INSERT INTO $table(created_at, code, full, app, is_default)"
                . " VALUES('$nowdate', 'mn', 'Монгол', 'common', 1),('$nowdate', 'en', 'English', 'common', 0)";

        $this->exec($query);
    }
}
