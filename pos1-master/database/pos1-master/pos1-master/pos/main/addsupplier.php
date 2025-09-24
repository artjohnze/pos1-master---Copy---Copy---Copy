<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="savesupplier.php" method="post">
    <center>
        <h4><i class="icon-plus-sign icon-large"></i> Add Supplier</h4>
    </center>
    <hr>
    <div id="ac">
        <span>Supplier Name : </span><input type="text" style="width:265px; height:30px;" name="name" required /><br>
        <span>Address : </span><input type="text" style="width:265px; height:30px;" name="address" required /><br>
        <span>Contact Person : </span><input type="text" style="width:265px; height:30px;" name="contact" /><br>
        <span>Contact No. : </span><input type="text" style="width:265px; height:30px;" name="cperson" required /><br>
        <span>Note : </span><textarea style="width:265px; height:80px;" name="note" /></textarea><br>
        <div style="float:right; margin-right:10px;">
            <button id="saveBtn" class="btn btn-success btn-block btn-large" style="width:267px; opacity:0.5;" disabled><i class="icon icon-save icon-large"></i> Save</button>
        </div>
    </div>
</form>

<script>
    function validateForm() {
        var name = document.querySelector('input[name="name"]');
        var address = document.querySelector('input[name="address"]');
        var cperson = document.querySelector('input[name="cperson"]');
        var saveBtn = document.getElementById('saveBtn');

        // Remove existing error messages
        var existingErrors = document.querySelectorAll('.error-message');
        existingErrors.forEach(function(error) {
            error.remove();
        });

        var isValid = true;

        // Validate Supplier Name
        if (!name.value.trim()) {
            showError(name, 'Supplier Name is required');
            isValid = false;
        }

        // Validate Address
        if (!address.value.trim()) {
            showError(address, 'Address is required');
            isValid = false;
        }

        // Validate Contact No
        if (!cperson.value.trim()) {
            showError(cperson, 'Contact No. is required');
            isValid = false;
        }

        // Enable/disable save button
        if (isValid) {
            saveBtn.disabled = false;
            saveBtn.style.opacity = '1';
            saveBtn.style.cursor = 'pointer';
        } else {
            saveBtn.disabled = true;
            saveBtn.style.opacity = '0.5';
            saveBtn.style.cursor = 'not-allowed';
        }

        return isValid;
    }

    function showError(input, message) {
        var errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        errorDiv.style.marginLeft = '0px';
        errorDiv.textContent = message;
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }

    // Prevent form submission if validation fails
    function handleSubmit(event) {
        if (!validateForm()) {
            event.preventDefault();
            alert('Please fill in all required fields before saving.');
            return false;
        }
        return true;
    }

    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form');
        var name = document.querySelector('input[name="name"]');
        var address = document.querySelector('input[name="address"]');
        var cperson = document.querySelector('input[name="cperson"]');

        // Add form submit event listener
        form.addEventListener('submit', handleSubmit);

        // Add input event listeners
        name.addEventListener('input', validateForm);
        name.addEventListener('blur', validateForm);
        address.addEventListener('input', validateForm);
        address.addEventListener('blur', validateForm);
        cperson.addEventListener('input', validateForm);
        cperson.addEventListener('blur', validateForm);

        // Initial validation
        validateForm();
    });
</script>