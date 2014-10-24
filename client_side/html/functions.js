
var requestUrl = "serve_requests.php";
//CLIENT

/*$(document).ready(function(){
	$("#loginClient").click(function(event){
      event.preventDefault();
	  window.location = "content.html";
    });
});**/

$(document).ready(function(){
	$("#loginClient").click(function(event){
		event.preventDefault();
		var email = document.getElementById('Lemail').value;
        var pwd = document.getElementById('Lpwd').value;
        var data = "action=login_client&email="+email+"&pass="+pwd;
        $.post(requestUrl, data, success, "json");
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
                setCookie("client", email, 120);
				window.open("clientInitial.html", "_self");
            } else {
		alert(resp.message);
	    }
        }
    });
});

$(document).ready(function(){
	$("#registerClient").click(function(event){
		event.preventDefault();
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
                alert("Thank you for registering! We will send your transaction codes to your email once your registration is approved.");
				window.open("index.html", "_self");
            }
        }
    });
});

function logoutClient()
{
    $(function(){
        var data = "action=logout_client";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            setCookie("client", "client", 120);
            window.open("index.html", "_self");
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
					row.append($("<td>" + value[0] + "</td>"));
					row.append($("<td>" + value[1] + "</td>"));
					row.append($("<td>" + value[2] + "</td>"));
					row.append($("<td>" + value[3] + "</td>"));
					if (value[4]=="1")
					{
						row.append($("<td><img src='images/approved.gif' width='65' height='20' alt='' /></td>"));
					}
					else
					{
						row.append($("<td><img src='images/waiting.gif' width='65' height='20' alt='' /></td>"));
					}
                    /*$.each(value, function(index, val){
                        row.append($("<td>" + val + "</td>"));
                    });*/
                });
            }
        }
    });
}   

$(document).ready(function(){
	$("#newTransactionClient").click(function(event){
      event.preventDefault();
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
                alert("Transaction was submitted. If the amount you transfered is bigger than 10.000 Bananas, wait for approval!");
				window.open("clientInitial.html");
            }
        }
    });
});

/*function newTransactionClient()
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
}*/

function getTancode()
{
    $(function(){ 
        var data = "action=get_tancode_id";
        $.post(requestUrl, data, success, "json");
        alert("req sent");
        function success (resp)
        {
		        alert("got res");
            if (resp.status=="true")
            {
		alert(resp.tan_code_id);
                $("#codeId").val(resp.tan_code_id);
            } else {
	      alert(resp.message);
	    }
        }
    });
}

$(document).ready(function(){
	$("#newFileTransactionClient").click(function(event){
      event.preventDefault();
	  data = $("#file").val();
 		alert("works");
         $.ajax({
             type: 'POST',
             url: requestUrl+"?action=set_trans_file&tancode_id="+document.getElementById('codeId').value,
             data: data,
             cache: false,
             contentType: false,
             processData: false
         }).done(function(resp) {
             if (resp.status=="true")
            {
                alert("Transaction was submitted. If the amount you transfered is bigger than 10.000 Bananas, wait for approval!");
				window.open("clientInitial.html");
            }
			else
			{
				alert(resp.message);
			}
         }).fail(function(jqXHR,status, errorThrown) {
             console.log(errorThrown);
             console.log(jqXHR.responseText);
             console.log(jqXHR.status);
			 alert("No connection to server...sorry");
         });
    });
});

/*function newFileTransactionClient()
{
 	$(function() {
         //data = new FormData($('#form')[0]);
 		data = $("#file").val();
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
}*/

//EMPLOYEE
$(document).ready(function(){
	$("#loginEmployee").click(function(event){
      event.preventDefault();
	  var email = document.getElementById('Lemail').value;
        var pwd = document.getElementById('Lpwd').value;
        
        var data = "action=login_emp&email="+email+"&pass="+pwd;
        $.post(requestUrl, data, success, "json");
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
                window.open("employeeInitial.html", "_self");
            } else
		alert(resp.message);
        }
    });
});
/*function loginEmployee()
{
    $(function(){    
        var email = document.getElementById('Lemail').value;
        var pwd = document.getElementById('Lpwd').value;
        
        var data = "action=login_emp&email="+email+"&pass="+pwd;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
                setCookie("emp",resp.cookie,10);
                window.open("employeeInitial.html");
            }
        }
    });
}*/
$(document).ready(function(){
	$("#registerEmployee").click(function(event){
      event.preventDefault();
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
});

/*function registerEmployee()
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
}*/

function logoutEmployee()
{
    $(function(){
        var data = "action=logout_emp";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            window.open("index.html", "_self");
        }
    });
}

function newTransactionsEmployee()
{
	$(function(){  
        
        var data = "action=get_trans";
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                
                $.each(resp.trans, function(index, value){
                    var row = $("<tr />");
                    $("#tablesTrans").append(row);
					row.append($("<td>" + value[0] + "</td>"));
					row.append($("<td>" + value[1] + "</td>"));
					row.append($("<td>" + value[2] + "</td>"));
					row.append($("<td>" + value[3] + "</td>"));
					row.append($("<td>" + value[4] + "</td>"));
					row.append($("<td><input type='image' id='newTransYes"+(index+1)+"' onClick='approveTrans(this.id)' src='images/yes.gif' width='25' height='25' alt='' style='padding-right:10px;'/><input type='image' id='newTransNo"+(index+1)+"' onClick='rejectTrans(this.id)' src='images/no.gif' width='25' height='25' alt='' /></td>"));
                });
            } else {
		alert(resp.message);
	    }
		
        }
    });
}

function approveTrans(id)
{
	$(function(){  
        
        
		var table = document.getElementById("tablesTrans");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		var data = "action=approve_trans&trans_id="+text;
	alert(text);
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				alert("The transaction N "+text+" is approved.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}
function rejectTrans(id)
{
	$(function(){  
        
        
		var table = document.getElementById("tablesTrans");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		var data = "action=reject_trans&trans_id="+text;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				alert("The transaction N "+text+" is rejected.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}

function newRegistrationsEmployee()
{
    $(function(){  
        var data = "action=get_new_users";
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                
                $.each(resp.new_users, function(index, value){
                    var row = $("<tr/>");
                    $("#tablesReg").append(row);
					row.append($("<td>" + value[0] + "</td>"));
					row.append($("<td>" + value[1] + "</td>"));
					row.append($("<td><input type='image' id='newRegYes"+(index+1)+"' onClick='approveReg(this.id)' src='images/yes.gif' width='25' height='25' alt='' style='padding-right:10px;'/><input type='image' id='newRegNo"+(index+1)+"' onClick='rejectReg(this.id)' src='images/no.gif' width='25' height='25' alt='' /></td>"));
                });
            } else {
		alert(resp.message);
	    }
        }
    });
}

function approveReg(id)
{
	$(function(){  
        
        
		var table = document.getElementById("tablesReg");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		var data = "action=approve_user&email="+text;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				alert("The user "+text+" is added to database.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}
function rejectReg(id)
{
	$(function(){  
        
        
		var table = document.getElementById("tablesReg");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		var data = "action=reject_user&email="+text;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				alert("The user "+text+" is removed from database.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}

function allUsers()
{
	$(function(){  
        var data = "action=get_clients";
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                
                $.each(resp.clients, function(index, value){
                    var row = $("<tr/>");
                    $("#tablesClients").append(row);
					row.append($("<td>" + value + "</td>"));
					row.append($("<td><input type='image' id='userDetails"+index+"' onClick='userDetails(this.id)' src='images/ButtonDetails.gif'/></td>"));
                });
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
	return false;
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
