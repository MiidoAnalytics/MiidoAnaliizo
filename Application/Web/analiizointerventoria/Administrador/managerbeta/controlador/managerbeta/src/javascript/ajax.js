
var parameters = JSON.parse("{}");
var interval;

function prepareService(action){
	$("#i_loading").fadeToggle(0);
	switch(action) {
		case '.login':
			$("#f_login").attr('onsubmit', 'return false;');
			parameters = JSON.parse("{}");
			parameters.action = "login";
			parameters.uname = $("#it_usuario").val();
			//parameters.hashpass = CryptoJS.MD5($("#ip_contrasena").val()).toString(CryptoJS.enc.Hex);
            parameters.hashpass = $("#ip_contrasena").val();
		break;
        case '.logout':
            parameters = JSON.parse("{}");
            parameters.action = "logout";
        break;
        case '.pollSaver':
            parameters.action = "pollSaver";
            parameters.poll = JSON.stringify(groups);
            parameters.name = sessionStorage.editionName;
            if(confirm(inf_dec_act_enc_act)){
                parameters.status = 1;
                socket.emit('pollIncoming', (JSON.stringify(groups)));
            }
            else
                parameters.status = 0;
            if (sessionStorage.editionIndex)
                parameters.index = sessionStorage.editionIndex;
        break;
        case '.pollFinder':
            parameters = JSON.parse("{}");
            parameters.action = "pollFinder";
        break;
         case '.projectFinder':
            parameters = JSON.parse("{}");
            parameters.action = "projectFinder";
        break;
	}
	execute();
	return false;
}

function execute() {
/*    console.log(JSON.stringify(parameters));*/
	$.post("lib/db/ajax.php",
        {
            arguments:  JSON.stringify(parameters)
        },
        function (data) {
            // console.log(data);
            switch(action) {
                case 0:
                	if(data > 0){
                		document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /> </form>';
                		document.getElementById("f_requester").submit();
                	} else {
                		document.getElementById("p_notice").innerHTML = err_us_pass;
                		$("#i_loading").fadeToggle(0);
                		$("#p_notice").fadeToggle(1500, function() {
                			interval=setInterval(function () {
                				clearInterval(interval);
        					    $("#p_notice").fadeToggle(1500);
        					    $("#f_login").attr('onsubmit', 'return prepareService(".login")');
        					}, 1000);
                		});
                	}
                break;
                case 1:
                    location.reload();
                break;
                case 5:
                     if (data > 0) {
                        releaseUnload();
                        document.getElementById('d_main').innerHTML = '<form id="f_requester" action="#" method="POST"> <input type="hidden" name="hiddenField" value="main" /> </form>';
                        alert(inf_su_enc_ha_gua);
                        document.getElementById("f_requester").submit();
                    } else
                        alert(err_gua_enc);
                break;
                case 6:
                    var stored = false;
                    do {
                        if(sessionStorage.allPollData) {
                            stored = true;
                            fillPolls("t_modifier_resumer", 2);
                        } else
                            sessionStorage.allPollData = data;
                    } while(!stored)
                    //console.log(data);
                break;
                case 8:
                    //console.log(data);
                    fillSelectProject(data);
                break;
            }
        }
    ).fail(function() {
    	alert(err_conn_serv);
    });
}