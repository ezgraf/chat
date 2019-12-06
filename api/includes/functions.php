<?php

//vérifie l'existence d'un membre par son id
function isMember($id){
    global $dbh;
    $req = $dbh->query('SELECT COUNT(*) FROM users WHERE id='.$id);
    return ( $req->fetchColumn() > 0) ? true : false;
}


function error($message){
    global $status;
    $status =  ['status'=>'error', 'msg'=>$message];
}

?>