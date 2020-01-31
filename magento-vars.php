<?php
// enable, adjust and copy this code for each store you run
// Store #0, default one
//if (isHttpHost("example.com")) {
//    $_SERVER["MAGE_RUN_CODE"] = "default";
//    $_SERVER["MAGE_RUN_TYPE"] = "store";
//}
//function isHttpHost($host)
//{
//    if (!isset($_SERVER['HTTP_HOST'])) {
//        return false;
//    }
//    return strpos(str_replace('---', '.', $_SERVER['HTTP_HOST']), $host) === 0;
//}

// Dev1
if (isHttpHost("dev1-z5fzyhy-g3o6dwn7txy2w.us-5.magentosite.cloud")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}

// Dev2
if (isHttpHost("dev2-iu3gqsi-g3o6dwn7txy2w.us-5.magentosite.cloud")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}

// Staging
if (isHttpHost("staging-5em2ouy-g3o6dwn7txy2w.us-5.magentosite.cloud")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}

// Staging (MC)
//if (isHttpHost("mcstaging.foxchapelpublishing.com")) {
//    $_SERVER["MAGE_RUN_CODE"] = "base";
//    $_SERVER["MAGE_RUN_TYPE"] = "website";
//}

// Production
if (isHttpHost("master-7rqtwti-g3o6dwn7txy2w.us-5.magentosite.cloud")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}

// Production (MC)
if (isHttpHost("mcprod.foxchapelpublishing.com")) {
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}

// Production Live With WWW
//if (isHttpHost("www.foxchapelpublishing.com")) {
//    $_SERVER["MAGE_RUN_CODE"] = "base";
//    $_SERVER["MAGE_RUN_TYPE"] = "website";
//}

// Production Live Without WWW
//if (isHttpHost("foxchapelpublishing.com")) {
//    $_SERVER["MAGE_RUN_CODE"] = "base";
//    $_SERVER["MAGE_RUN_TYPE"] = "website";
//}


function isHttpHost($host)
{
    if (!isset($_SERVER['HTTP_HOST'])) {
        return false;
    }
    // return strpos(str_replace('---', '.', $_SERVER['HTTP_HOST']), $host) === 0;
    return $_SERVER['HTTP_HOST'] ===  $host;
}

