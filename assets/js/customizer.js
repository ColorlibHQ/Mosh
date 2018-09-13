(function( wp, $ ){

    if ( ! wp || ! wp.customize ) { return; }

    var api = wp.customize;

    api.section( 'mosh_fof_options_section', function( section ) {
        section.expanded.bind( function( isExpanding ) {
            var newURL = MoshCustomizer.site_url + '/ihopethisisa404page';
            if ( isExpanding ) {
                MoshCustomizer.prev_url = api.previewer.previewUrl.get();
                wp.customize.previewer.previewUrl.set( newURL );
            }else{
                wp.customize.previewer.previewUrl.set( MoshCustomizer.site_url );
            }

        } );
    } );

    api.section( 'mosh_blog_options_section', function( section ) {
        section.expanded.bind( function( isExpanding ) {
            if ( isExpanding ) {
                MoshCustomizer.prev_url = api.previewer.previewUrl.get();
                wp.customize.previewer.previewUrl.set( MoshCustomizer.blog );
            }else{
                wp.customize.previewer.previewUrl.set( MoshCustomizer.site_url );
            }

        } );
    } );

})( window.wp, jQuery );