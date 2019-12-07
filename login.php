<?php 
    include("api/includes/_env.php");

    session_start();

    if(isset($_SESSION['chatAuthId'])){
        header("Location:  ".ROOT."/index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Anychat Signin / Signup</title>
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <meta name="robots" content="noindex">

        <link rel="stylesheet" type="text/css" href="public/css/style.css" />

        <script src="public/js/sha256.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>    </head>

    <body>
        <main id="log">
            <img src="public/images/bg-chat-1.jpg" alt="Welcome to AnyChat !"/>
            <div class="textWrap">
                <h1>Anychat</h1>
                <h2>Connexion</h2>

                <div id="logWrapper">
                    <div id="logWrapper-actions">
                        <span @click="displayLogin" :class="{ active : isLogin }">Se connecter</span>
                        <span @click="displayRegister" :class="{ active : isRegister }">S'inscrire</span>
                    </div>
                    
                    <div id="formWrap">
                        
                        <form v-show="isLogin" id="loginForm">
                            <input type="text" placeholder="Pseudo" v-model="author">
                            <input type="password" v-model="pwd" placeholder="Mot de passe">
                            <button @click.prevent="login">Connexion</button>
                        </form>
                        
                        
                        <form v-show="isRegister" id="registerForm">
                            <input type="text" placeholder="Votre pseudo souhaité" v-model="regAuthor">
                            <input type="password" v-model="regPwd" placeholder="Mot de passe">
                            <button @click.prevent="register">S'inscrire</button>
                        </form>
                        
                        <div id="errorMsg">{{ errorMsg }}</div>
                    </div>

                </div>


                
            </div>


        </main>

        <script src="public/js/env.js"></script>
        <script src="public/js/login.js"></script>

    </body>
</html>