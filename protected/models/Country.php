<?php
abstract class Country extends CModel
{
    private static $countries = array(
        //Code,       Inter name,  Local Name
        "at" => array("Austria", "Osterreich"),
        "au" => array("Ukraine", "Ukraina"),
        "be" => array("België", "Belgium"),
        "bg" => array("Bulgaria", "Bulgaria"),
        "ch" => array("Switzerland", "Schweiz"),
        "cy" => array("Cyprus", "Kypros"),
        "cz" => array("Czech Republic", "Česká Republika"),
        "de" => array("Germany", "Deutschland"),
        "dk" => array("Denmark", "Danmark"),
        "ee" => array("Estonia", "Eesti"),
        "fi" => array("Finland", "Finland"),
        "fr" => array("France", "France"),
        "gr" => array("Greece", "Ellada"),
        "hu" => array("Hungary", "Magyarország"),
        "ie" => array("Ireland", "Ireland"),
        "it" => array("Italy", "Italia"),
        "lv" => array("Latvia", "Latvija"),
        "lt" => array("Lithuania", "Lietuva"),
        "lu" => array("Luxembourg", "Luxembourg"),
        "mt" => array("Malta", "Malta"),
        "ae" => array("Dubai / United Arab Emirates", "Dubai / United Arab Emirates"),
        "nl" => array("The Netherlands", "Nederland"),
        "eu" => array("The Netherlands (eu)", "Nederland"),
        "no" => array("Norway", "Norge"),
        "pl" => array("Poland", "Polska"),
        "pt" => array("Portugal", "Portugal"),
        "ro" => array("Romania", "România"),
        "sk" => array("Slovakia (Slovak Republic)", "Slovensko"),
        "si" => array("Slovenia", "Slovenija"),
        "es" => array("Spain", "Espana"),
        "se" => array("Sweden", "Sverige"),
        "en" => array("United Kingdom", "United Kingdom"),
    );

    private static $languages = array(
        "en_gb"=>"English",
        "de"=>"German",
        "nl"=>"Dutch",
        "cs_cz"=>"Czech Republic",
        "sk"=>"Slovak",
        "fr"=>"French",
        "it"=>"Italian",
        "es"=>"Spanish",
        "hu"=>"Hungarian",
        "dk"=>"Danish",
        "no"=>"Norwegian",
        "pl"=>"Polish",
        "se"=>"Swedish",
        "si"=>"Slovenian",
    );

    public static function getByID($id)
    {
        $countries = self::$countries;
        return isset($countries[$id]) ? $countries[$id][0] : "unknown country ({$id})";
    }

    public static function getNameByID($id)
    {
        $countries = self::$countries;
        return isset($countries[$id]) ? $countries[$id][1] : "unknown country ({$id})";
    }

    public static function getLanguages()
    {
        $lang = self::$languages;
        $languages = array('en'=>'English');
        $directory= Yii::getPathOfAlias('application')."/messages/";
        $dirhandler = opendir($directory);

        while ($file = readdir($dirhandler))
        {
            if ($file != '.' && $file != '..')
            {
                $languages[$file] = isset($lang[$file]) ? $lang[$file] : "unknown language ({$file})";
            }
        }
        //close the handler
        closedir($dirhandler);
        return $languages;
    }

    public static function getDropDown()
    {
        $countries = self::$countries;

        $result = array();
        foreach($countries as $code => $country)
            $result[$code] = $country[0];

        return $result;
    }

    public static function showFlags()
    {
        $language = substr(Yii::app()->request->preferredLanguage, 0, 2);
        //Is the user on the site of his language?
        if ($language && $language != Yii::app()->administration->country_code)
        {
            //Is the language recodnized by this class?
            if(key_exists($language, self::$countries))
            {
                $administration = Administration::model()->findByAttributes(array('country_code'=>$language) );
                //Is the administration found and active?
                if($administration != null && $administration->active)
                {

                $return = '<a href="http://www.'.$administration->domain . '" title="' . $administration->title . '">';
                $return .= '<img src="' . Yii::app()->theme->baseUrl . '/img/flags/'. $language . '.png" border=0 /></a>';
                return $return;
                }
            }
        }
    }
}