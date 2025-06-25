const datePickerElementList = Array.prototype.slice.call(document.querySelectorAll('.date-picker'))
const datePickerList = datePickerElementList.map(datePickerEl => {
  return new coreui.DatePicker(datePickerEl)
})