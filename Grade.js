function submitGrade(form) {
    // Create new XML request
    let xml = new XMLHttpRequest();
    let fd = new FormData(form);
    // Define response to XML request
    xml.onreadystatechange = function() {
        // If request was successfull
        if(this.readyState == 4 && this.status == 200) {
            let status = document.getElementById("status");
            // If server responded with OK, meaning grade was added properly
            if(this.responseXML.documentElement.attributes.status.value == "SUCCESS") {
                status.innerHTML = "Grade submitted successfully!";
            } else {
                status.innerHTML = "Grade failed to submit. " + this.responseXML.documentElement.attributes.reason;
            }
        }
    }
    xml.open("POST", "/Agile-Experience-5/SubmitGrade.php");
    xml.send(fd);
    return false;
}