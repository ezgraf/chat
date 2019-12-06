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
        
        //tableau du status
        $status = ['status'=>'success'];
        
        // insertion d'un message
        if(isset($_POST["newMessage"])){
            if($_POST["newMessage"]){
        
                $messageContent = (string) addslashes($_POST['content']);
                $idAuthor = (int) $_POST['author'];
        
                if(isMember($idAuthor)){
                    $ins = $dbh->prepare("INSERT INTO messages(author, message) VALUES ($idAuthor,\"$messageContent\")");
                    $ins->execute();
                }else{
                    error('aucun membre avec cet id');
                }
            }
        }
        
        // récupération des messages
        $messages = $dbh->query('SELECT messages.id, messages.author, messages.date, messages.readmessage, messages.message, users.name FROM messages INNER JOIN users WHERE messages.author = users.id ORDER BY date');
        $data = $messages->fetchAll(PDO::FETCH_ASSOC);

    }else{
        session_destroy();
        $status = ['status'=>'error', 'msg'=>'user issue'];
    }

}else{
    
    $status = ['status'=>'error', 'msg'=>'user issue'];

}


//suppression connexion db
$dbh = null;

$datas = ['status'=>$status, 'datas'=>$data];

header("Access-Control-Allow-Origin: https://chat.digivore.fr");
header('Content-Type: application/json');
echo json_encode($datas);
?>