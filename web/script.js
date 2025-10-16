function toggleTextfield(){
    let field = document.getElementById('dropdown');
    let textbox = document.getElementById('miscellaneous-text')
    switch (field.value) {
        case 'miscellaneous':
            textbox.classList.toggle('d-none')
            break;
        default: textbox.classList.add('d-none')
            break;
    }
}

function addErrorUI(obj) {
        obj.classList.remove('success-border')
        obj.classList.add('error-border')
}


function addSuccessUI(obj) {
        obj.classList.add('success-border')
        obj.classList.remove('error-border')
}


function inputValidationNumber(obj) {
    let errorMessage = obj.nextElementSibling
    if (!integerValidation(obj)) {
        errorMessage.classList.remove('d-none')
        addErrorUI(obj)
    } else {
        errorMessage.classList.add('d-none');
        addSuccessUI(obj)
      }       
}

function inputValidationPhone(obj){
    let errorMessage = obj.nextElementSibling
    if (!phoneValidation(obj)) {
        addErrorUI(obj)
    } else {
        errorMessage.classList.add('d-none');
        addSuccessUI(obj)
      } 
}

function inputValidationString(obj) {
    if (!stringValidation(obj)) {
        obj.classList.remove('success-border')
    } else 
        obj.classList.add('success-border')
}


function inputValidationMail(obj){
    let errorMessage = obj.nextElementSibling
     if (!mailValidation(obj)) {
        addErrorUI(obj)
    } else {
        errorMessage.classList.add('d-none');
        addSuccessUI(obj)
      } 
}

function integerValidation(e){
    let regex = /\d+$/;
    let test = e.value != "" && regex.test(e.value) && e.value.length >= e?.min && e.value.length <= e?.max === true
        return test
}

function stringValidation(e) {
    let regexString = /^[\p{L}\p{N}]+(?: +[\p{L}\p{N}]+)*$/u
    return regexString.test(e.value)
}

function phoneValidation(e) {
    let regexPhone = /^(\+49|0)([1-9][0-9]{1,4})[ \-]?[0-9]{4,}$/
    return regexPhone.test(e.value)
}

function mailValidation(e) {
    let regexMail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    return regexMail.test(e.value)
}

function fileValidation(inputFile){
    return inputFile.files.length > 0
}

function dropdownValidation(dropdown) {
     if (dropdown.value != "") {
          return dropdown.value
  }   else return
}


function inputValidation(){
    let inputInteger = document.getElementsByName('integerInput[]')
    let inputString = document.getElementsByName('stringInput[]')
    let inputPhone = document.getElementsByName('phoneInput')
    let inputMail = document.getElementsByName('mailInput')
    let inputFile = document.getElementById('disposal-file')
    let dropdown = document.getElementById('dropdown')
    
    let isValidInteger = [...inputInteger].every((e) => {return integerValidation(e)})
    let isValidString = [...inputString].every((e) => {return stringValidation(e)})
    let isValidPhone = [...inputPhone].every((e) => {return phoneValidation(e)})
    let isValidMail = [...inputMail].every((e) => {return mailValidation(e)})
    let isValidFile = fileValidation(inputFile)
    let isValidDropdown = dropdownValidation(dropdown)
    
    return allValid(isValidString,isValidMail,isValidPhone,isValidInteger, isValidFile, isValidDropdown)
}

function allValid(isValidString,isValidMail,isValidPhone,isValidInteger,isValidFile, isValidDropdown){
    console.log(isValidString,isValidMail,isValidPhone,isValidInteger,isValidFile, isValidDropdown);
    
     if (isValidString && isValidMail && isValidPhone && isValidInteger && isValidFile && isValidDropdown){
         console.log("abgeschickt");
         return true
     }   else {console.log("nicht alles ausgefüllt");
        return false}
 }


