<?php
class c_index{
    //default controller
    public function index(){
        //echo 'You have a 木木夕';
        call('css',array(array('huacha/style.css','huacha/home.css')));
        call('css',array('#abc {display:none}','G'));
        call('js', array('huacha/style.js'));
        call('js', array(array('s'=>$_SERVER),'G'));

        $data = array('test' => 'abc');
        //$data['header'] = call('html',array('index.html',array(),1));
        $data['footer'] = call('html',array('index.html',array(),1));
        call('html', array('index.html',$data));
    }

    private function abc(){
        echo 'abc';
    }
}
