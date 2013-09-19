var confirmEnabled = true;
var useHighlighting = true;
var strToggleColor = '#f0fff2';
var strRollColor = '#fffff0';
var strRowOneColor = '#ffffff';
var strRowTwoColor = '#e7ecf8';
var from = 0;
var to = 0;
var pressed =0;
var hold = '';

function confirmMessage(ctlATag, strMessage) {
    // Confirmation is not required or browser is Opera:
    if (!confirmEnabled || typeof(window.opera) != 'undefined') {
        ctlATag.href += '&confirmed=1';
        return true;
    }
    // Everyting looks like we could ask using js:
    var bConfirmed = confirm(strMessage);
    if (bConfirmed)
        ctlATag.href += '&confirmed=1';
    return bConfirmed;
} // end confirmMessage()' function

function isAllChecked(ctlForm) {
    for (var i=0; i < ctlForm.elements.length; i++) {
        var ctl = ctlForm.elements[i];
        if (ctl.id.indexOf("cb_") == 0 && !ctl.checked)
            return false;
    }
    return true;
}

function getTRFromCB(ctlCB) {
    var ctlTR = null;
    if (ctlCB.parentNode && ctlCB.parentNode.parentNode)
        ctlTR = ctlCB.parentNode.parentNode;
    else if (ctlCB.parentElement && ctlCB.parentElement.parentElement)
        ctlTR = ctlCB.parentElement.parentElement;
    return ctlTR;
}

// <!> <body ... onload="restoreSelection();">
function restoreSelection() {
    for (var i=0; i < document.forms.length; i++) {
        var ctlForm = document.forms[i];
        if (ctlForm.className == 'iactive' && ctlForm.toggleAll) {
            for (var j=0; j < ctlForm.elements.length; j++) {
                var ctl = ctlForm.elements[j];
                if (ctl.id.indexOf("cb_") == 0 && ctl.checked)
                    toggleCB(ctl);
            }
            ctlForm.toggleAll.checked = isAllChecked(ctlForm);
        }
    }
}


function toggleCBs(ctlToggleAllCB) {
    var ctlForm = ctlToggleAllCB.form;
    var ctlTR = null;
    for (var i = 0; i < ctlForm.elements.length; i++) {
        var ctl = ctlForm.elements[i];
        if (ctl.id.indexOf("cb_") == 0 && ctl.checked != ctlToggleAllCB.checked) {
            ctl.checked = ctlToggleAllCB.checked;
            ctlTR = getTRFromCB(ctl);
            if (ctlTR)
                changeAppearance(ctlTR, ctl.checked, 0, strToggleColor);
        }
    }
}

function toggleCB(ctlCB) {
    var ctlForm = ctlCB.form;
    var ctlTR = null;
    if (ctlForm && ctlForm.toggleAll)
        if (ctlCB.checked)
            ctlForm.toggleAll.checked = isAllChecked(ctlForm);
        else ctlForm.toggleAll.checked = false;
    ctlTR = getTRFromCB(ctlCB);
    if (ctlTR)
        changeAppearance(ctlTR, ctlCB.checked, 0, strToggleColor);
}

function toggleCB_name(id,holder) {
    hold = holder;
    if(pressed==0){
        from = holder.parent().children().index(holder);
        $('#cb_'+id).jsCBtoggle(0);
    } else {
        if(pressed==1){
            to = holder.parent().children().index(holder);
            holder.parent().children().each(function(){
                holder = hold;
                index = holder.parent().children().index($(this));
                if (from < to) {
                    if(index>=from && index<=to){
                        $(this).children('#row1').children('input').jsCBtoggle(1);
                    }
                }else{
                    if(index>=to && index<=from){
                        $(this).children('#row1').children('input').jsCBtoggle(1);
                    }
                }
            });
        }
    }
}

(function($){
    $.fn.jsCBtoggle = function(o){
        var obj = $(this);
        if(!obj.attr("checked") || o == 1)
        {
            if (useHighlighting) {
                obj.parent().parent().css('background-color', strToggleColor);
            }
            return obj.attr("checked",'true');
        }else{
            if (useHighlighting) {
                obj.parent().parent().css('background-color', strRowTwoColor);
            }
            return obj.removeAttr("checked");
        }
    };    
})(jQuery);


// <!> If checked using checkbox, it causes automatic uncecking (both are triggered - toggleCB and toggleTR: unchecked -> checked -> unchecked)
function toggleTR(strID) {
    var ctlCB = document.getElementById('cb_' + strID);
    if (ctlCB) {
        ctlCB.checked = !ctlCB.checked;
        toggleCB(ctlCB);
    }
}

function rollTR(strID, nState) {
    var ctlCB = document.getElementById('cb_' + strID);
    var ctlTR = document.getElementById('tr_' + strID);
    if ((!ctlCB || !ctlCB.checked) && ctlTR)
        changeAppearance(ctlTR, false, nState, strRollColor);
}

function changeAppearance(ctlTR, bIsRowChecked, nState, strOnColor) {
    if (useHighlighting) {
        if (ctlTR && ctlTR.className == "rowone")
            ctlTR.style.backgroundColor = bIsRowChecked || (nState == 1) ? strOnColor : strRowOneColor;
        else if (ctlTR && ctlTR.className == "rowtwo")
            ctlTR.style.backgroundColor = bIsRowChecked || (nState == 1) ? strOnColor : strRowTwoColor;
    }
}

function toggleSection(strID) {
    var ctlDIV = document.getElementById(strID);
    document.cookie = strID + "=" + (ctlDIV.style.display != 'none' ? 'N' : 'Y') + "; expires=Thu, 31 Dec 2035 23:59:59 GMT; path=/;";
    ctlDIV.style.display = (ctlDIV.style.display != 'none' ? 'none' : 'block');
}

function showDIV(strID, bShow, bSetCookie) {
    var ctlDIV = document.getElementById(strID);
    if(bSetCookie)
        document.cookie = strID + "=" + (bShow ? 'N' : 'Y') + "; expires=Thu, 31 Dec 2035 23:59:59 GMT; path=/;";
    ctlDIV.style.display = (bShow ? 'block' : 'none');
}

function showDialog(URL, width, height) {
    id = window.open(URL, "", "location=no,statusbar=yes,status=yes,scrollbars=yes,menubar=no,toolbar=no,directories=no,resizable=yes,width=" + width + ",height=" + height);
}

function changeChoicePercents(ctlCB, strNo) {
    var count = $('#answer_correct:checked').length;
    //var ctlInput = document.getElementsByName('answer_percents[' + strNo + ']')[0];
    var perc = parseInt(100/count); 
    /*if(ctlCB.checked){
    ctlInput.value = '100';
    }
    else ctlInput.value = '0';*/
    $('#answer_correct:checked').parent().children('#answer_perc').each(function(index){
        $(this).val(perc);
    });
    $('#answer_correct:not(:checked)').parent().children('#answer_perc').each(function(index){
        $(this).val(0);
    });
}

function writeTag(strTag) {
    document.etemplateForm.etemplate_body.value += strTag;
}

function ShowInfoBar(bShow)
{
    document.cookie = "showinfobar=" + (bShow ? "Y" : "N") + "; expires=Thu, 31 Dec 2035 23:59:59 GMT; path=/;";
    ctlInfoBar = document.getElementById("infobar");
    if(ctlInfoBar)
        ctlInfoBar.style.display = (bShow ? "block":"none");
}