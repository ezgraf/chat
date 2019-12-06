<?php
//connexion mysql
include("includes/_env.php");
include("includes/db.php");
include("includes/functions.php");

session_start();

//tableau du status
$status = ['status'=>'error'];

// message de retour
$data = "";

if(isset($_SESSION['chatAuthId'])){

    //recup nom
    $test = $dbh->query('SELECT COUNT(name) FROM users WHERE id='.$_SESSION['chatAuthId']);
    if($test->fetchColumn() > 0){
        // renvoi des données en fonction de la session
        $nom = $dbh->query('SELECT name FROM users WHERE id='.$_SESSION['chatAuthId']);
        $status = ['status'=>'success'];
        $data = [ 'id'=>$_SESSION['chatAuthId'], 'name'=>$nom->fetchAll(PDO::FETCH_ASSOC)[0]['name'] ];
    }else{
        session_destroy();
    }


}else{

    //signup
    if( isset($_POST["register"]) ){

        if ($_POST['register']){

            $user = (string) addslashes($_POST['user']);
            $pwd = (string) addslashes($_POST['pwd']);

            if($user != "" && $pwd != ""){

                //vérifie si l'user existe
                if ($test = $dbh->query('SELECT COUNT(*) FROM users WHERE name="'.$user.'" AND pwd="'.$pwd.'"')){
                    if ($test->fetchColumn() == 0){

                        $member = $dbh->query('INSERT INTO users(name,pwd) VALUE("'.$user.'","'.$pwd.'")');
                        if( $member ){
                            $status = ['status'=>'success'];
                            $data = $dbh->lastInsertId();

                        }else{
                            error('sql error');
                        }

                    }else{
                        error('user already exists');
                    }
    
                }else{
                    error('sql error');
                }

            }else{
                error('wrong datas');
            }


        }else{
            error('wrong datas');
        }
    
    // login
    } elseif( isset($_POST["login"]) ){
        if($_POST["login"]){
    
            $user = (string) addslashes($_POST['user']);
            $pwd = (string) addslashes($_POST['pwd']);
    
            if($user != "" && $pwd != ""){
    
                //vérifie si l'user existe
                if ($test = $dbh->query('SELECT COUNT(*) FROM users WHERE name="'.$user.'" AND pwd="'.$pwd.'"')){
                    if ($test->fetchColumn() > 0){
                        $member = $dbh->query('SELECT id, name FROM users WHERE name="'.$user.'" AND pwd="'.$pwd.'"');
    
                        $status = ['status'=>'success'];
                        $data = $member->fetchAll(PDO::FETCH_ASSOC);
                        
                        $_SESSION['chatAuthId'] = $data[0]['id'];

                    }else{
                        error('no user');
                    }
    
                }else{
                    error('sql error');
                }
                
    
            }else{
    
                error("wrong datas");
    
            }
    
        }else{

            error("wrong datas");

        }
    }else{
       error('Pas de données');
    }
}



//suppression connexion db
$dbh = null;

// preparation de la reponse
$datas = ['status'=>$status, 'datas'=>$data];

header("Access-Control-Allow-Origin: https://chat.digivore.fr");
header('Content-Type: application/json');
echo json_encode($datas);
?>