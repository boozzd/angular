function AuthService($http, ipCookie, $location, $rootScope,$q){
    return {
        auth: auth,
        isAuth: isAuth,
        logout: logout,
        register: register,
        getAuth: getAuth
    }

    function auth(credentials, callback){
        var deffered = $q.defer();
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded; charset=utf-8";
        $http({
            method: 'post',
            url: '/rest/?route=user/login',
            data: {'name': credentials.login, 'password': credentials.password}
        }).then(function(response){
            if(angular.isObject(response.data)){
                $rootScope.$emit('login', response.data);
                ipCookie('login',response.data);
                deffered.resolve(response.data);
            }else{
                deffered.reject('ошибка авторизации');
            }
        });
        return deffered.promise;
    }

    function isAuth(){
        var trigg = (ipCookie('login') === undefined) ? false : ipCookie('login');
        $rootScope.$emit('login',ipCookie('login'));
        return trigg;
    }

    function logout(){
        $http({
            method: "post",
            url: '/rest/?route=user/logout'
        }).then(function(response){
            if(response.data == 0){
                $rootScope.$emit('login', undefined);
                ipCookie.remove('login');
                $location.path('login');
            }
        });
        
    }

    function register(credentials, callback){
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded; charset=utf-8";
        $http({
            method: "post",
            url: '/rest/?route=user/register',
            data:{'name': credentials.login, 'password': credentials.password, 'email': credentials.email}
        }).then(function(response){
            if(response.data == 1){
                $q.resolve(response.data);
            }else{
                $q.reject('произошла ошибка регистрации');
            }

        });
        return $q.promise;
    }

    function getAuth(){
        return ipCookie('login') === undefined ? null : ipCookie('login');
    }
}



function PlacesService($http, $q){
    return {
        getPlaces: getPlaces,
        getPlace: getPlace
    };

    function getPlaces(page){
        var deffered = $q.defer();
        var page = page ? '&page='+page : '';
        $http.get('/rest/?route=places/index'+page).success(function(data){
            deffered.resolve(data);
        }).error(function(){
            deffered.reject('Произошла ошибка загрузки данных!');
        });
        return deffered.promise;
    }

    function getPlace(value){
        var deffered = $q.defer();
        $http.get('/rest/?route=places/view', {params: {place: value}})
            .success(function(data){
            deffered.resolve(data);
        })
            .error(function(){
                deffered.reject('Ошибка при загрузке данных!');
            });
        return deffered.promise;
    }
}