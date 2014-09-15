function AuthService($http, $cacheFactory){
    var authCache = $cacheFactory('auth');
    return {
        auth: auth,
        isAuth: isAuth,
        logout: logout,
        register: register,
        getAuth: getAuth
    }

    function auth(credentials, callback){
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded; charset=utf-8";
        $http({
            method: 'post',
            url: '/rest/?route=user/login',
            data: {'name': credentials.login, 'password': credentials.password}
        }).success(Success);
        
        function Success(data){
            if(angular.isObject(data)){
                authCache.put('login', data);
            }
            callback(authCache.get('login'));          

        }
    }

    function isAuth(){
        var trigg = (authCache.get('login') === undefined) ? false : true;
        return trigg;
    }

    function logout(callback){
        $http({
            method: "post",
            url: '/rest/?route=user/logout'
        }).success(Success);
        function Success(data){
            if(data == 0){
                authCache.removeAll();
                callback();
            }
        }
        
    }

    function register(credentials, callback){
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded; charset=utf-8";
        $http({
            method: "post",
            url: '/rest/?route=user/register',
            data:{'name': credentials.login, 'password': credentials.password, 'email': credentials.email}
        }).success(Success);

        function Success(data){
            callback(data);
        }
    }

    function getAuth(){
        return authCache.get('login') === undefined ? null : authCache.get('login');
    }
}