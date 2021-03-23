function mlResizeIframe(iframe) {
    setTimeout(function() {
        iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
    }, 1000);
}