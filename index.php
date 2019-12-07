<?php 
    include("api/includes/_env.php");

    session_start();
    
    if(!isset($_SESSION['chatAuthId'])){
        header("Location: ".ROOT."/login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Anychat</title>
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <meta name="robots" content="noindex">
        
        <link rel="stylesheet" type="text/css" href="public/css/style.css" />

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>    </head>

    <body>
        <main>
            <img src="public/images/bg-chat-1.jpg" alt="Welcome to AnyChat !"/>
            <div class="textWrap">
                <h1>Hey guys !</h1>
                <h2>let's chat together</h2>
            </div>

            <div id="chat">
                <div id="chat-modal">
                    <header> Chat de {{ user.name }}</header>
                    <div id="chat-modal-conversation" @scroll="handleScroll" @mousemove="handleMouseMove">
                        <ul>
                            <li v-for="talk in talks" :class="[ talk.author == user.id ? 'self' : 'other' ]">
                                <span class="talk-date">{{ talk.name }} le {{ talk.date | toLocale }}</span>
                                <span v-if="talk.readmessage == true && talk.author == user.id" class="talk-read"></span>
                                <span class="talk" :class="{read : talk.readmessage == true}">{{ talk.message }}</span>
                            </li>
                        </ul>
                    </div>
                    <form id="chat-modal-texter">
                        <textarea v-model="message" :rows="rows.current" :style="{ overflow : rows.overflow }" @keyup.enter.exact="sendMessage" id="chat-modal-texter-input" placeholder="Message"></textarea>
                        <div id="chat-modal-texter-send" @click="sendMessage">></div>
                    </form>
                </div>
            </div>

        </main>
        
        

        <script src="public/js/env.js"></script>
        <script src="public/js/main.js"></script>
    </body>
</html>