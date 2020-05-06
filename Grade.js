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
                status.innerHTML = "Grade failed to submit. " + this.responseXML.documentElement.attributes.reason.nodeValue;
            }
        }
    }
    xml.open("POST", "SubmitGrade.php");
    xml.send(fd);
    return false;
}

function updateScore() {
    let arr = this.document.getElementsByName("CriteriaGrade");
    let totalGrade = 0;
    let maxGrade = 0;
    for(let i = 0; i < arr.length; i++) {
        if(arr[i].type == "number") {
            totalGrade += parseInt(arr[i].value);
        } else {
            if(arr[i].checked) {
                totalGrade += parseInt(arr[i].max);
            }
        }
        maxGrade += parseInt(arr[i].max);
    }
    this.document.getElementById("TotalGrade").innerHTML = totalGrade + " out of " + maxGrade + " points.";
    let grade = this.document.getElementById("Grade");
    grade.value = totalGrade;
}