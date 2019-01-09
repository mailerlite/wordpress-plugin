(function () {
    tinymce.create('tinymce.plugins.mailerlite_shortcode', {
        init: function (ed, url) {
            ed.addCommand('mailerlite_shortcode_popup', function () {
                    ed.windowManager.open({
                            file: ajaxurl + '?action=mailerlite_tinymce_window',
                            width: 400 + ed.getLang('example.delta_width', 0),
                            height: 400 + ed.getLang('example.delta_height', 0),
                            inline: 1
                        }, {
                            plugin_url: url
                        }
                    );
                }
            );
            ed.addButton('mailerlite_shortcode', {
                    title: 'Add a MailerLite sign-up form',
                    image: url + '/../image/widget_logo.svg',
                    cmd: 'mailerlite_shortcode_popup'
                }
            );
        },
        createControl: function (n, ml) {
            return null;
        }
    });
    tinymce.PluginManager.add('mailerlite_shortcode', tinymce.plugins.mailerlite_shortcode);
})();
