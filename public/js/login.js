var login = new Vue({
    el:'#log',
    data:{
        author:"",
        pwd:"",
        regAuthor:"",
        regPwd:"",
        errorMsg : "",
        instance:'',
        isLogin : true,
        isRegister : false,
        env:{
            baseURL : BASEURL,
            apiURL : APIURL
        }
    },
    methods : {
        displayLogin : function(){
            this.isLogin = true;
            this.isRegister = false;
            this.regPwd = "";
            this.regAuthor = "";
            this.errorMsg = "";
        },

        displayRegister : function(){
            this.isLogin = false;
            this.isRegister = true;
            this.author = "";
            this.pwd = "";
            this.errorMsg = "";
        },

        login : function(){
            this.errorMsg = "Veuillez patienter";
            // requete log user
            var bodyFormData = new FormData();
            bodyFormData.set('login', true);
            bodyFormData.set('user', this.author);
            bodyFormData.set('pwd', sha256(this.pwd).toUpperCase());
            // requete
            var vm = this;
            vm.instance.post(vm.env.apiURL + '/members.php', bodyFormData)
            .then(function (response) {
                console.log(response);

                var status = response.data.status.status;

                if(status == "success"){
                    vm.errorMsg = "";
                    window.location.href = vm.env.baseURL;
                }else{
                    vm.errorMsg = "Erreur : " + response.data.status.msg;
                }


            })
            .catch(function (error) {
                console.log(error);
            })
        },
        register : function(){
            this.errorMsg = "Veuillez patienter";
            if( this.regAuthor.length >= 5){
                if( this.regPwd.length >= 8 && this.regPwd.match(/[a-z]/gi) && this.regPwd.match(/[0-9]/gi)){
                    // requete register user
                    var bodyFormData = new FormData();
                    bodyFormData.set('register', true);
                    bodyFormData.set('user', this.regAuthor);
                    bodyFormData.set('pwd', sha256(this.regPwd).toUpperCase());
                    // requete
                    var vm = this;
                    vm.instance.post(vm.env.apiURL + '/members.php', bodyFormData)
                    .then(function (response) {
                        console.log(response);

                        var status = response.data.status.status;

                        if (status == "success"){
                            vm.displayLogin();
                            vm.author = vm.regAuthor;
                            vm.regAuthor = "";
                            vm.regPwd = "";
                            vm.errorMsg = "Utilisateur créé. Vous pouvez désormais vous connecter.";
                            
                        }else{
                            vm.errorMsg = "Erreur : " + response.data.status.msg;
                        }

                    })
                    .catch(function (error) {
                        console.log(error);
                    })

                }else{
                    this.errorMsg = "Le mot de passe doit faire au moins 8 caractères et être composé de chiffres et de lettres";
                }
            }else{
                this.errorMsg = "Le nom d'utilisateur doit faire au moins 5 lettres et/ou chiffres";
            }
        }
    },
    mounted : function(){
        var vm = this;
        this.instance = axios.create({
            baseURL: vm.env.APIURL,
            timeout: 1000,
            headers: {'Content-Type': 'multipart/form-data'}
        });
    },
})