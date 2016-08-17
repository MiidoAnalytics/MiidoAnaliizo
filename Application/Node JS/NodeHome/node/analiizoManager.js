var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var redis = require("redis");
var client = redis.createClient();
var amqp = require('amqplib/callback_api');
var ss = require('socket.io-stream');
var path = require('path');
var fs = require('fs');

io.on('connection', function(socket){
  console.log('conexion establecida');
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});

//Socket incoming for Analiizo "Comfasucre".
io.on('connection', function(socket){
	socket.on('pollIncoming', function(poll){
	    client.set ("Poll", poll, function(error, result) {
	    	if (error) console.log('Error: ' + error);
	      	else {
	        	console.log('Data saved!');
	      	}
	    });
	});
});

//Socket incoming for Analiizo "Cirugía estética"
io.on('connection', function(socket){
	socket.on('pollIncomingCE', function(poll){
	    client.set ("PollCirEst", poll, function(error, result) {
	    	if (error) console.log('Error: ' + error);
	      	else {
	        	console.log('Data saved!');
	      	}
	    });
	});
	socket.on('pollIncomingDeveloper', function(poll){
	    client.set ("devPoll", poll, function(error, result) {
	    	if (error) console.log('Error: ' + error);
	      	else {
	        	console.log('Data saved!');
	      	}
	    });
	});
});

client.on("error", function (err) {
    console.log("Error " + err);
});

//socketResponse for Analiizo "Comfasucre"
io.on('connection', function(socket){
	socket.on('pollOutgoing', function(poll){
		if (poll == "requestPollData") {
			client.get("Poll", function(err, value) {
			    try {
			    	console.log((value));
			    	io.emit('pollOutgoing', (value));
			    } catch (e) {
			    	console.log(e);
			    }
			});
		}
	});
	socket.on('pollOutgoingDev', function(poll){
		if (poll == "requestPollData") {
			client.get("devPoll", function(err, value) {
			    try {
			    	console.log((value));
			    	io.emit('pollOutgoing', (value));
			    } catch (e) {
			    	console.log(e);
			    }
			});
		}
	});
});

//socketResponse for Analiizo "Cirugía estetica"
io.on('connection', function(socket){
	socket.on('pollOutgoingCE', function(poll){
		if (poll == "requestPollData") {
			client.get("PollCirEst", function(err, value) {
			    try {
			    	console.log((value));
			    	io.emit('pollOutgoingCE', (value));
			    } catch (e) {
			    	console.log(e);
			    }
			});
		}
	});
});

// prueba
io.on('connection', function(socket){
	socket.on('testPollIncoming', function(poll){
	    client.set ("testPoll", poll, function(error, result) {
	    	if (error) console.log('Error: ' + error);
	      	else {
	        	console.log('Data saved!');
	      	}
	    });
	});
	socket.on('testPollOutgoing', function(poll){
		if (poll == "requestPollData") {
			client.get("testPoll", function(err, value) {
			    try {
			    	console.log((value));
			    	io.emit('testPollOutgoing', (value));
			    } catch (e) {
			    	console.log(e);
			    }
			});
		}
	});
});


// presentacion en blanco universidad de cartagena

io.on('connection', function(socket){
	socket.on('udcPollIncoming', function(poll){
	    client.set ("udcPoll", poll, function(error, result) {
	    	if (error) console.log('Error: ' + error);
	      	else {
	        	console.log('Data saved!');
	      	}
	    });
	});

	var pg = require('pg');
	var conString = "postgres://postgres:Miido2015@localhost:5432/postgres";
	var resultado;
	var client = new pg.Client(conString);
	client.connect();

	socket.on('udcPollOutgoing', function(interviewerid){
			var query = "select distinct ee.intidestructura,ee.intidinterviewer,p.intidproyecto,p.nombre,p.intidcliente,se.estructura from administracion.encuestaencuestador as ee, administracion.estructuraencuestas as se,administracion.encuestadorproyecto as ep,administracion.proyecto as p where ee.intidinterviewer =" + interviewerid + " and ee.intidestructura = se.intidestructura and ee.intidinterviewer = ep.intidinterviewer and ep.intidproyecto = p.intidproyecto and ee.intistatus != 3 ";
			client.query(query, function(err, result){
				if(err){
					console.log(err);
				}else{
					if(result.rows.length > 0){
						var jsonPolls = JSON.parse("[]");
						for(var i =0; i< result.rows.length;i++){
							var jsonPoll = JSON.parse(JSON.stringify(result.rows[i].estructura));
							jsonPoll.Document_info["structureid"] = result.rows[i].intidestructura;
							jsonPoll.Document_info["interviewerid"] = result.rows[i].intidinterviewer;
							jsonPoll.Document_info["projectid"] = result.rows[i].intidproyecto;
							jsonPoll.Document_info["projectname"] = result.rows[i].nombre;
							jsonPoll.Document_info["clientid"] = result.rows[i].intidcliente;
							jsonPolls.push(jsonPoll);
						}
						console.log(JSON.stringify(jsonPolls));
						io.emit("udcPollOutgoing", JSON.stringify(jsonPolls));
					}else{
						io.emit("udcPollOutgoing", JSON.parse("[]"));
					}
				}
			});
	});
    socket.on("loginInterviewer", function(request){
    	if(request == "requestInterviewers"){
    		var query = client.query("SELECT * FROM administracion.splogininterviewer();",function(err,result){
    			if(err){
    				console.log('Error de conexión');
    			}else{
    				if(result.rows.length > 0){
    					var json = JSON.parse("[]");
    					for(var i =0; i< result.rows.length;i++){
    						json.push(result.rows[i]);
    					}
    					socket.emit("loginInterviewer",JSON.stringify(json));
    					console.log(json);
    				}else{
    					console.log('Registro no encontrado');
    				}
    			}
    		});
    	}
    })
    // Guardado en cola RABBITMQ
	socket.on('enqueue', function(msg, callback){
		amqp.connect('amqp://localhost', function(err, conn) {
			conn.createChannel(function(err, ch) {
				if(err){

				}else{
					var q = 'PollQueue';
					ch.assertQueue(q, {durable: true});
					ch.sendToQueue(q, new Buffer(msg), {persistent: true});
					//ch.ack(msg);
					console.log(" [x] Sent '"+msg+"'");
					callback("OK");
				}
			});
		});
	});

	var shema = "analiizo_interventoria";

	socket.on("loginInventoryInterviewer", function(request){
    	if(request == "requestInterviewers"){
    		var result = client.query("SELECT * FROM "+shema+".splogininterviewer();",function(err,result){
    			if(err){
    				console.log('Error de conexión');
    			}else{
    				if(result.rows.length > 0){
    					var json = JSON.parse("[]");
    					for(var i =0; i< result.rows.length;i++){
    						json.push(result.rows[i]);
    					}
    					socket.emit("loginInventoryInterviewer",JSON.stringify(json));
    					console.log(json);
    				}else{
    					console.log('Registro no encontrado');
    					socket.emit("loginInventoryInterviewer",JSON.parse("[]"));
    				}
    			}
    		});
    	}
    })

    socket.on("updateUsers", function(request){
    	if(request == "requestInterviewers"){
    		var result = client.query("SELECT * FROM "+shema+".splogininterviewer();",function(err,result){
    			if(err){
    				console.log('Error de conexión');
    			}else{
    				if(result.rows.length > 0){
    					var json = JSON.parse("[]");
    					for(var i =0; i< result.rows.length;i++){
    						json.push(result.rows[i]);
    					}
    					socket.emit("updateUsers",JSON.stringify(json));
    					console.log(json);
    				}else{
    					console.log('Registro no encontrado');
    					socket.emit("loginInventoryInterviewer",JSON.parse("[]"));
    				}
    			}
    		});
    	}
    });

    socket.on('inventoryProjectsOutgoing', function(interviewerid, callback){
        var query = "select p.intidproyecto,p.strnombre,p.strnombreinter from "+shema+".proyecto as p, "+shema+".encuestadorproyecto as ip where ip.intidinterviewer = "+interviewerid+" and ip.intistatus != 3 and p.intidproyecto = ip.intidproyecto;"
        var result = client.query(query, function(err, result){
            if(err){
                console.log(err);
            }else{
                if(result.rows.length > 0){
                    var jsonProjects = JSON.parse("[]");
                    for(var i = 0; i < result.rows.length; i++){
                        var jsonProject = JSON.parse("{}");
                        jsonProject["id"] = result.rows[i].intidproyecto;
                        jsonProject["name"] = result.rows[i].strnombre;
                        jsonProject["description"] = result.rows[i].strnombreinter;
                        jsonProjects.push(jsonProject);
                    }
                    //console.log(JSON.stringify(jsonProjects));
                    callback(JSON.stringify(jsonProjects));
                }else{
                    console.log("No se encontraron registros");
                    callback(JSON.stringify(JSON.parse("[]")));
                }
            }
        });
    });

    socket.on('invetoryPollOutgoing', function(interviewerid){
    	var query = "";
    	var query = "select distinct ee.intidestructura,ee.intidinterviewer,p.intidproyecto,p.strnombre,p.intidcliente,se.estructura from "+shema+".encuestaencuestador as ee, "+shema+".estructuraencuestas as se,"+shema+".encuestadorproyecto as ep,"+shema+".proyecto as p where ee.intidinterviewer =" + interviewerid + " and ee.intidestructura = se.intidestructura and ee.intidinterviewer = ep.intidinterviewer and ep.intidproyecto = p.intidproyecto and ee.intistatus != 3 AND se.intstatus != 3";
			var result = client.query(query, function(err, result){
				if(err){
					console.log(err);
				}else{
					if(result.rows.length > 0){
						var jsonPolls = JSON.parse("[]");
						for(var i =0; i< result.rows.length;i++){
							var jsonPoll = JSON.parse(JSON.stringify(result.rows[i].estructura));
							jsonPoll.Document_info["structureid"] = result.rows[i].intidestructura;
							jsonPoll.Document_info["interviewerid"] = result.rows[i].intidinterviewer;
							jsonPoll.Document_info["projectid"] = result.rows[i].intidproyecto;
							jsonPoll.Document_info["projectname"] = result.rows[i].strnombre;
							jsonPoll.Document_info["clientid"] = result.rows[i].intidcliente;
							jsonPolls.push(jsonPoll);
						}
						//console.log(JSON.stringify(jsonPolls));
						io.emit("invetoryPollOutgoing", JSON.stringify(jsonPolls));
					}else{
						console.log("no se encontraron registro");
						io.emit("invetoryPollOutgoing", JSON.parse("[]"));
					}
				}
			});
    });

    socket.on('inventoryPollOutgoing2', function(interviewerid,projectid){
        var query = "";
        var query = "select distinct ee.intidestructura,ee.intidinterviewer,p.intidproyecto,p.strnombre,p.intidcliente,se.estructura from "+shema+".encuestaencuestador as ee, "+shema+".estructuraencuestas as se,"+shema+".encuestadorproyecto as ep,"+shema+".proyecto as p where ee.intidinterviewer =" + interviewerid + " and p.intidproyecto = "+projectid+" and ee.intidestructura = se.intidestructura and ee.intidinterviewer = ep.intidinterviewer and ep.intidproyecto = p.intidproyecto and ee.intistatus != 3 AND se.intstatus != 3";
            var result = client.query(query, function(err, result){
                if(err){
                    console.log(err);
                }else{
                    if(result.rows.length > 0){
                        var jsonPolls = JSON.parse("[]");
                        for(var i =0; i< result.rows.length;i++){
                            var jsonPoll = JSON.parse(JSON.stringify(result.rows[i].estructura));
                            jsonPoll.Document_info["structureid"] = result.rows[i].intidestructura;
                            jsonPoll.Document_info["interviewerid"] = result.rows[i].intidinterviewer;
                            jsonPoll.Document_info["projectid"] = result.rows[i].intidproyecto;
                            jsonPoll.Document_info["projectname"] = result.rows[i].strnombre;
                            jsonPoll.Document_info["clientid"] = result.rows[i].intidcliente;
                            jsonPolls.push(jsonPoll);
                        }
                        //console.log(JSON.stringify(jsonPolls));
                        io.emit("inventoryPollOutgoing2", JSON.stringify(jsonPolls));
                    }else{
                        console.log("no se encontraron registro");
                        io.emit("inventoryPollOutgoing2", JSON.parse("[]"));
                    }
                }
            });
    });

    socket.on('invetoryEnqueue', function(msg, callback){
        var conString = "postgres://postgres:Miido2015@localhost:5432/postgres";
      var jsonPoll = JSON.parse(msg);
      var query = "INSERT INTO analiizo_interventoria.poll (pollcontent,idpollstructure) VALUES ('"+JSON.stringify(jsonPoll)+"',"+jsonPoll.DOCUMENTINFO['structureid']+");";

      var result = client.query(query, function(err, resul){
        if(err){
            console.log(err);
        }else{
            //console.log(resul);
            callback("OK");
        }
      });// client.query

    	/*amqp.connect('amqp://localhost', function(err, conn) {
			conn.createChannel(function(err, ch) {
				if(err){
                    console.log(err);
				}else{
					var q = 'invetoryQueue';
					ch.assertQueue(q, {durable: true});
					ch.sendToQueue(q, new Buffer(msg), {persistent: true});
					//ch.ack(msg);
					console.log(" [x] Sent '"+msg+"'");
					callback("OK");
				}
			});
		});*/
    });

    socket.on('autocompleteService', function(msg, callback){
        var util = require('util');
        var query = "SELECT aui.intid,aui.strname "+
            "FROM administracion.autocomplete_services AS aus, administracion.autocomplete_items AS aui "+
            "WHERE aus.strname = '%s' AND aus.intid = aui.intidservice;";
        client.query(util.format(query,msg), function(err, result){
            if(err){
                console.log(err);
            }else{
                if(result.rows.length > 0){
                    var jsonArray = JSON.parse("[]");
                    for(var i = 0; i < result.rows.length; i++){
                        var jsonObject = JSON.parse("{}");
                        jsonObject["id"] = result.rows[i].intid;
                        jsonObject["item"] = result.rows[i].strname;
                        jsonArray.push(jsonObject);
                    }
                    callback(JSON.stringify(jsonArray));
                    console.log(JSON.stringify(jsonArray));
                }else{
                    callback(JSON.stringify(JSON.parse("[]")));
                    console.log("No hay registros");
                }
            }
        });
    });

    socket.on('insumos', function(token, callback){
    	var table = "insumos";
    	var query = "select * from "+shema+"."+table;
    	var result = client.query(query, function(err, result){
    		if(err){
    			console.log(err);
    		}else{
    			if(result.rows.length > 0){
    				var jsupplies = JSON.parse("[]");
    				for(var i = 0; i < result.rows.length; i++){
    					var jsupplie = JSON.parse("{}");
    					jsupplie["intidinsumo"] = result.rows[i].intidinsumo;
    					jsupplie["strnombreinsumo"] = result.rows[i].strnombreinsumo;
    					jsupplies.push(jsupplie);
    				}
    				var ssupplies = JSON.stringify(jsupplies);
    				//console.log(ssupplies);
    				callback(ssupplies);
    			}else{
    				callback(JSON.stringify(JSON.parse("[]")));
    			}
    		}
    	});
    });

    socket.on('equipos', function(token, callback){
    	var table = "equipos";
    	var query = "select * from "+shema+"."+table;
    	var result = client.query(query, function(err, result){
    		if(err){
    			console.log(err);
    		}else{
    			if(result.rows.length > 0){
    				var jequipments = JSON.parse("[]");
    				for(var i = 0; i < result.rows.length; i++){
    					var jequipment = JSON.parse("{}");
    					jequipment["intidequipo"] = result.rows[i].intidequipo;
    					jequipment["strnombreequipo"] = result.rows[i].strnombreequipo;
    					jequipments.push(jequipment);
    				}
    				var sequipments = JSON.stringify(jequipments);
    				//console.log(sequipments);
    				callback(sequipments);
    			}else{
    				callback(JSON.stringify(JSON.parse("[]")));
    			}
    		}
    	});
    });

    socket.on('muestraensayo', function(token, callback){
    	var table = "muestraensayo";
    	var query = "select * from "+shema+"."+table;
    	var result = client.query(query, function(err, result){
    		if(err){
    			console.log(err);
    		}else{
    			if(result.rows.length > 0){
    				var jtests = JSON.parse("[]");
    				for(var i = 0; i < result.rows.length; i++){
    					var jtest = JSON.parse("{}");
    					jtest["intidmuestra"] = result.rows[i].intidmuestra;
    					jtest["strnombremuestra"] = result.rows[i].strnombremuestra;
    					jtests.push(jtest);
    				}
    				var stests = JSON.stringify(jtests);
    				//console.log(stests);
    				callback(stests);
    			}else{
    				callback(JSON.stringify(JSON.parse("[]")));
    			}
    		}
    	});

    });

    socket.on('observacionparticular', function(token, callback){
    	var table = "observacionparticular";
    	var query = "select * from "+shema+"."+table;
    	var result = client.query(query, function(err, result){
    		if(err){
    			console.log(err);
    		}else{
    			if(result.rows.length > 0){
    				var jobservations = JSON.parse("[]");
    				for(var i = 0; i < result.rows.length; i++){
    					var jobservation = JSON.parse("{}");
    					jobservation["intidobservacion"] = result.rows[i].intidobservacion;
    					jobservation["strdescricpion"] = result.rows[i].strdescricpion;
    					jobservations.push(jobservation);
    				}
    				var sobservations = JSON.stringify(jobservations);
    				//console.log(sobservations);
    				callback(sobservations);
    			}else{
    				callback(JSON.stringify(JSON.parse("[]")));
    			}
    		}
    	});
    });

    /*Servicio para autocompletar los items porgramados y/o ejecutados*/
    socket.on('itemproeje', function(token, callback){
    	var table = "itemproeje";
    	var query = "select * from "+shema+"."+table;
    	var result = client.query(query, function(err, result){
    		if(err){
    			console.log(err);
    		}else{
    			if(result.rows.length > 0){
    				var items = JSON.parse("[]");
    				for(var i = 0; i < result.rows.length; i++){
    					var item = JSON.parse("{}");
    					item["intiditem"] = result.rows[i].intiditem;
    					item["stritem"] = result.rows[i].stritem;
    					items.push(item);
    				}
    				var sobitems = JSON.stringify(items);
    				//console.log(sobitems);
    				callback(sobitems);
    			}else{
    				callback(JSON.stringify(JSON.parse("[]")));
    			}
    		}
    	});
    });

    /*Servicio para autocompletar las actividades de cada proyecto*/
    socket.on('actividadproyecto', function(token, callback){
        var table = "actividadproyecto";
        var query = "select * from "+shema+"."+table+" where intidproyecto = 2";
        var result = client.query(query, function(err, result){
            if(err){
                console.log(err);
            }else{
                if(result.rows.length > 0){
                    var activities = JSON.parse("[]");
                    for(var i = 0; i < result.rows.length; i++){
                        var activity = JSON.parse("{}");
                        activity["intidactividad"] = result.rows[i].intidactividad;
                        activity["strdescripcion"] = result.rows[i].strdescripcion;
                        activities.push(activity);
                    }
                    var sobactivities = JSON.stringify(activities);
                    //console.log(sobitems);
                    callback(sobactivities);
                }else{
                    callback(JSON.stringify(JSON.parse("[]")));
                }
            }
        });
    });


    /****************** ANALIIZO SALUD *************************/
    var shema = "analiizo_salud";

    socket.on("LOGIN", function(request){
        if(request == "requestInterviewers"){
            var result = client.query("SELECT * FROM "+shema+".splogininterviewer();",function(err,result){
                if(err){
                    console.log('Error de conexión');
                }else{
                    if(result.rows.length > 0){
                        var json = JSON.parse("[]");
                        for(var i =0; i< result.rows.length;i++){
                            json.push(result.rows[i]);
                        }
                        console.log("<LOGIN> "+JSON.stringify(json));
                        socket.emit("LOGIN",JSON.stringify(json));
                    }else{
                        console.log("<LOGIN> "+'Registro no encontrado');
                        socket.emit("LOGIN",JSON.parse("[]"));
                    }
                }
            });
        }
    })

    socket.on("UPDATEUSERS", function(request){
        if(request == "requestInterviewers"){
            var result = client.query("SELECT * FROM "+shema+".splogininterviewer();",function(err,result){
                if(err){
                    console.log('Error de conexión');
                }else{
                    if(result.rows.length > 0){
                        var json = JSON.parse("[]");
                        for(var i =0; i< result.rows.length;i++){
                            json.push(result.rows[i]);
                        }
                        console.log("<LOGIN> "+JSON.stringify(json));
                        socket.emit("UPDATEUSERS",JSON.stringify(json));

                    }else{
                        console.log("<LOGIN> "+'Registro no encontrado');
                        socket.emit("UPDATEUSERS",JSON.parse("[]"));
                    }
                }
            });
        }
    });

    socket.on('PROJECTS', function(interviewerid, callback){
        var query = "select p.intidproyecto,p.nombre,p.dtcreatedate from "+shema+".proyecto as p, "+shema+".encuestadorproyecto as ip where ip.intidinterviewer = "+interviewerid+" and ip.intistatus != 3 and p.intidproyecto = ip.intidproyecto;"
        var result = client.query(query, function(err, result){
            if(err){
                console.log(err);
            }else{
                if(result.rows.length > 0){
                    var jsonProjects = JSON.parse("[]");
                    for(var i = 0; i < result.rows.length; i++){
                        var jsonProject = JSON.parse("{}");
                        jsonProject["id"] = result.rows[i].intidproyecto;
                        jsonProject["name"] = result.rows[i].nombre;
                        jsonProject["description"] = result.rows[i].dtcreatedate;
                        jsonProjects.push(jsonProject);
                    }
                    console.log(JSON.stringify(jsonProjects));
                    callback(JSON.stringify(jsonProjects));
                }else{
                    console.log("No se encontraron registros");
                    callback(JSON.stringify(JSON.parse("[]")));
                }
            }
        });
    });


    socket.on('POLL', function(interviewerid,projectid){
        var query = "";
        var query = "select distinct ee.intidestructura,ee.intidinterviewer,p.intidproyecto,p.nombre,p.intidcliente,se.estructura,se.nombre AS structurename from "+shema+".encuestaencuestador as ee, "+shema+".estructuraencuestas as se,"+shema+".encuestadorproyecto as ep,"+shema+".proyecto as p where ee.intidinterviewer =" + interviewerid + " and p.intidproyecto = "+projectid+" and ee.intidestructura = se.intidestructura and ee.intidinterviewer = ep.intidinterviewer and ep.intidproyecto = p.intidproyecto and ee.intistatus != 3 AND se.intstatus != 3";
            var result = client.query(query, function(err, result){
                if(err){
                    console.log(err);
                }else{
                    if(result.rows.length > 0){
                        var jsonPolls = JSON.parse("[]");
                        for(var i =0; i< result.rows.length;i++){
                            var jsonPoll = JSON.parse(JSON.stringify(result.rows[i].estructura));
                            jsonPoll.Document_info["structureid"] = result.rows[i].intidestructura;
                            jsonPoll.Document_info["interviewerid"] = result.rows[i].intidinterviewer;
                            jsonPoll.Document_info["projectid"] = result.rows[i].intidproyecto;
                            jsonPoll.Document_info["projectname"] = result.rows[i].nombre;
                            jsonPoll.Document_info["clientid"] = result.rows[i].intidcliente;
                            jsonPoll.Document_info["structurename"] = result.rows[i].structurename;
                            jsonPolls.push(jsonPoll);
                        }
                        console.log(JSON.stringify(jsonPolls));
                        io.emit("POLL", JSON.stringify(jsonPolls));
                    }else{
                        console.log("<inventoryPollOutgoing2> no se encontraron registro");
                        io.emit("POLL", JSON.parse("[]"));
                    }
                }
            });
    });

    socket.on('POLLENQUEUE', function(msg, callback){
      var jsonPoll = JSON.parse(msg);
      //var query = "INSERT INTO analiizo_interventoria.poll (pollcontent,idpollstructure) VALUES ('"+JSON.stringify(jsonPoll)+"',"+jsonPoll.DOCUMENTINFO['structureid']+");";
      var query = "INSERT INTO analiizo_salud.poll (pollcontent,idpollstructure) VALUES ('"+JSON.stringify(jsonPoll)+"', 1);";
      var result = client.query(query, function(err, resul){
        if(err){
            console.log(err);
        }else{
            console.log(resul);
            callback("OK");
        }
      });// client.query

        /*amqp.connect('amqp://localhost', function(err, conn) {
            conn.createChannel(function(err, ch) {
                if(err){
                    console.log(err);
                }else{
                    var q = 'invetoryQueue';
                    ch.assertQueue(q, {durable: true});
                    ch.sendToQueue(q, new Buffer(msg), {persistent: true});
                    //ch.ack(msg);
                    console.log(" [x] Sent '"+msg+"'");
                    callback("OK");
                }
            });
        });*/
    });

    socket.on('SERVICES', function(msg, callback){
        var util = require('util');
        var query = "SELECT aui.intid,aui.strname "+
            "FROM analiizo_salud.autocomplete_services AS aus, analiizo_salud.autocomplete_items AS aui "+
            "WHERE aus.strname = '%s' AND aus.intid = aui.intidservice;";
        client.query(util.format(query,msg), function(err, result){
            if(err){
                console.log(err);
            }else{
                if(result.rows.length > 0){
                    var jsonArray = JSON.parse("[]");
                    for(var i = 0; i < result.rows.length; i++){
                        var jsonObject = JSON.parse("{}");
                        jsonObject["id"] = result.rows[i].intid;
                        jsonObject["item"] = result.rows[i].strname;
                        jsonArray.push(jsonObject);
                    }
                    callback(JSON.stringify(jsonArray));
                    console.log(JSON.stringify(jsonArray));
                }else{
                    callback(JSON.stringify(JSON.parse("[]")));
                    var msgToClient = "<autocompleteService> <%s> No hay registros";
                    console.log(util.format(msgToClient,msg));
                }
            }
        });
    });

});
