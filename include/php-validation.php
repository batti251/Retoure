<?php
function validate_inputs($customerno, $deliverynote, $contactnumber, $contactmail, $reason){
    $isCustomer   = validate_customerno($customerno);
    $isDelivery   = validate_deliveryNote($deliverynote);
    $isPhone      = validate_phonenumber($contactnumber);
    $isMail       = validate_mail($contactmail);
    $isReason     = validate_reason($reason);

    if ($isCustomer === false ||
        $isDelivery === false ||
        $isPhone    === false ||
        $isMail     === false ||
        $isReason   === false) {
        echo "Mindestens eine Eingabe ist ungültig.";
        return false;
    }
        echo "Alle Eingaben sind korrekt.";
    return true;
}


function translateReason($reason){
    $reasons = ['reason-1', 'reason-2', 'reason-3', 'miscellaneous'];
    if (in_array($reason, $reasons)){
        return $reason = "Ware beschädigt";
    } else  if (in_array($reason, $reasons)) {
        return $reason = "Falschlieferung";
    } else  if (in_array($reason, $reasons)) {
        return $reason = "Falschbestellung";
    } else if (in_array($reason, $reasons)) {
        return $reason = "sonstiger Grund";
    }
}

function validate_customerno($customerno){
if (preg_match('/^\d{5}$/', $customerno)) {
    return $customerno;
}   else {
    echo "Error: CustomerNo: NICHT DAS RICHTIGE FORMAT"." ".$customerno." "."es werden 5 Zeichen erwartet"."<br>";
    return false;
}
}

function validate_deliveryNote($deliverynote){
if(preg_match('/^\d{6,7}$/', $deliverynote)){
    return $deliverynote;
}    else {
    echo "DeliveryNote: NICHT DAS RICHTIGE FORMAT"." ".$deliverynote." "."es werden 6-7 Zeichen erwartet"."<br>";
    return false;
}
}

function validate_reason($reason){
    $validReasons = ['reason-1', 'reason-2', 'reason-3', 'miscellaneous'];
    if (in_array($reason, $validReasons, true)) {
        echo $reason."<br>";
         return $reason;
    } else {
    echo "Reason: kein erlaubter Grund"." ".$reason." "."bitte erlaubten Grund angeben"."<br>";
    return false;
    }
}

function validate_phonenumber($contactnumber){
    if(preg_match('/^(\+49|0)([1-9][0-9]{1,4})[ \-]?[0-9]{4,}$/', $contactnumber)){
        echo $contactnumber."<br>";
        return $contactnumber;
    } else {
     echo "Phone: NICHT DAS RICHTIGE FORMAT".$contactnumber."<br>";
    return false;
}
}

function validate_mail($contactmail){
    if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $contactmail)) {
        echo $contactmail."<br>";
        return $contactmail;
    } else {
    echo "Mail: keine valide Email"." ".$contactmail." "."bitte gültige Mail angeben"."<br>";
    return false;
    }
}
?>
