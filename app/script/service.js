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
            url: '/api/?route=user/login',
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
            url: '/api/?route=user/logout'
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
        $defer = $q.defer();
        $http({
            method: "post",
            url: '/api/?route=user/register',
            data:{'name': credentials.login, 'password': credentials.password, 'email': credentials.email}
        }).then(function(response){
            if(response.data == 1){
                $defer.resolve(response.data);
            }else{
                $defer.reject('произошла ошибка регистрации');
            }

        });
        return $defer.promise;
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

    function getPlaces(page, city){
        var deffered = $q.defer();
        var page = page ? '&page='+page : '';
        var ids = '';
        if(angular.isArray(city)){
            ids = '&town='
            angular.forEach(city, function(value){
                    ids += value+',';
            });
            ids = ids.slice(0, -1);
        }
        $http.get('/api/?route=places/index'+page+ids).success(function(data){
            deffered.resolve(data);
        }).error(function(){
            deffered.reject('Произошла ошибка загрузки данных!');
        });
        return deffered.promise;
    }

    function getPlace(value){
        var deffered = $q.defer();
        $http.get('/api/?route=places/view', {params: {place: value}})
            .success(function(data){
            deffered.resolve(data);
        })
            .error(function(){
                deffered.reject('Ошибка при загрузке данных!');
            });
        return deffered.promise;
    }
}

function CityService($http, $q){
    return {
        getCity: getCity,
        getTown: getTown
    };
/*=======================
    Получаем список стран
========================*/
    function getCity(){
        var deffered = $q.defer();
        $http({
            method: 'post',
            url: '/api/?route=places/getcity'
        }).then(function(response){
            if(angular.isObject(response.data)){
                deffered.resolve(response);
            }else{
                deffered.reject('Ошибка загрузки данных!');
            }
            
        });
        return deffered.promise;
    }
/*======================
    Получаем список город выбраной страны
=======================*/
    function getTown(id){
        var deffered = $q.defer();
        $http({
            method: 'post',
            url: '/api/?route=places/gettown',
            data: {'city': id}
        }).then(function(response){
            if(angular.isObject(response.data)){
                deffered.resolve(response);
            }else{
                deffered.reject('Ошибка загрузки данных!');
            }
            
        });
        return deffered.promise;
    }
}

/*=================
    Сервис для обмена данными пользователя
==================*/
function UserService($http, $q){
    return {
        getUsersData: getUsersData,
        getUserData: getUserData,
        removeUser: removeUser,
        changeUser: changeUser,
    }

    function getUsersData(){
        var deffered = $q.defer();
        $http({
            method: 'get',
            url: '/api/?route=user/getusers'
        }).then(function(response){
            if(angular.isObject(response.data)){
                deffered.resolve(response);
            }else{
                deffered.reject('Ошибка загрузки данных');
            }
        });
        return deffered.promise;
    }

    function getUserData(id){
        var deffered = $q.defer();
        $http({
            method: 'post',
            url: '/api/?route=user/getuser',
            data: {'id': id},
        }).then(function(response){
            if(angular.isObject(response.data)){
                deffered.resolve(response);
            }else{
                deffered.reject('Ошибка загрузки данных');
            }
        });
        return deffered.promise;
    }

    function removeUser(userId){
        var deffered = $q.defer();
        $http({
            method: 'post',
            url: '/api/?route=user/removeuser',
            data: {'id': userId},
        }).then(function(response){
            if(response.data != 0){
                deffered.resolve(true);
            }else{
                deffered.reject(false);
            }
        });
        return deffered.promise;
    }

    function changeUser(user){
        var deffered = $q.defer();
        $http({
            method: 'post',
            url: '/api/?route=user/changeuser',
            data: {'user':user},
        }).then(function(response){
            if(response.data !=0){
                deffered.resolve(true);
            }else{
                deffered.reject(false);
            }
        });
        return deffered.promise;
    }
    
}