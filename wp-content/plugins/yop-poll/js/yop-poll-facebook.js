jQuery( '<div></div>', {
    id: 'fb-root'
} ).appendTo( jQuery( 'body' ) );

window.fbAsyncInit = function () {
    FB.init( {
        appId: yop_poll_fb_appID,
        status: true,
        xfbml: true
    } );
};

(function ( d, s, id ) {
    var js, fjs = d.getElementsByTagName( s )[0];
    if ( d.getElementById( id ) ) {return;}
    js = d.createElement( s );
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js";
    fjs.parentNode.insertBefore( js, fjs );
}( document, 'script', 'facebook-jssdk' ));