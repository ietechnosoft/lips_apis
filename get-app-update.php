<?php

    $RESPONSE = array();
    $RESPONSE['result'] = 'success';
    $RESPONSE['build_version_code'] = (int) 1;
    $RESPONSE['build_version_name'] = '1.0.0';
    $RESPONSE['is_mandatory'] = (bool) false;

    echo json_encode($RESPONSE);
?>