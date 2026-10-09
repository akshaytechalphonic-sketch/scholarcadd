<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('printButton').addEventListener('click', function() {
            printSection('print-report');
        });
        function printSection(sectionId) {
            var printContents = document.getElementById(sectionId).innerHTML;
            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);
            var iframeDoc = iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write('<html><head><title>Quotation Report | Bidmate - Powered By Amysoftech</title>');
            var styles = document.querySelectorAll('link[rel="stylesheet"], style');
            styles.forEach(function(style) {
                iframeDoc.write(style.outerHTML);
            });
            iframeDoc.write('</head><body>');
            iframeDoc.write(printContents);
            iframeDoc.write('</body></html>');
            iframeDoc.close();
            iframe.contentWindow.onload = function() {
                iframe.contentWindow.print();
                document.body.removeChild(iframe);
            };
        }
    });
</script>