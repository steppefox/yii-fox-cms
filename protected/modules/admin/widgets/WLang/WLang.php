<?php
class WLang extends CWidget{
    public $errors=array();
    public $langs=array();
    public $langTitle=array(
        'ru'=>'Русский',
        'en'=>'English',
        'kz'=>'Қазақша',
        'ko'=>'Korean',
    );
    public $langErrors=array();
    public $cookiePath = '-adminPanel-language';
    public function init(){
        $_COOKIE[$this->cookiePath] = 'ru';
        $this->langs = $this->controller->langs;
        foreach ($this->errors as $key=>$val){
            $l = explode('_',$key);
            $l = $l[count($l)-1];
            if(in_array($l,$this->langs)){
                $this->langErrors[$l]+=1;
            }
        }
    }
    public function run(){
        $cs=Yii::app()->clientScript;
        $cs->registerCoreScript('cookie')->registerScript($this->cookiePath,"
        $('.language-select a').bind('click', function(e) {
            var t = $(this);
            var id = t.data('id');
            var langs = ".json_encode($this->langs).";
            $('.language-select .btn-primary').removeClass('btn-primary');
            t.addClass('btn-primary');
            if (id === 'all'){
                var targets = $('*[id$=\"_ru\"],*[id$=\"_en\"],*[id$=\"_kz\"],*[data-lang]');
                targets.each(function(i,q){
                    if($(q).data('lang')!=undefined){
                        $(q).slideDown();
                    }else{
                        $(q).parents('.control-group ').slideDown();
                    }
                });
            }else {
                for(var i in langs){
                    var lang = langs[i];
                    var targets = $('*[id$=\"_'+lang+'\"],*[data-lang=\"+lang+\"],*[data-lang=\"'+lang+'\"]');
                    if(lang!=id){
                        targets.each(function(i,q){
                            console.log($(q));
                            if($(q).data('lang')!=undefined){
                                $(q).slideUp();
                            }else{
                                $(q).parents('.control-group ').slideUp();
                            }
                        });
                    }else{
                        targets.each(function(i,q){
                            if($(q).data('lang')!=undefined){
                                $(q).slideDown();
                            }else{
                                $(q).parents('.control-group ').slideDown();
                            }
                        });
                    }
                }
            }
            $.cookie('".$this->cookiePath."',id);
            e.preventDefault();
        });
        ");
        if($_COOKIE[$this->cookiePath] && $_COOKIE[$this->cookiePath]!='all'){
            $cs->registerScript('-lang-show',"
                var langs = ".json_encode($this->langs).";
                var id = '".$_COOKIE[$this->cookiePath]."';
                for(var i in langs){
                    var lang = langs[i];
                    var targets = $('*[id$=\"_'+lang+'\"],*[data-lang=\"'+lang+'\"]');
                    if(lang!=id){
                        targets.each(function(i,q){
                            if($(q).data('lang')!=undefined){
                                $(q).slideUp();
                            }else{
                                $(q).parents('.control-group ').slideUp();
                            }
                        });
                    }else{
                        targets.each(function(i,q){
                            if($(q).data('lang')!=undefined){
                                $(q).slideDown();
                            }else{
                                $(q).parents('.control-group ').slideDown();
                            }
                        });
                    }
                }
            ");
        }
        $this->render('wLang');
    }

}
