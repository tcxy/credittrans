//定义HTTP连接对象
var xmlHttp;

//实例化HTTP连接对象
function createXmlHttpRequest() {
    if(window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else if(window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
}

//发起登录请求
function login() {
    createXmlHttpRequest();
    var name = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if(name == null || name == "") {
        innerHtml("please enter username");
        return;
    }
    if(password == null || password == "") {
        innerHtml("please enter password");
        return;
    }
    var url = "127.0.0.1:8000/validate";
    xmlHttp.open("POST", url, true);
    xmlHttp.onreadystatechange = handleResult;
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.send("action=login&name=" + name + "&psd=" + password);
}

//处理服务器返回的结果/更新页面
function handleResult() {
    if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        var response = xmlHttp.responseText;
        var json = eval('(' + response + ')');
        if(json['login_result']) {
            alert("Login Successful！");
            // 页面跳转
            // window.location.href='transaction.html';
        } else {
            innerHtml("userName and password doesn't match");
        }
    }
}

//插入提示语
function innerHtml(message) {
    document.getElementById("tip").innerHTML = "<span style='font-size:16px; color:red;'>" + message + "</span>";
}