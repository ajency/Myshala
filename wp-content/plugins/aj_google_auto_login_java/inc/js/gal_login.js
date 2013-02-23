var jq = jQuery;

function galValidateToken(token) {
	jq.ajax({
        url: VALIDURL + token,
        data: null,
        success: function(responseText){  
        	galGetUserInfo();
        },  
        dataType: "jsonp"  
    });
}

function galGetUserInfo() {
	jq.ajax({
        url: 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' + acToken,
        data: null,
        success: function(resp) {
            user    =   resp;
            galRegisterLoginUser(user);
        },
        dataType: "jsonp"
    });
}

function galRegisterLoginUser(user)
{
	var d = {
		
			action		:'gal_register_login_user',
			'_wpnonce'	:jq('#galnonce').val(),
			'token'		:acToken,
			'user'		:user,
	};
	jq.post(gal_ajax.ajaxurl,d,function(r){
		
		//console.log(r);
		if(r.success === true)
			window.location.href = WPSITEURL;
		else
			alert(r.msg);
	});
}
 //credits: http://www.netlobo.com/url_query_string_javascript.html
 function gup(url, name) {
            name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
            var regexS = "[\\#&]"+name+"=([^&#]*)";
            var regex = new RegExp( regexS );
            var results = regex.exec( url );
            if( results == null )
                return "";
            else
                return results[1];
        }
 
  function startLogoutPolling() {
            jq('#loginText').show();
            jq('#logoutText').hide();
            loggedIn = false;
            jq('#uName').text('Welcome ');
            jq('#imgHolder').attr('src', 'none.jpg');
        }

  
  
//parseUri 1.2.2
//(c) Steven Levithan <stevenlevithan.com>
//MIT License

function parseUri (str) {
	var	o   = parseUri.options,
		m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
		uri = {},
		i   = 14;

	while (i--) uri[o.key[i]] = m[i] || "";

	uri[o.q.name] = {};
	uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
		if ($1) uri[o.q.name][$1] = $2;
	});

	return uri;
};

parseUri.options = {
	strictMode: false,
	key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
	q:   {
		name:   "queryKey",
		parser: /(?:^|&)([^&=]*)=?([^&]*)/g
	},
	parser: {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
	}
};