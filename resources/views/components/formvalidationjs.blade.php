<script>
    $(document).ready(function() {

        function alphaNumSpacePeriod(e) {
            const charCode = e.which || e.keyCode;
            return (
                (charCode >= 65 && charCode <= 90) || // A-Z
                (charCode >= 97 && charCode <= 122) || // a-z
                (charCode >= 48 && charCode <= 57) || // 0-9
                [32, 33, 34, 43, 45, 46, 47, 58, 59, 63, 64, 91, 93, 95, 44].includes(charCode) // Special chars
            );
        }

        function numeric(e) {
            const charCode = e.which || e.keyCode;
            return charCode >= 48 && charCode <= 57;
        }

        function numericPeriod(e) {
            const charCode = e.which || e.keyCode;
            return (
                (charCode >= 48 && charCode <= 57) || // 0-9
                charCode === 46 || // .
                charCode === 43    // +
            );
        }

        // Apply to alpha-numeric + special
        $('.alphaNumSpace').on('keypress', function(e) {
            if (!alphaNumSpacePeriod(e)) {
                e.preventDefault();
            }
        });

        // Numeric only
        $('.numeric').on('keypress', function(e) {
            if (!numeric(e)) {
                e.preventDefault();
            }
        });

        // Numeric + period + plus
        $('.numeric_period').on('keypress', function(e) {
            if (!numericPeriod(e)) {
                e.preventDefault();
            }
        });

    });
</script>
<script>

    document.getElementById('email').addEventListener('blur', function () {
        const email = this.value.trim();
        const errorSpan = document.getElementById('email-error');
        const emailPattern = /^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailPattern.test(email)) {
            errorSpan.classList.remove('hidden');
        } else {
            errorSpan.classList.add('hidden');
        }
    });

    document.getElementById('alternativemail').addEventListener('blur', function () {
        const email = this.value.trim();
        const errorSpan = document.getElementById('email-error');
        const emailPattern = /^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailPattern.test(email)) {
            errorSpan.classList.remove('hidden');
        } else {
            errorSpan.classList.add('hidden');
        }
    });
</script>
<script>
    function SameAddress() {
        if (document.getElementById('myCheck').checked) {
            document.getElementById('billing_address').value = document.getElementById('primary_address').value;
        } else {
            document.getElementById('billing_address').value = "";
        }
    }
</script>
<script type="text/javascript">
    window.onload = function() {
        var existing_founding_year = document.getElementById("existing_founding_year").value;
        var founding_year = document.getElementById("founding_year");
        var currentYear = (new Date()).getFullYear();
        for (var i = 1950; i <= currentYear; i++) {
            var option = document.createElement("OPTION");
            option.innerHTML = i;
            option.value = i;
            if (i == existing_founding_year) {
                option.selected = true;
            }
            founding_year.appendChild(option);
        }
    };
</script>
<script>
    function checkURL(input) {
     var string = input.value.trim(); // Trim whitespace
     if (string && !string.startsWith("http://") && !string.startsWith("https://")) {
         input.value = "http://" + string;
     }
 }
</script>

<script>
 const docSelect = document.getElementById('doc_id');
 const issueDate = document.getElementById('issueDate');
 const expireDate = document.getElementById('expireDate');

 docSelect.addEventListener('change', function() {
     if (this.value === "7" || this.value === "8" || this.value === "9" || this.value === "10" || this
         .value === "11" || this.value === "12" || this.value === "13") {
         issueDate.disabled = true;
         expireDate.disabled = true;
         issueDate.value = '';
         expireDate.value = '';
     } else {
         issueDate.disabled = false;
         expireDate.disabled = false;
     }
 });
</script>
