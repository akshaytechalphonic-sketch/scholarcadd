<script src="{{ URL::asset('amybuild/libs/html2canvas.min.js') }}"></script>
<script src="{{ URL::asset('amybuild/libs/jspdf.umd.min.js') }}"></script>

<script>
    function printSection() {
        window.print();
    }
</script>
<script>
    var source = $('#source').val();
    async function downloadPDF() {
        const {
            jsPDF
        } = window.jspdf;
        const report = document.getElementById("report");
        const canvas = await html2canvas(report, {
            scale: 2,
            useCORS: true,
            scrollY: -window.scrollY,
            backgroundColor: "#ffffff"
        });
        const imgData = canvas.toDataURL("image/jpeg", 1.0);
        const pdf = new jsPDF("p", "mm", "a4");
        pdf.setProperties({
            title: "Bidmate " + source,
        });
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        const margin = 10;
        const usableWidth = pageWidth - margin * 2;
        const usableHeight = pageHeight - margin * 2;
        const imgWidth = usableWidth;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        const canvasPageHeight = (usableHeight * canvas.height) / imgHeight;
        const pageCount = Math.ceil(canvas.height / canvasPageHeight);
        for (let i = 0; i < pageCount; i++) {
            const pageCanvas = document.createElement("canvas");
            pageCanvas.width = canvas.width;
            pageCanvas.height = canvasPageHeight;
            const ctx = pageCanvas.getContext("2d");
            ctx.fillStyle = "#ffffff";
            ctx.fillRect(0, 0, pageCanvas.width, pageCanvas.height);
            ctx.drawImage(
                canvas,
                0,
                i * canvasPageHeight,
                canvas.width,
                canvasPageHeight,
                0,
                0,
                canvas.width,
                canvasPageHeight
            );
            const pageImgData = pageCanvas.toDataURL("image/jpeg", 1.0);
            if (i > 0) pdf.addPage();
            pdf.addImage(pageImgData, "JPEG", margin, margin, imgWidth, usableHeight);
        }
        pdf.save("Bidmate " + source + ".pdf");
    }
</script>