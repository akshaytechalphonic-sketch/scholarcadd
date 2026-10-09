<script>
     function validateFile(v, types, sizes) {
        var allowedExtensions = null;
        if (types == "image")
            allowedExtensions = ['jpeg', 'png','jpg'];
        else if (types == "document")
            allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'xls'];
        else if (types == "image_document")
            allowedExtensions = ['jpeg', 'png', 'pdf', 'doc', 'docx', 'xlsx', 'xls'];
        const sizeLimit = (1000000 * sizes);

        var error_message = "Please upload only " + allowedExtensions + " files & Size should be " + sizes + "MB"
        var size_message = "File size must be less than " + sizes + "MB"

        for (i = 0; i <= 10; i++) {
            if (v.files[i]) {
                const {
                    name: fileName,
                    size: fileSize
                } = v.files[i];
                const fileExtension = fileName.split(".").pop();
                if (!allowedExtensions.includes(fileExtension)) {
                    toastr.error(error_message);                   
                    v.value = null;
                } else if (fileSize > sizeLimit) {
                    toastr.error(size_message);                    
                    v.value = null;
                }

            } else
                break;

        }
    }
    </script>