var last = 0;
var group;
var users = [];
var user_max = 0;
var tmp = [];
var timer = 2000;
var type = 1;
var sys_id = 0;

function rtime_init(id){
    sys_id = id;
    var url = "/ajax.php?group="+id+"&action=init";
    if(type==2){
        url = "/ajax.php?test="+id+"&action=init";
    }
    if(type==3){
        url = "/ajax.php?subjectid="+id+"&action=init";
    }
    if(type==4){
        url = "/ajax.php?all=all&action=init";
    }
    
    $.getJSON(url,
        function(html){
            if(html!=null){
                $.each(html, function(key, val) {
                    addUsers(val.resultid,val.yt_questionno,val.yt_got_points);
                    if(val.resultid>user_max){
                        user_max = val.resultid;
                    }
                    var perc = parseInt((val.yt_questionno-1)/(val.yt_questioncount/100));
                    var time = 100;
                    if(val.yt_testtime != 0){
                        time = (val.now_time-val.yt_teststart)/(val.yt_testtime/100);
                    }
                    var point = genPoint(val.yt_pointsmax, val.yt_got_points, val.yt_points);
                    var fio = '<b>' + val.lastname + ' ' + val.firstname + '</b>';
                    if(type > 1){
                        fio = val.usergroup_txt + ' » <b>' + val.lastname + ' ' + val.firstname + '</b>';
                    }
                    addRow(val.resultid, fio, val.yt_name, (val.yt_questionno-1) + ' з ' + val.yt_questioncount + ' ('+perc+'%)', time, point);
                });
            }
        }
        );
    setTimeout("rtime_timer()",timer);
}

function addUsers(id,one,two){
    users[users.length] = [id,one,two, 0, users.length];
    return users.length;
}

function rtime_timer(){
    rtime_update_users();
    rtime_update();
    setTimeout("rtime_timer()",timer);
}

function rtime_update_users(){
    var url = "/ajax.php?max="+user_max+"&group="+sys_id+"&action=uusers";
    if(type==2){
        url = "/ajax.php?max="+user_max+"&test="+sys_id+"&action=uusers";
    }
    if(type==3){
        url = "/ajax.php?max="+user_max+"&subjectid="+sys_id+"&action=uusers";
    }
    if(type==4){
        url = "/ajax.php?max="+user_max+"&all=all&action=uusers";
    }
    $.getJSON(url,
        function(html){
            if(html!=null){
                $.each(html, function(key, val) {
                    addUsers(val.resultid,val.yt_questionno,val.yt_got_points);
                    if(val.resultid>user_max){
                        user_max = val.resultid;
                    }
                    var perc =  parseInt((val.yt_questionno-1)/(val.yt_questioncount/100));
                    var time = 100;
                    if(val.yt_testtime != 0){
                        time = (val.now_time-val.yt_teststart)/(val.yt_testtime/100);
                    }
                    var point = genPoint(val.yt_pointsmax, val.yt_got_points, val.yt_points);
                    var fio = '<b>' + val.lastname + ' ' + val.firstname + '</b>';
                    if(type > 1){
                        fio = val.usergroup_txt + ' » <b>' + val.lastname + ' ' + val.firstname + '</b>';
                    }
                    addRow(val.resultid, fio, val.yt_name, (val.yt_questionno-1) + ' з ' + val.yt_questioncount + ' ('+perc+'%)', time, point);
                });
            }
        }
        );
}


function getGlobal(id){
    return tmp[id];
}

function rtime_update(){
    $.each(users, function(u_key, u_val) {
        if(users[u_val[4]][3] != 1){
            $.getJSON("/ajax.php?user="+u_val[0]+"&action=update",
                function(val){
                    var id = u_val[4];
                    var user = u_val;
                
                    if(val.yt_questionno>user[1]){
                        var perc =  parseInt((val.yt_questionno-1)/(val.yt_questioncount/100));
                        updateQuestions(val.resultid, (val.yt_questionno-1) + ' з ' + val.yt_questioncount + ' ('+perc+'%)');
                        var time = 100;
                        if(val.yt_testtime != 0){
                            time = (val.now_time-val.yt_teststart)/(val.yt_testtime/100);
                        }
                        updateTime(val.resultid, time);
                        var point = genPoint(val.yt_pointsmax, val.yt_got_points, val.yt_points);
                        if(val.yt_state==6){
                            $('#result_'+val.resultid+' .td_point').html(point);
                            setReady(val.resultid);
                            users[id][3] = 1;
                        } else {
                            updatePoint(val.resultid, point, genPoint(val.yt_pointsmax, user[2], val.yt_points));
                        }
                        users[id][1] = val.yt_questionno;
                        users[id][2] = val.yt_got_points;
                    }
                    if(val.yt_testtime != 0 && val.yt_state<6){
                        time = (val.now_time-val.yt_teststart)/(val.yt_testtime/100);
                        updateTime(val.resultid, time);
                    }
         
                }
                );
        }
    });
}
function genPoint(max_balls, now_balls, points){
    tmp['perc'] = now_balls/(max_balls/100);
    $.each(points, function(key, val) {
        if((tmp['perc'] > val.from && tmp['perc'] < val.to) || tmp['perc'] == val.from || (tmp['perc']==100 && val.to==100)){
            tmp['out'] = val.name;
        }
    });
    return tmp['out'];
}

function addRow(id, fio, test, questions, time, point){
    if(time == 100){
        var html = '<tr class="row" id="result_'+id+'"><td>'+fio+'</td><td>'+test+'</td><td class="td_questions">'+questions+'</td><td class="td_time"><div style="height: 20px; background: #00CC00; width:'+time+'%; text-align: center; color:#fff">Не обмежено</div></td><td class="td_point">'+point+'</td></tr>';
    } else {
        var html = '<tr class="row" id="result_'+id+'"><td>'+fio+'</td><td>'+test+'</td><td class="td_questions">'+questions+'</td><td class="td_time"><div style="height: 20px; background: #00CC00; width:'+time+'%;"></div></td><td class="td_point">'+point+'</td></tr>';
    }
    $('#realtime tr:first').after(html);
    $('#result_'+id).animate({
        backgroundColor: "#00CC00"
    }, 1000);
    $('#result_'+id).animate({
        backgroundColor: "#ececec"
    }, 1000);
    
}
function updateQuestions(id, text){
    $('#result_'+id+' .td_questions').html(text);
}
function updateTime(id, now){
    if(now<=100)
    $('#result_'+id+' .td_time div').animate({
        width: now+"%"
    }, 1000 );
}
function updatePoint(id, new_p, old_p){
    $('#result_'+id+' .td_point').html(new_p);
    if(new_p>old_p){
        $('#result_'+id+' .td_point').animate({
            backgroundColor: "#00CC00"
        }, 1000);
        $('#result_'+id+' .td_point').animate({
            backgroundColor: "#ececec"
        }, 1000);
    } else {
        $('#result_'+id+' .td_point').animate({
            backgroundColor: "#CC0000"
        }, 1000);
        $('#result_'+id+' .td_point').animate({
            backgroundColor: "#ececec"
        }, 1000);
    }
}
function setReady(id){
    updateTime(id, 100);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#A8DCE3"
    }, 500);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#ececec"
    }, 500);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#A8DCE3"
    }, 500);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#ececec"
    }, 500);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#A8DCE3"
    }, 500);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#ececec"
    }, 500);
    $('#result_'+id+', #result_'+id+' .td_point').animate({
        backgroundColor: "#A8DCE3"
    }, 500);
    
}
