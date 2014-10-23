
var requestUrl = "serve_requests.php";
//CLIENT

function testUrl()
{
//alert("1");
//var win= window.open("employeeLogin.html", "_self");
window.event.returnValue = false;
setTimeout(function(){window.location.assign("content.html");return false;},500);
//win.focus();
//alert("2");
//return false;
}

function loginClient()
{
	//window.open("employeeLogin.html");
    $(function(){    
        var email = document.getElementById('Lemail').value;
        var pwd = document.getElementById('Lpwd').value;
        
        var data = "action=login_client&email="+email+"&pass="+pwd;
        $.post(requestUrl, data, success, "json");
	alert("Req sent");
        function success(resp)
        {
		alert(resp.status);
            if (resp.status=="true")//TODO
            {
                //setCookie("user",resp.cookie,10);
                window.open("clientInitial.html", "_self");
		return false;
            } else if (resp.status=="false") {
		alert(resp.message);
	    }
        }
		
    });
}

function logoutClient()
{
    $(function(){
        var data = "action=logout_client";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            //setCookie("user","",10);
            window.open("index.html", "_self");
        }
    });
}


function registerClient()
{
	
    $(function(){  
        var email = document.getElementById("Remail").value;
        var pwd = document.getElementById("Rpwd").value;
        var pwdRep = document.getElementById("RpwdRep").value;
        
		if (email=="")
		{
			alert ("Please enter the email!");
			return;
		}
		if (pwd=="")
		{
			alert ("Please enter the password!");
			return;
		}
        if (pwd==pwdRep)
        {
            var data = "action=reg_client&email="+email+"&pass="+pwd;
            $.post(requestUrl, data, statusRegisterClient, "json");
        }
		else
		{
			alert ("Password confirmation is wrong!");
			//return;
		}
        
        function statusRegisterClient(resp)
        {
            if (resp.status=="true")//TODO
            {
                alert("Thank You! We send the transaction codes on your email address and then you can use our service");
				window.open("index.html");
            }
        }
    });
}

function getAccountClient()
{
    $(function(){ 
        var data = "action=get_account_client";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            if (resp.status=="true")
            {
                //TODO
            }
        }
    });
}

function getHistoryClient()
{
    $(function(){  
        var data = "action=get_trans_client";
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                
                $.each(resp.trans, function(index, value){
                    var row = $("<tr />");
                    $("#tablesTrans").append(row);
                    $.each(value, function(index, val){
                        row.append($("<td>" + val + "</td>"));
                    });
                });
            }
        }
    });
}   

function newTransactionClient()
{
    $(function(){ 
	
		var dest = document.getElementById('Demail').value;
        var amount = document.getElementById('amount').value;
		var tancode_id = document.getElementById('codeId').value;
        var tancode_value = document.getElementById('code').value;
        var data = "action=set_trans_form&email_dest="+dest+"&amount="+amount+"&tancode_id="+tancode_id+"&tancode_value="+tancode_value;
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            if (resp.status=="true")
            {
                alert("Transcation was submitted. If the amount you transfered is bigger than 10.000 Bananas, wait for approval!");
            }
        }
    });
}

function getTancode()
{
    $(function(){ 
        var data = "action=get_tancode_id";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            if (resp.status=="true")
            {
                $("#codeId").val(resp.tancode_id);
            }
        }
    });
}

function newFileTransactionClient()
{
     alert("works");
 	$(function() {
         //data = new FormData($('#form')[0]);
 		data = $("#file").val();
         console.log('Submitting');
 		alert("works");
         $.ajax({
             type: 'POST',
             url: requestUrl+"?action=set_trans_file&tancode_id="+document.getElementById('codeId').value,
             data: data,
             cache: false,
             contentType: false,
             processData: false
         }).done(function(data) {
             console.log(data);
         }).fail(function(jqXHR,status, errorThrown) {
             console.log(errorThrown);
             console.log(jqXHR.responseText);
             console.log(jqXHR.status);
         });
 	});
}

//EMPLOYEE
function loginEmployee()
{
alert("Start");
    $(function(){    
        var email = document.getElementById('Lemail').value;
        var pwd = document.getElementById('Lpwd').value;
        
        var data = "action=login_emp&email="+email+"&pass="+pwd;
        $.post(requestUrl, data, success, "json");
	alert("Request sent");
        
        function success(resp)
        {
		alert(resp.status);
            if (resp.status=="true")//TODO
            {
                setCookie("emp",resp.cookie,10);
                window.open("employeeInitial.html", "_self");
            }
        }
    });
}

function registerEmployee()
{
    $(function(){  
        var email = document.getElementById('Remail').value;
        var pwd = document.getElementById('Rpwd').value;
        var pwdRep = document.getElementById('RpwdRep').value;
        
        if (pwd==pwdRep)
        {
            var data = "action=reg_emp&email="+email+"&pass="+pwd;
            $.post(requestUrl, data, success, "json");
        }
		else
		{
			alert ("Password confirmation is wrong!");
			return;
		}
        
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
               alert("Thank you! We send a confirmation to your email address");
			   window.open("index.html", "_self");
            }
        }
    });
}

function logoutEmployee()
{
    $(function(){
        var data = "action=logout_emp";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            setCookie("emp","",10);
            window.open("index.html");
        }
    });
}

function newTransactionsEmployee()
{
	$(function(){  
        
        var data = "action=get_trans_emp";
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                
                $.each(resp.trans, function(index, value){
                    var row = $("<tr id="+"'"+index+"'"+"/>");
                    $("#tablesTrans").append(row);
                    $.each(value, function(index, val){
                        row.append($("<td>" + val + "</td>"));
                    });
					row.append($("<td><input type='image' name='imageField' onClick='approveTransEmployee()' src='images/ButtonLogout.gif' /><input type='image' name='imageField' onClick='rejectTransEmployee()' src='images/ButtonLogout.gif' /></td>"));
                });
            }
        }
    });
}

function approveTransEmployee()
{
	$(function(){  
        
        var data = "{action:approve_trans}";
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				
            }
        }
    });
}

//SHARED
function getParam(sParamName)
{
    var Params = location.search.substring(1).split("&amp;");
    
    for (var i = 0; i < Params.length; i++)
    { 
        if (Params[i].split("=")[0] == sParamName)
        { 
           if (Params[i].split("=").length > 1) variable = Params[i].split("=")[1]; 
           
           return variable;
        }
    }
    return "";
}

function setCookie(cname, cvalue, exminutes) {
    var d = new Date();
     d.setTime(d.getTime() + (exminutes*60*1000));
    var expires = "expires="+d.toUTCString();
     document.cookie = cname + "=" + cvalue + "; " + expires+ "; path=/";
 }

function getCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");

    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);

        x = x.replace(/^\s+|\s+$/g,"");

        if (x == c_name) {
            return unescape(y);
        }
    }
}

function checkCookie() {
    var user = getCookie("username");
     if (user != "") {
        alert("Welcome again " + user);
     } else {
         user = prompt("Please enter your name:", "");
         if (user != "" && user != null) {
             setCookie("username", user, 365);
         }
    }
}
