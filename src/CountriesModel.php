<?php

namespace codesaur\Localization;

use PDO;

use codesaur\DataObject\Column;
use codesaur\DataObject\MultiModel;

class CountriesModel extends MultiModel
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
           (new Column('id', 'varchar', 19))->primary()->unique()->notNull(),
            new Column('speak', 'varchar', 64),
            new Column('is_active', 'tinyint', 1, 1),
            new Column('created_at', 'datetime'),
            $created_by,
            new Column('updated_at', 'datetime'),
            $updated_by
        ));
        
        $this->setContentColumns(array(
            new Column('title', 'varchar', 255)
        ));
        
        $this->setTable('countries', 'utf8_unicode_ci');
    }
    
    public function retrieve(?string $code = null): array
    {        
        $countries = array();
        $codeName = $this->getCodeColumn()->getName();
        if (empty($code)) {
            $stmt = $this->select(
                    "p.id as id, c.$codeName as $codeName, c.title as title",
                    array('WHERE' => 'p.is_active=1', 'ORDER BY' => 'p.id'));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $countries[$row['id']][$row[$codeName]] = $row['title'];
            }
        } else {
            $code = preg_replace('/[^A-Za-z]/', '', $code);
            $condition = array(
                'WHERE' => "c.$codeName=:1 AND p.is_active=1",
                'ORDER BY' => 'p.id',
                'PARAM' => array(':1' => $code)
            );
            $stmt = $this->select("p.id as id, c.$codeName as $codeName, c.title as title", $condition);            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $countries[$row['id']] = $row['title'];
            }
        }
        return $countries;
    }
    
    function __initial()
    {
        $this->setForeignKeyChecks(false);
        
        $this->insert(array('id' => 'AD', 'speak' => 'Catal??'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Andorra')));
        $this->insert(array('id' => 'AE', 'speak' => '???????????????? ?????????????? ??????????????'), array('en' => array('title' => 'United Arab Emirates'), 'mn' => array('title' => '???????????? ?????????????? ????????????')));
        $this->insert(array('id' => 'AF', 'speak' => '?? ?????????????????? ???????????? ??????????????'), array('en' => array('title' => 'Afghanistan'), 'mn' => array('title' => '????????????????????')));
        $this->insert(array('id' => 'AI', 'speak' => 'English'), array('mn' => array('title' => 'Anguilla'), 'en' => array('title' => 'Anguilla')));
        $this->insert(array('id' => 'AL', 'speak' => 'Shqip'), array('en' => array('title' => 'Albania'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'AM', 'speak' => 'Armenian'), array('en' => array('title' => 'Armenia'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'AN', 'speak' => 'Cura??ao'), array('mn' => array('title' => '?????????????????????? ???????????????? ????????????'), 'en' => array('title' => 'Netherlands Antilles')));
        $this->insert(array('id' => 'AO', 'speak' => 'Portuguese'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Angola')));
        $this->insert(array('id' => 'AQ', 'speak' => 'English'), array('en' => array('title' => 'Antarctica'), 'mn' => array('title' => '??????????????????')));
        $this->insert(array('id' => 'AR', 'speak' => 'Spanish'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Argentina')));
        $this->insert(array('id' => 'AS', 'speak' => 'S??moa'), array('mn' => array('title' => 'American Samoa'), 'en' => array('title' => 'American Samoa')));
        $this->insert(array('id' => 'AT', 'speak' => 'Deutsch'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Austria')));
        $this->insert(array('id' => 'AU', 'speak' => 'English'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Australia')));
        $this->insert(array('id' => 'AW', 'speak' => 'Dutch'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Aruba')));
        $this->insert(array('id' => 'AZ', 'speak' => 'Azerbaijani'), array('en' => array('title' => 'Azerbaijan'), 'mn' => array('title' => '????????????????????')));
        $this->insert(array('id' => 'BA', 'speak' => 'Bosnian, Croatian and Serbian '), array('mn' => array('title' => '?????????? ????????????????????'), 'en' => array('title' => 'Bosnia and Herzegowina')));
        $this->insert(array('id' => 'BB', 'speak' => 'English'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Barbados')));
        $this->insert(array('id' => 'BD', 'speak' => 'Bengali'), array('en' => array('title' => 'Bangladesh'), 'mn' => array('title' => '??????????????????')));
        $this->insert(array('id' => 'BE', 'speak' => 'French, Dutch, German'), array('en' => array('title' => 'Belgium'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'BF', 'speak' => 'Burkina Faso'), array('mn' => array('title' => '?????????????? ????????'), 'en' => array('title' => 'Burkina Faso')));
        $this->insert(array('id' => 'BG', 'speak' => 'Bulgarian'), array('en' => array('title' => 'Bulgaria'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'BH', 'speak' => 'Arabic'), array('en' => array('title' => 'Bahrain'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'BI', 'speak' => 'French, Kirund'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Burundi')));
        $this->insert(array('id' => 'BJ', 'speak' => 'French'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Benin')));
        $this->insert(array('id' => 'BM', 'speak' => 'Bermud ????buca??'), array('en' => array('title' => 'Bermuda'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'BN', 'speak' => 'Malay'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Brunei Darussalam')));
        $this->insert(array('id' => 'BO', 'speak' => 'Spanish, Aymara, Chiquitano'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Bolivia')));
        $this->insert(array('id' => 'BR', 'speak' => 'Portuguese'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Brazil')));
        $this->insert(array('id' => 'BS', 'speak' => 'English'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Bahamas')));
        $this->insert(array('id' => 'BT', 'speak' => 'Dzongkha'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Bhutan')));
        $this->insert(array('id' => 'BV', 'speak' => 'Norwegia'), array('en' => array('title' => 'Bouvet Island'), 'mn' => array('title' => 'Bouvet Island')));
        $this->insert(array('id' => 'BW', 'speak' => 'English, Tswana'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Botswana')));
        $this->insert(array('id' => 'BY', 'speak' => 'Belarusian'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Belarus')));
        $this->insert(array('id' => 'BZ', 'speak' => 'English'), array('en' => array('title' => 'Belize'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'CA', 'speak' => 'English'), array('en' => array('title' => 'Canada'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'CC', 'speak' => 'English'), array('mn' => array('title' => 'Cocos (Keeling) Islands'), 'en' => array('title' => 'Cocos (Keeling) Islands')));
        $this->insert(array('id' => 'CD', 'speak' => 'French'), array('en' => array('title' => 'Congo, the Democratic Republic of the'), 'mn' => array('title' => '???????? ?????????????????? ?????????????????? ??????????')));
        $this->insert(array('id' => 'CF', 'speak' => 'Sango, French'), array('en' => array('title' => 'Central African Republic'), 'mn' => array('title' => '?????? ??????????')));
        $this->insert(array('id' => 'CG', 'speak' => 'French'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Congo')));
        $this->insert(array('id' => 'CH', 'speak' => 'French, German, Italian, Romans'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Switzerland')));
        $this->insert(array('id' => 'CI', 'speak' => 'French'), array('en' => array('title' => 'Cote d\'Ivoire'), 'mn' => array('title' => '?????? ????????????')));
        $this->insert(array('id' => 'CK', 'speak' => 'English, Rarotongan'), array('mn' => array('title' => 'Cook Islands'), 'en' => array('title' => 'Cook Islands')));
        $this->insert(array('id' => 'CL', 'speak' => 'Spanish'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Chile')));
        $this->insert(array('id' => 'CM', 'speak' => 'French, English'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Cameroon')));
        $this->insert(array('id' => 'CN', 'speak' => '??????'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'China')));
        $this->insert(array('id' => 'CO', 'speak' => 'Spanish'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Colombia')));
        $this->insert(array('id' => 'CR', 'speak' => 'Spanish'), array('en' => array('title' => 'Costa Rica'), 'mn' => array('title' => '?????????? ????????')));
        $this->insert(array('id' => 'CU', 'speak' => 'Spanish'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Cuba')));
        $this->insert(array('id' => 'CV', 'speak' => 'Portuguese'), array('mn' => array('title' => '???????? ??????????'), 'en' => array('title' => 'Cape Verde')));
        $this->insert(array('id' => 'CX', 'speak' => 'English'), array('en' => array('title' => 'Christmas Island'), 'mn' => array('title' => 'Christmas Island')));
        $this->insert(array('id' => 'CY', 'speak' => 'Turkish, Greek'), array('en' => array('title' => 'Cyprus'), 'mn' => array('title' => '????????')));
        $this->insert(array('id' => 'CZ', 'speak' => '??e??tina'), array('mn' => array('title' => '??????'), 'en' => array('title' => 'Czech Republic')));
        $this->insert(array('id' => 'DE', 'speak' => 'Deutsch'), array('en' => array('title' => 'Germany'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'DJ', 'speak' => 'French, Arabic'), array('mn' => array('title' => 'Djibouti'), 'en' => array('title' => 'Djibouti')));
        $this->insert(array('id' => 'DK', 'speak' => 'Danish'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Denmark')));
        $this->insert(array('id' => 'DM', 'speak' => 'English'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Dominica')));
        $this->insert(array('id' => 'DO', 'speak' => 'Spanish'), array('mn' => array('title' => '??????????????????'), 'en' => array('title' => 'Dominican Republic')));
        $this->insert(array('id' => 'DZ', 'speak' => 'Arabic, Berber'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Algeria')));
        $this->insert(array('id' => 'EC', 'speak' => 'Spanish'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Ecuador')));
        $this->insert(array('id' => 'EE', 'speak' => 'Estonian'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Estonia')));
        $this->insert(array('id' => 'EG', 'speak' => 'Arabic'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Egypt')));
        $this->insert(array('id' => 'EH', 'speak' => 'Arabic'), array('mn' => array('title' => '???????????? ??????????'), 'en' => array('title' => 'Western Sahara')));
        $this->insert(array('id' => 'ER', 'speak' => ''), array('en' => array('title' => 'Eritrea'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'ES', 'speak' => 'Espa??a'), array('en' => array('title' => 'Spain'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'ET', 'speak' => ''), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Ethiopia')));
        $this->insert(array('id' => 'FI', 'speak' => 'Finnish '), array('en' => array('title' => 'Finland'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'FJ', 'speak' => 'English, Fijian'), array('en' => array('title' => 'Fiji'), 'mn' => array('title' => '????????')));
        $this->insert(array('id' => 'FK', 'speak' => 'English'), array('mn' => array('title' => 'Falkland Islands (Malvinas)'), 'en' => array('title' => 'Falkland Islands (Malvinas)')));
        $this->insert(array('id' => 'FM', 'speak' => 'English'), array('en' => array('title' => 'Micronesia, Federated States of'), 'mn' => array('title' => '????????????????')));
        $this->insert(array('id' => 'FO', 'speak' => 'Danish, Faroese'), array('mn' => array('title' => 'Faroe Islands'), 'en' => array('title' => 'Faroe Islands')));
        $this->insert(array('id' => 'FR', 'speak' => 'French'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'France')));
        $this->insert(array('id' => 'GA', 'speak' => 'French, ??ab????'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Gabon')));
        $this->insert(array('id' => 'GB', 'speak' => 'British English'), array('mn' => array('title' => '???? ??????????????'), 'en' => array('title' => 'United Kingdom')));
        $this->insert(array('id' => 'GD', 'speak' => 'English'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Grenada')));
        $this->insert(array('id' => 'GE', 'speak' => 'Georgian: ??????????????????????????????'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Georgia')));
        $this->insert(array('id' => 'GF', 'speak' => 'Guyane fran??aise'), array('mn' => array('title' => '?????????????? ????????????'), 'en' => array('title' => 'French Guiana')));
        $this->insert(array('id' => 'GH', 'speak' => 'English'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Ghana')));
        $this->insert(array('id' => 'GI', 'speak' => 'English'), array('mn' => array('title' => '??????????????????'), 'en' => array('title' => 'Gibraltar')));
        $this->insert(array('id' => 'GL', 'speak' => 'Greenlandic'), array('en' => array('title' => 'Greenland'), 'mn' => array('title' => '????????????????')));
        $this->insert(array('id' => 'GM', 'speak' => 'English'), array('en' => array('title' => 'Gambia'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'GN', 'speak' => 'French'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Guinea')));
        $this->insert(array('id' => 'GP', 'speak' => 'French'), array('en' => array('title' => 'Guadeloupe'), 'mn' => array('title' => 'Guadeloupe')));
        $this->insert(array('id' => 'GQ', 'speak' => ''), array('en' => array('title' => 'Equatorial Guinea'), 'mn' => array('title' => '?????????????????? ????????????')));
        $this->insert(array('id' => 'GR', 'speak' => 'Greek: ????????????'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Greece')));
        $this->insert(array('id' => 'GS', 'speak' => 'English'), array('en' => array('title' => 'South Georgia and the South Sandwich Islands'), 'mn' => array('title' => 'South Georgia and the South Sandwich Islands')));
        $this->insert(array('id' => 'GT', 'speak' => ''), array('mn' => array('title' => 'Guatemala'), 'en' => array('title' => 'Guatemala')));
        $this->insert(array('id' => 'GU', 'speak' => 'Portuguese'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Guam')));
        $this->insert(array('id' => 'GW', 'speak' => ''), array('mn' => array('title' => 'Guinea-Bissau'), 'en' => array('title' => 'Guinea-Bissau')));
        $this->insert(array('id' => 'GY', 'speak' => 'English'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Guyana')));
        $this->insert(array('id' => 'HK', 'speak' => 'English, Chinese'), array('mn' => array('title' => '???????? ????????'), 'en' => array('title' => 'Hong Kong')));
        $this->insert(array('id' => 'HM', 'speak' => 'Australia'), array('mn' => array('title' => '???? ???????????????? ????????????'), 'en' => array('title' => 'Heard and Mc Donald Islands')));
        $this->insert(array('id' => 'HN', 'speak' => 'Spanish'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Honduras')));
        $this->insert(array('id' => 'HR', 'speak' => 'Hrvatska'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Croatia (Hrvatska)')));
        $this->insert(array('id' => 'HT', 'speak' => 'French Haitian Creole'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Haiti')));
        $this->insert(array('id' => 'HU', 'speak' => 'Hungarian: Magyarorsz??g'), array('en' => array('title' => 'Hungary'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'ID', 'speak' => 'Indonesian'), array('en' => array('title' => 'Indonesia'), 'mn' => array('title' => '????????????????')));
        $this->insert(array('id' => 'IE', 'speak' => 'English, Irish, Ulster Scots'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Ireland')));
        $this->insert(array('id' => 'IL', 'speak' => 'Hebrew, Arabic'), array('en' => array('title' => 'Israel'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'IN', 'speak' => 'Hindi, English'), array('en' => array('title' => 'India'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'IO', 'speak' => 'English'), array('mn' => array('title' => '???? ?????????????????? ???????????????????? ???????????? ??????????'), 'en' => array('title' => 'British Indian Ocean Territory')));
        $this->insert(array('id' => 'IQ', 'speak' => 'Arabic Kurdish'), array('en' => array('title' => 'Iraq'), 'mn' => array('title' => '????????')));
        $this->insert(array('id' => 'IR', 'speak' => 'Persian'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Iran (Islamic Republic of)')));
        $this->insert(array('id' => 'IS', 'speak' => 'Icelandic'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Iceland')));
        $this->insert(array('id' => 'IT', 'speak' => 'Italiana'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Italy')));
        $this->insert(array('id' => 'JM', 'speak' => 'English'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Jamaica')));
        $this->insert(array('id' => 'JO', 'speak' => 'Arabic: ????????????'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Jordan')));
        $this->insert(array('id' => 'JP', 'speak' => ''), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Japan')));
        $this->insert(array('id' => 'KE', 'speak' => 'Swahili, English'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Kenya')));
        $this->insert(array('id' => 'KG', 'speak' => 'Kyrgyz'), array('en' => array('title' => 'Kyrgyzstan'), 'mn' => array('title' => '??????????????????')));
        $this->insert(array('id' => 'KH', 'speak' => 'Khmer'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Cambodia')));
        $this->insert(array('id' => 'KI', 'speak' => 'English Gilbertese'), array('en' => array('title' => 'Kiribati'), 'mn' => array('title' => '????????????????')));
        $this->insert(array('id' => 'KM', 'speak' => 'Comorian, Arabic, French'), array('en' => array('title' => 'Comoros'), 'mn' => array('title' => '?????????????? ????????')));
        $this->insert(array('id' => 'KN', 'speak' => 'English'), array('mn' => array('title' => '???????? ???????? ???????????????? ????????????????'), 'en' => array('title' => 'Saint Kitts and Nevis')));
        $this->insert(array('id' => 'KP', 'speak' => '?????????'), array('en' => array('title' => 'Korea, Democratic People\'s Republic of'), 'mn' => array('title' => '?????????? ????????????????')));
        $this->insert(array('id' => 'KR', 'speak' => '?????????'), array('en' => array('title' => 'Korea, Republic of'), 'mn' => array('title' => '?????????? ????????????????')));
        $this->insert(array('id' => 'KW', 'speak' => 'Arabic'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Kuwait')));
        $this->insert(array('id' => 'KY', 'speak' => 'English'), array('en' => array('title' => 'Cayman Islands'), 'mn' => array('title' => '?????????????? ????????????')));
        $this->insert(array('id' => 'KZ', 'speak' => '???Kazakh'), array('mn' => array('title' => '??????????????????'), 'en' => array('title' => 'Kazakhstan')));
        $this->insert(array('id' => 'LA', 'speak' => 'Lao'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Lao People\'s Democratic Republic')));
        $this->insert(array('id' => 'LB', 'speak' => 'Arabic'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Lebanon')));
        $this->insert(array('id' => 'LC', 'speak' => 'English'), array('mn' => array('title' => '????????-????????'), 'en' => array('title' => 'Saint LUCIA')));
        $this->insert(array('id' => 'LI', 'speak' => 'German'), array('mn' => array('title' => '??????????????????????'), 'en' => array('title' => 'Liechtenstein')));
        $this->insert(array('id' => 'LK', 'speak' => 'Sinhala, Tamil, English'), array('mn' => array('title' => '?????? ??????????'), 'en' => array('title' => 'Sri Lanka')));
        $this->insert(array('id' => 'LR', 'speak' => 'English'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Liberia')));
        $this->insert(array('id' => 'LS', 'speak' => 'English, Southern Sotho'), array('en' => array('title' => 'Lesotho'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'LT', 'speak' => '???Lithuanian'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Lithuania')));
        $this->insert(array('id' => 'LU', 'speak' => 'German, French, Luxembourgish'), array('en' => array('title' => 'Luxembourg'), 'mn' => array('title' => '????????????????????')));
        $this->insert(array('id' => 'LV', 'speak' => '???Latvian'), array('en' => array('title' => 'Latvia'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'LY', 'speak' => 'Arabic'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Libyan Arab Jamahiriya')));
        $this->insert(array('id' => 'MA', 'speak' => 'Arabic'), array('en' => array('title' => 'Morocco'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'MC', 'speak' => 'French'), array('en' => array('title' => 'Monaco'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'MD', 'speak' => '???Moldovan'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Moldova, Republic of')));
        $this->insert(array('id' => 'MG', 'speak' => 'Malagasy, French'), array('en' => array('title' => 'Madagascar'), 'mn' => array('title' => '????????????????????')));
        $this->insert(array('id' => 'MH', 'speak' => 'English, Marshallese'), array('mn' => array('title' => '?????????????????? ????????????'), 'en' => array('title' => 'Marshall Islands')));
        $this->insert(array('id' => 'MK', 'speak' => 'Macedonian'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Macedonia, The Former Yugoslav Republic of')));
        $this->insert(array('id' => 'ML', 'speak' => '???Bambara'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Mali')));
        $this->insert(array('id' => 'MM', 'speak' => 'Burmese'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Myanmar')));
        $this->insert(array('id' => 'MN', 'speak' => '????????????'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Mongolia')));
        $this->insert(array('id' => 'MO', 'speak' => 'Chinese'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Macau')));
        $this->insert(array('id' => 'MP', 'speak' => 'English, Chamorro'), array('mn' => array('title' => '?????????? ???????????????????? ????????????'), 'en' => array('title' => 'Northern Mariana Islands')));
        $this->insert(array('id' => 'MQ', 'speak' => 'French'), array('en' => array('title' => 'Martinique'), 'mn' => array('title' => 'Martinique')));
        $this->insert(array('id' => 'MR', 'speak' => 'Arabic'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Mauritania')));
        $this->insert(array('id' => 'MS', 'speak' => 'English'), array('en' => array('title' => 'Montserrat'), 'mn' => array('title' => '????????????????????')));
        $this->insert(array('id' => 'MT', 'speak' => 'English, Maltese'), array('en' => array('title' => 'Malta'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'MU', 'speak' => ''), array('mn' => array('title' => 'Mauritius'), 'en' => array('title' => 'Mauritius')));
        $this->insert(array('id' => 'MV', 'speak' => ''), array('en' => array('title' => 'Maldives'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'MW', 'speak' => 'English'), array('en' => array('title' => 'Malawi'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'MX', 'speak' => 'Spanish'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Mexico')));
        $this->insert(array('id' => 'MY', 'speak' => '???Malaysian'), array('en' => array('title' => 'Malaysia'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'MZ', 'speak' => 'Portuguese'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Mozambique')));
        $this->insert(array('id' => 'NA', 'speak' => 'English, German'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Namibia')));
        $this->insert(array('id' => 'NC', 'speak' => 'French'), array('mn' => array('title' => '???????? ??????????????????'), 'en' => array('title' => 'New Caledonia')));
        $this->insert(array('id' => 'NE', 'speak' => 'French'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Niger')));
        $this->insert(array('id' => 'NF', 'speak' => 'English, Norfuk'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Norfolk Island')));
        $this->insert(array('id' => 'NG', 'speak' => 'English, Hausa, Yoruba, Igbo'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Nigeria')));
        $this->insert(array('id' => 'NI', 'speak' => 'Spanish'), array('mn' => array('title' => '??????????????????'), 'en' => array('title' => 'Nicaragua')));
        $this->insert(array('id' => 'NL', 'speak' => 'Dutch, Frisian, Papiamento'), array('en' => array('title' => 'Netherlands'), 'mn' => array('title' => '??????????????????')));
        $this->insert(array('id' => 'NO', 'speak' => 'Norwegian, Bokm??l, Nynorsk'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Norway')));
        $this->insert(array('id' => 'NP', 'speak' => 'Nepali'), array('en' => array('title' => 'Nepal'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'NR', 'speak' => 'English, Nauruan'), array('en' => array('title' => 'Nauru'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'NU', 'speak' => 'Niuean English'), array('mn' => array('title' => 'Niue'), 'en' => array('title' => 'Niue')));
        $this->insert(array('id' => 'NZ', 'speak' => 'English'), array('en' => array('title' => 'New Zealand'), 'mn' => array('title' => '???????? ????????????')));
        $this->insert(array('id' => 'OM', 'speak' => 'Arabic'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Oman')));
        $this->insert(array('id' => 'PA', 'speak' => 'Spanish'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Panama')));
        $this->insert(array('id' => 'PE', 'speak' => 'Spanish, Aymara, Quechua'), array('en' => array('title' => 'Peru'), 'mn' => array('title' => '????????')));
        $this->insert(array('id' => 'PF', 'speak' => 'French'), array('mn' => array('title' => '?????????????? ??????????????'), 'en' => array('title' => 'French Polynesia')));
        $this->insert(array('id' => 'PG', 'speak' => 'English, Tok Pisin, Hiri Motu'), array('mn' => array('title' => '???????? ????????????'), 'en' => array('title' => 'Papua New Guinea')));
        $this->insert(array('id' => 'PH', 'speak' => 'English, Filipino'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Philippines')));
        $this->insert(array('id' => 'PK', 'speak' => 'Urdu, English'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Pakistan')));
        $this->insert(array('id' => 'PL', 'speak' => 'Polska'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Poland')));
        $this->insert(array('id' => 'PM', 'speak' => 'French'), array('mn' => array('title' => 'St. Pierre and Miquelon'), 'en' => array('title' => 'St. Pierre and Miquelon')));
        $this->insert(array('id' => 'PN', 'speak' => 'English'), array('mn' => array('title' => 'Pitcairn'), 'en' => array('title' => 'Pitcairn')));
        $this->insert(array('id' => 'PR', 'speak' => 'Spanish, English Destinations'), array('en' => array('title' => 'Puerto Rico'), 'mn' => array('title' => '???????????? ????????')));
        $this->insert(array('id' => 'PT', 'speak' => 'Portuguese'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Portugal')));
        $this->insert(array('id' => 'PW', 'speak' => 'Palauan English'), array('en' => array('title' => 'Palau'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'PY', 'speak' => 'Spanish, Paraguayan Guaran??'), array('en' => array('title' => 'Paraguay'), 'mn' => array('title' => '????????????????')));
        $this->insert(array('id' => 'QA', 'speak' => 'Arabic'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Qatar')));
        $this->insert(array('id' => 'RE', 'speak' => ''), array('en' => array('title' => 'Reunion'), 'mn' => array('title' => 'Reunion')));
        $this->insert(array('id' => 'RO', 'speak' => '???Romanian '), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Romania')));
        $this->insert(array('id' => 'RU', 'speak' => '??????????????'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Russian Federation')));
        $this->insert(array('id' => 'RW', 'speak' => 'Kinyarwanda, French'), array('en' => array('title' => 'Rwanda'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'SA', 'speak' => 'Arabic'), array('mn' => array('title' => '???????????? ????????'), 'en' => array('title' => 'Saudi Arabia')));
        $this->insert(array('id' => 'SB', 'speak' => 'English'), array('mn' => array('title' => '???????????????? ????????????'), 'en' => array('title' => 'Solomon Islands')));
        $this->insert(array('id' => 'SC', 'speak' => 'French, English, Seselwa'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Seychelles')));
        $this->insert(array('id' => 'SD', 'speak' => 'Arabic, English'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Sudan')));
        $this->insert(array('id' => 'SE', 'speak' => '???Swedish'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Sweden')));
        $this->insert(array('id' => 'SG', 'speak' => 'English, Tamil, Malay, Mandarin'), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Singapore')));
        $this->insert(array('id' => 'SH', 'speak' => 'English'), array('mn' => array('title' => 'St. Helena'), 'en' => array('title' => 'St. Helena')));
        $this->insert(array('id' => 'SI', 'speak' => '???Slovene'), array('en' => array('title' => 'Slovenia'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'SJ', 'speak' => 'Norwegian, Bokm??l, Russian'), array('mn' => array('title' => 'Svalbard and Jan Mayen Islands'), 'en' => array('title' => 'Svalbard and Jan Mayen Islands')));
        $this->insert(array('id' => 'SK', 'speak' => '???Slovak'), array('en' => array('title' => 'Slovakia (Slovak Republic)'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'SL', 'speak' => 'English'), array('en' => array('title' => 'Sierra Leone'), 'mn' => array('title' => '???????????? ????????')));
        $this->insert(array('id' => 'SM', 'speak' => 'Italian'), array('mn' => array('title' => '?????? ????????????'), 'en' => array('title' => 'San Marino')));
        $this->insert(array('id' => 'SN', 'speak' => 'French'), array('en' => array('title' => 'Senegal'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'SO', 'speak' => 'Somali, Arabic'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Somalia')));
        $this->insert(array('id' => 'SR', 'speak' => 'Dutch'), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Suriname')));
        $this->insert(array('id' => 'ST', 'speak' => 'Portuguese'), array('en' => array('title' => 'Sao Tome and Principe'), 'mn' => array('title' => '?????? ???????? ??????????????')));
        $this->insert(array('id' => 'SV', 'speak' => 'Spanish'), array('mn' => array('title' => '?????? ??????????????????'), 'en' => array('title' => 'El Salvador')));
        $this->insert(array('id' => 'SY', 'speak' => 'Arabic'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Syrian Arab Republic')));
        $this->insert(array('id' => 'SZ', 'speak' => 'English, Swati'), array('mn' => array('title' => 'Swaziland'), 'en' => array('title' => 'Swaziland')));
        $this->insert(array('id' => 'TC', 'speak' => 'English'), array('mn' => array('title' => 'Turks and Caicos Islands'), 'en' => array('title' => 'Turks and Caicos Islands')));
        $this->insert(array('id' => 'TD', 'speak' => 'French, Arabic'), array('mn' => array('title' => '??????'), 'en' => array('title' => 'Chad')));
        $this->insert(array('id' => 'TF', 'speak' => 'French'), array('mn' => array('title' => 'French Southern Territories'), 'en' => array('title' => 'French Southern Territories')));
        $this->insert(array('id' => 'TG', 'speak' => 'French'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Togo')));
        $this->insert(array('id' => 'TH', 'speak' => 'Thai'), array('en' => array('title' => 'Thailand'), 'mn' => array('title' => '??????????????')));
        $this->insert(array('id' => 'TJ', 'speak' => '???Tajiks'), array('mn' => array('title' => '??????????????????'), 'en' => array('title' => 'Tajikistan')));
        $this->insert(array('id' => 'TK', 'speak' => 'Tokelauan'), array('en' => array('title' => 'Tokelau'), 'mn' => array('title' => 'Tokelau')));
        $this->insert(array('id' => 'TM', 'speak' => 'Turkmen'), array('en' => array('title' => 'Turkmenistan'), 'mn' => array('title' => '??????????????????????')));
        $this->insert(array('id' => 'TN', 'speak' => 'Arabic'), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Tunisia')));
        $this->insert(array('id' => 'TO', 'speak' => 'English, Tongan'), array('en' => array('title' => 'Tonga'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'TR', 'speak' => 'Turkish'), array('mn' => array('title' => '????????'), 'en' => array('title' => 'Turkey')));
        $this->insert(array('id' => 'TT', 'speak' => 'English'), array('mn' => array('title' => '???????? ?????????????????? ???????????????? ????????????'), 'en' => array('title' => 'Trinidad and Tobago')));
        $this->insert(array('id' => 'TV', 'speak' => 'Tuvaluan English'), array('en' => array('title' => 'Tuvalu'), 'mn' => array('title' => '????????????')));
        $this->insert(array('id' => 'TW', 'speak' => 'Chinese: ????????? or ?????????'), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Taiwan, Province of China')));
        $this->insert(array('id' => 'TZ', 'speak' => 'Swahili'), array('mn' => array('title' => '???????? ?????????????????? ??????????????'), 'en' => array('title' => 'Tanzania, United Republic of')));
        $this->insert(array('id' => 'UA', 'speak' => ''), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Ukraine')));
        $this->insert(array('id' => 'UG', 'speak' => ''), array('mn' => array('title' => '????????????'), 'en' => array('title' => 'Uganda')));
        $this->insert(array('id' => 'UM', 'speak' => ''), array('en' => array('title' => 'United States Minor Outlying Islands'), 'mn' => array('title' => 'United States Minor Outlying Islands')));
        $this->insert(array('id' => 'US', 'speak' => 'English'), array('mn' => array('title' => '?????????????????? ?????????????? ??????'), 'en' => array('title' => 'United States')));
        $this->insert(array('id' => 'UY', 'speak' => ''), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Uruguay')));
        $this->insert(array('id' => 'UZ', 'speak' => ''), array('en' => array('title' => 'Uzbekistan'), 'mn' => array('title' => '??????????????????')));
        $this->insert(array('id' => 'VA', 'speak' => ''), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Holy See (Vatican City State)')));
        $this->insert(array('id' => 'VC', 'speak' => ''), array('en' => array('title' => 'Saint Vincent and the Grenadines'), 'mn' => array('title' => '???????? ?????????????? ????????????????')));
        $this->insert(array('id' => 'VE', 'speak' => ''), array('en' => array('title' => 'Venezuela'), 'mn' => array('title' => '????????????????')));
        $this->insert(array('id' => 'VG', 'speak' => ''), array('mn' => array('title' => '?????????????? ???????????? (??????????????)'), 'en' => array('title' => 'Virgin Islands (British)')));
        $this->insert(array('id' => 'VI', 'speak' => ''), array('en' => array('title' => 'Virgin Islands (U.S.)'), 'mn' => array('title' => '?????????????? ???????????? (??????)')));
        $this->insert(array('id' => 'VN', 'speak' => ''), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Viet Nam')));
        $this->insert(array('id' => 'VU', 'speak' => ''), array('mn' => array('title' => '??????????????'), 'en' => array('title' => 'Vanuatu')));
        $this->insert(array('id' => 'WF', 'speak' => ''), array('en' => array('title' => 'Wallis and Futuna Islands'), 'mn' => array('title' => 'Wallis and Futuna Islands')));
        $this->insert(array('id' => 'WS', 'speak' => ''), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Samoa')));
        $this->insert(array('id' => 'YE', 'speak' => ''), array('mn' => array('title' => '??????????'), 'en' => array('title' => 'Yemen')));
        $this->insert(array('id' => 'YT', 'speak' => ''), array('mn' => array('title' => 'Mayotte'), 'en' => array('title' => 'Mayotte')));
        $this->insert(array('id' => 'ZA', 'speak' => ''), array('mn' => array('title' => '?????????? ??????????'), 'en' => array('title' => 'South Africa')));
        $this->insert(array('id' => 'ZM', 'speak' => ''), array('en' => array('title' => 'Zambia'), 'mn' => array('title' => '??????????')));
        $this->insert(array('id' => 'ZW', 'speak' => ''), array('mn' => array('title' => '????????????????'), 'en' => array('title' => 'Zimbabwe')));
        
        $this->setForeignKeyChecks();
    }
}
