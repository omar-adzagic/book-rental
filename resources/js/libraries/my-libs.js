// Basic
export const checkIfNotEmpty = value => value !== null && value !== undefined && value !== "";
export const checkIfEveryNotEmpty = (values) => {
    let args = [...arguments];
    console.log(args);
};

// Numbers
export const roundNumber = (number, decimalPlaces) => {
    const factorOfTen = Math.pow(10, decimalPlaces);
    return Math.round(number * factorOfTen) / factorOfTen;
};

// Strings
export const cropText = (text, limit) => text.length > limit ? text.substring(0, limit) + "..." : text;
export const stripHtmlTags = string => string.replace(/(<([^>]+)>)/gi,"");
export const stripHtmlTagsAndCropText = (text, limit) => cropText(stripHtmlTags(text), limit);
export const snakeCaseToCamelCase = str => str.replace(/([-_][a-z])/g, group => {
    return group.toUpperCase().replace('-', '').replace('_', '');
});

// Arrays
export const checkIfEmptyArray = array => array.length === 0 ? true : false;
export const moveArrayElement = (array, oldIndex, newIndex) => {
    console.log(array.map(item => item.order_number));
    if (newIndex >= array.length) {
        let k = newIndex - array.length + 1;
        while (k--) {
            array.push(undefined);
        }
    }
    array.splice(newIndex, 0, array.splice(oldIndex, 1)[0]);
    return array;
};

// Objects
export const setAllObjectPropertiesToBoolean = (object, boolean) => {
    for (let property in object) {
        object[property] = boolean;
    }
};

// Moment.js
export const convertToMysqlDateFormat = stringDate => {
    const dateMomentObject = moment(stringDate, "DD/MM/YYYY");
    const dateObject = dateMomentObject.toDate();
    return moment(dateObject).format('YYYY-MM-DD');
};
export const convertToDateFormat = (date, format) => {
    // const dateMomentObject = moment(stringDate, "YYYY-MM-DD");
    // const dateObject = date.toDate();
    // console.log(dateObject);
    return moment(date).format(format);
};
export const convertToDateTimeFormat = (stringDate, format) => {
    const dateMomentObject = moment(stringDate, "YY-MM-DD hh:mm:ss");
    const dateObject = dateMomentObject.toDate();
    return moment(dateObject).format(format);
};

export const getWeekdayFromDate = (stringDate, lang) => {
    const dateMomentObject = moment(stringDate, "YY-MM-DD hh:mm:ss");
    const dateObject = dateMomentObject.toDate();
    const weekDayNumber = moment(dateObject).weekday();
    switch (lang) {
        case 'me': return getWeekdayMe(weekDayNumber);
        case 'en': return getWeekdayEn(weekDayNumber);
    }
};

const getWeekdayMe = (weekdayNumber) => {
    switch(weekdayNumber) {
        case 0: return 'Nedjelja';
        case 1: return 'Ponedjeljak';
        case 2: return 'Utorak';
        case 3: return 'Srijeda';
        case 4: return 'Četvrtak';
        case 5: return 'Petak';
        case 6: return 'Subota';
    }
};
const getWeekdayEn = (weekdayNumber) => {
    switch(weekdayNumber) {
        case 0: return 'Sunday';
        case 1: return 'Monday';
        case 2: return 'Tuesday';
        case 3: return 'Wednesday';
        case 4: return 'Thursday';
        case 5: return 'Friday';
        case 6: return 'Saturday';
    }
};

export const resetTabs = activeTabObject => {
    for (let key in activeTabObject) {
        activeTabObject[key] = key === Object.keys(activeTabObject)[0];
    }
};

// Validation
export const checkForSingleValidationErrorsNoTabs = (errors, dataErrors, fields) => {
    fields.forEach(field => {
        const camelCaseField = snakeCaseToCamelCase(field);
        const camelCaseFieldErrorPresent = camelCaseField + "ErrorPresent";
        if (errors.hasOwnProperty(field)) {
            dataErrors[camelCaseField] = errors[field][0];
            dataErrors[camelCaseFieldErrorPresent] = true;
        }
    });
};
export const checkForSingleValidationErrors = (errors, dataErrors, dataTabErrors, tabsAndFields) => {
    for (let tab in tabsAndFields) {
        tabsAndFields[tab].forEach(field => {
            const camelCaseField = snakeCaseToCamelCase(field);
            const camelCaseFieldErrorPresent = camelCaseField + "ErrorPresent";
            if (errors.hasOwnProperty(field)) {
                dataErrors[camelCaseField] = errors[field][0];
                dataErrors[camelCaseFieldErrorPresent] = true;
                dataTabErrors[tab] = true;
            }
        });
    }
};
export const resetErrors = (errorsObject) => {
    for (let error in errorsObject) {
        errorsObject[error] = error.includes("ErrorPresent") ? false : "";
    }
};

// Files
export const getFileNameFromPath = path => {
    const pathArray = path.split("/");
    console.log(pathArray);
    return pathArray[pathArray.length - 1];
};

// Form data
export const appendFormDataInputs = (formData, dataObject, fields) => fields.forEach(field => formData.append(field, dataObject[field]));
export const appendFormDataInputsIfNotEmpty = (formData, dataObject, fields) => fields.forEach(field => {
    if (checkIfNotEmpty(dataObject[field])) {
        formData.append(field, dataObject[field]);
    }
});

// Form fields
export const allowOnlyNumbers = event => {
    event = (event) ? event : window.event;
    const charCode = (event.which) ? event.which : event.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        event.preventDefault();
    } else {
        return true;
    }
};

// SweetAlert
export const swalNotification = (type, message) => {
    switch(type) {
        case "success":
            return Swal.fire({
                icon: type,
                title: "Success!",
                text: message,
                showConfirmButton: false,
                timer: 2000
            });
        case "success-confirm":
            return Swal.fire({
                icon: "success",
                title: "Success!",
                text: message,
                showConfirmButton: true,
                confirmButtonText: "OK"
            });
        case "warning":
            return Swal.fire({
                icon: type,
                title: "Warning!",
                text: message,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#138496'
            });
        case "error":
            return Swal.fire({
                icon: type,
                title: "Invalid action!",
                text: message,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#138496'
            });
        case "error-title":
            return Swal.fire({
                icon: 'error',
                title: "Error!",
                text: message,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#138496'
            });
    }
};
