
var requestUrl = "../php/serve_requests.php";
//CLIENT

//LOGIN CLIENT
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
				window.open("clientInitial.html", "_self");
            } else {
		alert(resp.message);
	    }
        }
    });
});
//REGISTER CLIENT
$(document).ready(function(){
	$("#registerClient").click(function(event){
		event.preventDefault();
		var email = document.getElementById("Remail").value;
        var pwd = document.getElementById("Rpwd").value;
        var pwdRep = document.getElementById("RpwdRep").value;
		var scs = "1";
        
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
            document.form1.Method[0].checked?scs="1":scs="2";
			var data = "action=reg_client&email="+email+"&pass="+pwd+"&scs="+scs;
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
				if(scs=="1"){
					alert("Thank you for registering! We will send your transaction codes to your email once your registration is approved. Your password for the pdf is: "+ resp.pdf_password + " Do not forget it!");
				}else{
					alert("Thank you for registering! We will send your SCS PIN to your email once your registration is approved. Your password for the pdf is: "+ resp.pdf_password + " Do not forget it!");
				}
				window.open("index.html", "_self");
            } else {
		alert(resp.message);
	    }
        }
    });
});
//LOGOUT CLIENT
function logoutClient()
{
    $(function(){
        var data = "action=logout_client";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            window.open("index.html", "_self");
        }
    });
}
//GET ACCOUNT CLIENT
function getAccountClient()
{
    $(function(){ 
        var data = "action=get_account_client";
        $.post(requestUrl, data, success, "json");
        
        function success (resp)
        {
            if (resp.status=="true")
            {
                document.getElementById("accountEmail").innerHTML=resp.email; 
				document.getElementById("accountBalance").innerHTML=resp.balance; 
				document.getElementById("accountNumber").innerHTML=resp.account_number;
				var downLink = "<a href='../downloads/"+resp.account_number+".pdf' target='_blank'><img src='images/ButtonDownloadTransactions.gif'/></a>";
				document.getElementById("downloadButtonClient").innerHTML=downLink; 
            } else {
		alert(resp.message);
	    }
        }
    });
}
//GET HISTORY CLIENT
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
					row.append($("<td>" + value[4] + "</td>"));
					if (value[5]=="1")
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
            } else {
		alert(resp.message);
	    }
        }
    });
}   
//NEW TRANSACTION CLIENT
$(document).ready(function(){
	$("#newTransactionClient").click(function(event){
      event.preventDefault();
	  var dest = document.getElementById('Demail').value;
        var amount = document.getElementById('amount').value;
        var tancode_value = encodeURIComponent(document.getElementById('code').value);
		var description = encodeURIComponent(document.getElementById('desc').value);
		
		if (description.length <= 100){
			var data = "action=set_trans_form&account_num_dest="+dest+"&amount="+amount+"&tancode_value="+tancode_value+"&description="+description;
			$.post(requestUrl, data, success, "json");
        }else{
			alert("GO HOME, DIRTY HACKER!");
		}
        function success (resp)
        {
            if (resp.status=="true")
            {
                alert("Transaction was submitted. If the amount your transfer is larger than 10.000 Bananas, wait for approval!");
				window.open("clientInitial.html", "_self");
            }else {
		alert(resp.message);
	    }
        } 
    });
});


//DOWNLOAD SCS
function setSCSToDownload()
{
  $(function(){    
    var data = "action=download_scs_exe";
        $.post(requestUrl, data, success, "json");
        function success (resp)
        {
            if (resp.status=="true")
            {
				
            } else {
	      alert(resp.message);
	    }
        }
    });
}
//GET TAN CODE CLIENT
function getTancode()
{
    $(function(){ 
        var data = "action=get_tancode_id";
        $.post(requestUrl, data, success, "json");
        function success (resp)
        {
            if (resp.status=="true")
            {
				if (resp.tan_code_id!="-1"){
					document.getElementById("tanCode").innerHTML="TAN N: "+resp.tan_code_id;// if zero = SCS
				}else{
					document.getElementById("tanCode").innerHTML="TAN from SCS:"	
				}
				
            } else {
	      alert(resp.message);
	    }
        }
    });
}
//GET PDF CLIENT
function setFileToDownload()
{
  $(function(){    
    var data = "action=get_trans_client_pdf";
        $.post(requestUrl, data, success, "json");
        function success (resp)
        {
            if (resp.status=="true")
            {
				
            } else {
	      alert(resp.message);
	    }
        }
    });
}
//NEW FILE TRANSACTION CLIENT
$(document).ready(function(){
	$("#newFileTransactionClient").click(function(event){
      event.preventDefault();
	  data = $("#uploadFile").val();
 		alert("request");
         $.ajax({
             type: 'POST',
             url: requestUrl,
             data: data,
             cache: false,
             contentType: false,
             processData: false
         }).done(function(resp) {
		alert("response here");
             if (resp.status=="true")
            {
                alert("Transaction was submitted. If the amount you transfered is larger than 10.000 Bananas, wait for approval!");
				window.open("clientInitial.html", "_self");
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

function initUpload() {
	document.getElementById("file_upload_form").onsubmit=function() {
		document.getElementById("file_upload_form").target = "upload_target";
		document.getElementById("upload_target").onload = uploadDone; //This function should be called when the iframe has compleated loading
			// That will happen when the file is completely uploaded and the server has returned the data we need.
	}
}

function uploadDone() { //Function will be called when iframe is loaded
	var ret = frames['upload_target'].document.getElementsByTagName("body")[0].innerHTML;
	var data = eval("("+ret+")"); //Parse JSON // Read the below explanations before passing judgment on me
	
	if(data.success) { //This part happens when the image gets uploaded.
		document.getElementById("upload_details").innerHTML = "File uploaded!";
	}
	else if(data.failure) { //Upload failed - show user the reason.
		alert("Upload Failed: " + data.failure);
	}	
}
//DOWNLOAD PDF CLIENT
$(document).ready(function(){
	$("#downloadTransactionsClient").click(function(event){
		event.preventDefault();
        var data = "action=get_trans_client_pdf";
        $.post(requestUrl, data, success, "json");
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
				window.open("clientInitial.html", "_self");
            } else {
		alert(resp.message);
	    }
        }
    });
});

//EMPLOYEE

//LOGIN EMPLOYEE
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
		{alert(resp.message);}
		
        }
    });
});
//REGISTER EMPLOYEE
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
            } else
		{alert(resp.message);}
        }
    });
});
//LOGOUT EMPLOYEE
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
//PENDING TRANSACTIONS EMPLOYEE
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
					row.append($("<td>" + value[5] + "</td>"));
					row.append($("<td><input type='image' id='newTransYes"+(index+1)+"' onClick='approveTrans(this.id)' src='images/yes.gif' width='25' height='25' alt='' style='padding-right:10px;'/><input type='image' id='newTransNo"+(index+1)+"' onClick='rejectTrans(this.id)' src='images/no.gif' width='25' height='25' alt='' /></td>"));
                });
            } else {
		alert(resp.message);
	    }
		
        }
    });
}
//APPROVE TRANS EMP
function approveTrans(id)
{
	$(function(){  
        
        
		var table = document.getElementById("tablesTrans");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		var data = "action=approve_trans&trans_id="+text;
	
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				alert("Transaction "+text+" has been approved.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}//REJECT TRANS EMP
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
				alert("Transaction "+text+" has been rejected.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}
//PENDING REG EMP
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
					row.append($("<td><input type='text' name='balance' id='balance' style='width:90%;' /></td>"));
					row.append($("<td><input type='image' id='newRegYes"+(index+1)+"' onClick='approveReg(this.id)' src='images/yes.gif' width='25' height='25' alt='' style='padding-right:10px;'/><input type='image' id='newRegNo"+(index+1)+"' onClick='rejectReg(this.id)' src='images/no.gif' width='25' height='25' alt='' /></td>"));
                });
            } else {
		alert(resp.message);
	    }
        }
    });
}
//APPROVE REG EMP
function approveReg(id)
{
	$(function(){  
        
        
		var table = document.getElementById("tablesReg");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		var balance = row.cells[2].children[0].value;
		var data = "action=approve_user&email="+text+"&init_balance="+balance;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
				alert("User "+text+" has been added to database.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}
//REJECT REG EMP
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
				alert("User "+text+" has been removed from database.");
				window.open("employeeInitial.html", "_self");
            }
			else{alert(resp.message);}
        }
    });
}
//ALL USERS EMP
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
					row.append($("<td>" + value + "</td>"));
					row.append($("<td><input type='image' id='userDetails"+(index+1)+"' onClick='userDetails(this.id)' src='images/ButtonDetails.gif'/></td>"));
                });
            }else{alert(resp.message);}
        }
    });
}
//USER DETAILS EMP
function userDetails(id)
{
		var table = document.getElementById("tablesClients");
		var rowId = id.slice(-1);
		var row = table.rows[rowId];
		var text = row.cells[0].innerHTML;
		localStorage.setItem('_email', text);
		window.open("employeeViewClient.html", "_self");
}
//ACCOUNT DETAILS EMP
function accountDetails()
{
	$(function(){  
		var email = localStorage.getItem('_email');
        var data = "action=get_account_emp&email="+email;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                    var row = $("<tr/>");
                    $("#tablesAccount").append(row);
					row.append($("<td>" + email + "</td>"));
					row.append($("<td>" + resp.balance + "</td>"));
					row.append($("<td>" + resp.account_number + "</td>"));
					 
		var downLink = "Transactions <a href='../downloads/"+resp.account_number+".pdf' target='_blank'><img src='images/ButtonDownloadTransactions.gif'/></a>";
		document.getElementById("transactions").innerHTML=downLink; 
            }else{alert(resp.message);}
        }
    });
	
}
//DOWNLOAD FILE EMP
function setFileToDownloadEmp()
{
  $(function(){    
    var email = localStorage.getItem('_email');
    var data = "action=get_trans_emp_pdf&email="+email;
        $.post(requestUrl, data, success, "json");
        function success (resp)
        {
            if (resp.status=="true")
            {
				
            } else {
	      alert(resp.message);
	    }
        }
    });
}
//ALL TRANSACTIONS EMP
function transactionsEmployee()
{
	$(function(){  
		var email = localStorage.getItem('_email');
        var data = "action=get_trans_emp&email="+email;
        $.post(requestUrl, data, success, "json");
        
        function success(resp)
        {
            if (resp.status=="true")
            {
                
                $.each(resp.trans, function(index, value){
                    var row = $("<tr />");
                    $("#tablesTransOfAccount").append(row);
					row.append($("<td>" + value[0] + "</td>"));
					row.append($("<td>" + value[1] + "</td>"));
					row.append($("<td>" + value[2] + "</td>"));
					row.append($("<td>" + value[3] + "</td>"));
					row.append($("<td>" + value[4] + "</td>"));
					if (value[5]=="1")
					{
						row.append($("<td><img src='images/approved.gif' width='65' height='20' alt='' /></td>"));
					}
					else
					{
						row.append($("<td><img src='images/waiting.gif' width='65' height='20' alt='' /></td>"));
					}
                });
            }else{alert(resp.message);}
        }
    });
}

//SHARED

//CHANGE PASS
$(document).ready(function(){
	$("#sendNewPass").click(function(event){
		event.preventDefault();
		var token = location.search.split('token=')[1];
		var pwd = document.getElementById('RRpwd').value;
		var pwdC = document.getElementById ('RRpwdRep').value;
		
		if (pwd==pwdC){
		
			var data = "action=change_pass&token="+token+"&new_pass="+pwd;
			$.post(requestUrl, data, success, "json");
		}else{
			alert("Password confirmation is wrong!");
		}
		
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
				alert("Done! Now you can log in with your new password!");
				window.open("index.html", "_self");
            } else {
		alert(resp.message);
	    }
        }
    });
});

//Recover pass
$(document).ready(function(){
	$("#recoverPass").click(function(event){
		event.preventDefault();
		var email = document.getElementById('Remail').value;
        var data = "action=recover_pass&email="+email;
        $.post(requestUrl, data, success, "json");
        function success(resp)
        {
            if (resp.status=="true")//TODO
            {
		alert("You will soon receive an email with further instructions!");
		window.open("index.html", "_self");
            } else {
		alert(resp.message);
	    }
        }
    });
});

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
        alert("Welcome again!" + user);
     } else {
         user = prompt("Please enter your name:", "");
         if (user != "" && user != null) {
             setCookie("username", user, 365);
         }
    }
}
