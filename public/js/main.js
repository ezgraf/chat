// vue main elem
var chatApp = new Vue({
    el:'#chat',
    data:{
        user:{
            id:'',
            name:''
        },
        rows : {
            min : 2,
            current : 2,
            max : 10,
            overflow : 'hidden'
        },
        message : "",
        talks : [],
        instance : '',
        updateTimer: '',
        scrollTimer: '',
        isScrolling : false,
        env:{
            baseURL : BASEURL,
            apiURL : APIURL
        }
    },
    mounted : function(){
        var vm = this;
        this.instance = axios.create({
            baseURL: vm.env.apiURL,
            timeout: 1000,
            headers: {'Content-Type': 'multipart/form-data'}
        });

        this.getId();

        this.getMessages();
    },
    computed : {

    },
    watch : {
        message : function(val){
            // calcule le nombre de ligne à mettre dans le textarea
            var elem = document.getElementById('chat-modal-texter-input');
            this.rows.overflow = 'hidden';
            elem.style.height = '';
            var computed = window.getComputedStyle(elem, null);
            var lineHeight = Number(computed.lineHeight.replace('px',''));

            var height = elem.scrollHeight;
            if (height > lineHeight * this.rows.max) height = lineHeight * this.rows.max;
            if (height < lineHeight * this.rows.min) height = lineHeight * this.rows.min;

            height = Math.ceil(height) + 1;
            var numLines = Math.ceil( height / lineHeight);

            elem.style.height = height+'px';
            if(numLines > this.rows.max) this.rows.overflow = 'auto';
        }
    },
    methods : {

        getId : function(){
            var vm = this;

            vm.instance.get('/members.php')
            .then(function (response) {
                if(response.data.status.status == "success"){
                    vm.user.id = parseInt(response.data.datas.id);
                    vm.user.name = response.data.datas.name;
                }else{
                    console.log(new Error(response.data.status.msg));
                    //window.location.href = vm.env.baseURL;
                }
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        getMessages : function(){
            var vm = this;

            vm.instance.get('/chat.php')
            .then(function (response) {
                if(response.data.status.status == "success"){
                    vm.setMessages(response.data.datas);
                    vm.talks = response.data.datas;
                }else{
                    if(response.data.status.msg == "user issue"){
                        //window.location.href = vm.env.baseURL;
                        console.log(new Error(response.data.status.msg));
                    }else{
                        console.log(new Error(response.data.status.msg));
                    }
                }
            })
            .catch(function (error) {
                console.log(error);
            })
        },

        sendMessage : function(){
            if(this.message != ""){
                //preparation des donnees
                this.message = this.message.trim();

                var bodyFormData = new FormData();
                bodyFormData.set('newMessage', true);
                bodyFormData.set('author', this.user.id);
                bodyFormData.set('content', this.message);
                
                // requete
                var vm = this;
                vm.instance.post('/chat.php', bodyFormData)
                .then(function (response) {
                    if(response.data.status.status == "success"){
                        vm.setMessages(response.data.datas);
                        vm.message = "";
                    }else{
                        console.log(new Error(response.data.status.msg));
                    }
                    
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
        },

        //insere les messages dans la vue
        setMessages : function(talks){
            this.talks = talks;
            this.setUpdateTimer();
        },
        
        // timer de recuperation des messages
        setUpdateTimer : function(){
            var vm = this;
            this.updateTimer = setTimeout( vm.getMessages, 1000 );
        },
        
        // scroll en bas de la conversation pour afficher le dernier message ( lors du rafraichissement des donnees )
        scrollToBottom : function(){
            if(this.isScrolling == false){
                var clientView = document.getElementById('chat-modal-conversation');
                if (clientView.scrollHeight > clientView.clientHeight){
                    clientView.scrollTo(0 , clientView.scrollHeight - clientView.clientHeight );
                }
            }
        },

        // timer qui réactive l'autoscroll
        setScrollTimer : function(){
            var vm = this;
            this.scrollTimer = setTimeout( function(){
                vm.isScrolling = false;
            }, 10000 );
        },

        // ne pas scroller automatiquement quand l'user a scrollé
        handleScroll : function(){
            clearTimeout(this.scrollTimer);
            this.isScrolling = true;
            this.setScrollTimer();
        },

        // on repousse le delai avant le prochain scroll tant que la souris bouge
        handleMouseMove : function(){
            if (this.isScrolling == true){
                clearTimeout(this.scrollTimer);
                this.setScrollTimer();
            }
        }
    },
    filters : {
        toLocale : function(value){
            if(!value) return '';
            var options = {day : "2-digit", month:"2-digit", year:"2-digit", hour:"2-digit", minute:"2-digit"}
            return new Date(value).toLocaleString('fr-FR', options);
        }
    },
    updated : function(){
        this.scrollToBottom();
    }
});