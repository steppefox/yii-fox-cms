<?php
Yii::import('application.modules.admin.components.gapi');
/**
 * Statistics class.
 * Used the google GAPI class end calculated data for EFlot widget
 */
class Statistics extends CModel
{
    private $_ga_profile_id;
    private $_ga;
    public $start_date;
    public $end_date;

    private $totalPageviews;
    private $totalVisits;

    public function __construct()
    {
        $this->_ga_profile_id = Yii::app()->administration->ga_profile_id;
        //echo $this->_ga_profile_id;
        //echo Yii::app()->administration->ga_profile_id;

        //$ga = new gapi($ga_email,$ga_password);
        $this->_ga = new gapi(Yii::app()->params['gapi_login'],Yii::app()->params['gapi_ww']);
    }
    
    public function getLog()
    {

        $array1 = $this->_ga->requestReportData($this->_ga_profile_id,
                array(
                    'browser',
                    'browserVersion',
                    'operatingSystem',
                    //'operatingSystemVersion',
                    'country',
                    'region',
                    'city',
                    'date',
                ),
                array(
                    'pageviews',
                ),
                'date', null, null, null, 1, 10
                );

        $array2 = $this->_ga->requestReportData($this->_ga_profile_id,
                array(
                    'date',
                    'visitLength',
                    'screenResolution',
                    'pageTitle',
                    'landingPagePath',
                    'source',
                ),
                array(
                    'pageviews',
                ),
                'date', null, null, null, 1, 10
                );

        return array_merge($array1, $array2);
    }

    public function getLastUpdate()
    {
        return $this->_ga->getUpdated();
    }
    
    private function sec2hms ($sec, $padHours = false) 
    {

        // start with a blank string
        $hms = "";

        // do the hours first: there are 3600 seconds in an hour, so if we divide
        // the total number of seconds by 3600 and throw away the remainder, we're
        // left with the number of hours in those seconds
        $hours = intval(intval($sec) / 3600); 

        // add hours to $hms (with a leading 0 if asked for)
        $hms .= ($padHours) 
              ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
              : $hours. ":";

        // dividing the total seconds by 60 will give us the number of minutes
        // in total, but we're interested in *minutes past the hour* and to get
        // this, we have to divide by 60 again and then use the remainder
        $minutes = intval(($sec / 60) % 60); 

        // add minutes to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

        // seconds past the minute are found by dividing the total number of seconds
        // by 60 and using the remainder
        $seconds = intval($sec % 60); 

        // add seconds to $hms (with a leading 0 if needed)
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        // done!
        return $hms;

    }
    
    public function getTotals()
    {
        //$this->_ga->requestReportData($this->_ga_profile_id,array('date'),array('pageviews', 'visits','avgTimeOnPage' , 'visitBounceRate', 'percentNewVisits'), '-pageviews', null, null, null, 1, 20);
        
        $avg = $this->sec2hms($this->_ga->getAvgTimeOnPage()); //date("H:i:s",$this->_ga->getAvgTimeOnPage());
        $bounce = number_format($this->_ga->getVisitBounceRate(), 2)."%";
        
        return array(
            "pageviews"=>$this->_ga->getPageviews(),
            "visits"=>$this->_ga->getVisits(),
            "avgtimeonpage"=>$avg,
            "visitbouncerate"=>$bounce,
            //"percentnewvisits"=>$this->_ga->getPercentNewVisits(),
            //"percentNewVisits "=>$this->_ga->getPercentNewVisits(),
        );
        
        //return $totalen;
    }

    public function getPagepath()
    {
        $this->_ga->requestReportData($this->_ga_profile_id,array('pagepath', 'pageTitle'),array('pageviews', 'visits','avgTimeOnPage' , 'visitBounceRate'), '-pageviews', null, null, null, 1, 20);
        $pagepath = $this->_ga->getResults();
        return $pagepath;
    }
    
    public function getBrowser()
    {
        //Find browser statistics
        $this->_ga->requestReportData($this->_ga_profile_id,array('browser','browserVersion'),array('visits'), '-visits', null, null, null, 1, 15);
        $browser = array();
        $test = 0;
        foreach($this->_ga->getResults() as $result)
        {
            $browser[] = array(
                'label'=>$result->getBrowser() . " " . $result->getBrowserVersion(),
                'data'=>$result->getVisits(),
            );
        }
        return $browser;
    }

    public function getCountry()
    {
        $this->_ga->requestReportData($this->_ga_profile_id,array('country'),array('visits'), '-visits', null, null, null, 1, 10);

        $country = array();
        foreach($this->_ga->getResults() as $result)
        {
            $country[] = array(
                'label'=>$result->getCountry(),
                'data'=>$result->getVisits()
            );
        }
        return $country;
    }
    
    public function getPlace()
    {
        $this->_ga->requestReportData($this->_ga_profile_id,array('city'),array('visits'), '-visits', null, null, null, 1, 10);

        $country = array();
        foreach($this->_ga->getResults() as $result)
        {
            $country[] = array(
                'label'=>$result->getCity(),
                'data'=>$result->getVisits()
            );
        }
        return $country;
    }

    public function getSource()
    {
        $this->_ga->requestReportData($this->_ga_profile_id,array('source'),array('visits'), '-visits', null, null, null, 1, 10);

        $source = array();
        foreach($this->_ga->getResults() as $result)
        {
            $source[] = array(
                'label'=>$result->getSource(),
                'data'=>$result->getVisits()
            );
        }
        return $source;
    }

    public function getVisitorsAndPageviews()
    {
        $this->_ga->requestReportData($this->_ga_profile_id, array('date'), array('pageviews','visits'), 'date');

        //$this->totalVisits = $ga->getVisits();
        //$this->totalPageviews = $ga->getPageviews();

        $visits = array();
        $pageviews = array();
        foreach($this->_ga->getResults() as $result)
        {
            $pageviews[] = array(strtotime($result->getDate()).'000', $result->getPageviews());
            $visits[] = array(strtotime($result->getDate()).'000', $result->getVisits());
        }
        
        $restult = array(
            array(
                'label'=> 'Pageviews',
                'data'=>$pageviews,
                'points'=>array('show'=>true),
                'lines'=>array('show'=>true, 'fill'=>true),
            ),
            array(
                'label'=> 'Visitors',
                'data'=>$visits,
                'points'=>array('show'=>true),
                'lines'=>array('show'=>true, 'fill'=>true),
            )
        );
        
        return $restult;
    }

    public function getPageviews()
    {
        return $this->pageviews;
    }

    public function getTotalVisits()
    {
        $this->loadData();
        return $this->totalVisits;
    }

    public function getTotalPageviews()
    {
        return $this->totalPageviews;
    }

    public function attributeNames()
    {
        return array(
            'totalPageview' => 'Totaal aantal pagina weergaven',
            'totalVisits' => 'Totaal aantal unieke bezoeken',
            'pageview' => 'Pagina weergavens',
            'visitors' => 'Unieke bezoekers',
        );
    }
}