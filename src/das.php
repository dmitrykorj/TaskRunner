<?php

function getName($name) {
    return "Your name is $name";
}

function foobar($arg, $arg2) {
    echo __FUNCTION__, " got $arg and $arg2\n";
}


    //foobar('one','two');
    call_user_func("foobar" , "one","two");





